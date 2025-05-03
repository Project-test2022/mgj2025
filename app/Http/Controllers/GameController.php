<?php

namespace App\Http\Controllers;

use App\Dify\DifyApi;
use App\Entities\Event;
use App\Entities\EventResult;
use App\Factories\PlayerFaceFactory;
use App\Factories\PlayerFactory;
use App\Repositories\PlayerFaceRepository;
use App\Repositories\PlayerRepository;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Choice;
use App\ValueObjects\EventSituation;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexName;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Throwable;

final class GameController extends Controller
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerFactory $playerFactory,
        private readonly DifyApi $dify,
        private readonly PlayerFaceRepository $playerFaceRepository,
        private readonly PlayerFaceFactory $playerFaceFactory,
    ) {
    }

    public function title(): View
    {
        return view('pages.title');
    }

    /**
     * @throws Throwable
     */
    public function start(): RedirectResponse
    {
        $id = $this->playerFactory->generateId();
        [$name, $sex, $birthYear] = $this->dify->createPlayer($id);
        $imgUrl = $this->dify->createPlayerImage($id);

        DB::beginTransaction();
        try {
            $player = $this->playerFactory->create(
                $id,
                PlayerName::from($name),
                SexName::from($sex),
                BirthYear::from($birthYear),
            );
            $this->playerRepository->save($player);

            $img = file_get_contents($imgUrl);
            $playerFace = $this->playerFaceFactory->create($id, $img);
            $this->playerFaceRepository->save($playerFace);

            $player = $player->setFace($playerFace->id);
            $this->playerRepository->save($player);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            return redirect()->route('title');
        }

        return redirect()->route('home', ['id' => $player->id]);
    }

    public function home(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        return view('pages.home', [
            'player' => $player,
        ]);
    }

    public function select(Request $request): RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        if ($request->has('business')) {
            $situation = EventSituation::BUSINESS;
        } elseif ($request->has('love')) {
            $situation = EventSituation::LOVE;
        } else {
            return redirect()->route('home', ['id' => $playerId]);
        }

        $event = $this->dify->event(
            $player,
            $situation,
        );

        return redirect()->route('event', ['id' => $playerId])->with([
            'event' => $event,
            'situation' => $situation,
        ]);
    }

    public function event(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        /** @var Event|null $event */
        $event = $request->session()->get('event');
        /** @var EventSituation|null $situation */
        $situation = $request->session()->get('situation');
        if ($event === null || $situation === null) {
            return redirect()->route('home', ['id' => $playerId]);
        }

        return view('pages.select', [
            'player' => $player,
            'event' => $event,
            'situation' => $situation,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function selectEvent(Request $request): RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        $situation = EventSituation::from($request->input('situation'));
        $event = Event::fromRequest($request);

        if ($request->has('ok')) {
            $choice = $event->choice1;
        } elseif ($request->has('ng')) {
            $choice = $event->choice2;
        } else {
            return redirect()->route('home', ['id' => $playerId]);
        }

        $result = $this->choice($choice);

        $eventResult = $this->dify->eventResult(
            $player,
            $situation,
            $event,
            $choice,
            $result,
        );

        DB::beginTransaction();
        try {
            if ($eventResult->dead) {
                // TODO: 死んだときは終了
            }

            // 結果に応じてステータスを更新する
            $player = $player->update(
                $eventResult->totalMoney,
                $eventResult->health,
                $eventResult->ability,
                $eventResult->evaluation,
            );

            // ターンを進める
            $player = $player->nextTurn();

            $this->playerRepository->save($player);

            DB::commit();
        } catch (Exception) {
            DB::rollBack();
            return redirect()->route('home', ['id' => $playerId]);
        }

        return redirect()->route('event.result', ['id' => $playerId])->with([
            'result' => $eventResult,
        ]);
    }

    public function eventResult(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        /** @var EventResult|null $result */
        $result = $request->session()->get('result');
        if ($result === null) {
            return redirect()->route('home', ['id' => $playerId]);
        }

        return view('pages.event', [
            'player' => $player,
            'result' => $result,
        ]);
    }

    public function face(Request $request)
    {
        $playerFaceId = $request->route('id') ?? null;
        $default = asset('images/dummy-user.svg');
        if ($playerFaceId === null) {
            return response(file_get_contents($default))->header('Content-Type', 'image/svg+xml');
        } else {
            $image = $this->playerFaceRepository->find(PlayerFaceId::from($playerFaceId));
            if ($image === null) {
                return response(file_get_contents($default))->header('Content-Type', 'image/svg+xml');
            }
            return response($image->image)->header('Content-Type', 'image/png');
        }
    }

    private function choice(Choice $choice): bool
    {
        $rate = (float)$choice->rate->value;
        return mt_rand(0, 10000) < ($rate * 100);
    }
}
