<?php

namespace App\Http\Controllers;

use App\Entities\Event;
use App\Entities\EventResult;
use App\Http\Requests\CreatePlayerRequest;
use App\Models\SexModel;
use App\Repositories\BackgroundRepository;
use App\Repositories\PlayerFaceRepository;
use App\Services\EventAppService;
use App\Services\PlayerAppService;
use App\Services\Utility;
use App\ValueObjects\BackgroundId;
use App\ValueObjects\BirthYear;
use App\ValueObjects\EventSituation;
use App\ValueObjects\PlayerFaceId;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexCode;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

final class GameController extends Controller
{
    public function __construct(
        private readonly PlayerFaceRepository $playerFaceRepository,
        private readonly BackgroundRepository $backgroundRepository,
        private readonly PlayerAppService $playerAppService,
        private readonly EventAppService $eventAppService,
    ) {
    }

    public function title(): View
    {
        $sexes = SexModel::all();

        return view('pages.title', [
            'sexes' => $sexes,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function start(CreatePlayerRequest $request): RedirectResponse
    {
        $name = $request->has('name_random') ? null : $request->input('name');
        $birthYear = $request->has('birth_year_random') ? null : $request->input('birth_year');
        $sexCode = $request->input('gender_random') ? null : $request->input('gender');

        DB::beginTransaction();
        try {
            // プレイヤーの作成
            $playerId = $this->playerAppService->create(
                PlayerName::tryFrom($name),
                BirthYear::tryFrom($birthYear),
                SexCode::tryFrom($sexCode),
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('home', ['id' => $playerId]);
    }

    public function home(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerAppService->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }
        if ($player->isDeleted) {
            return redirect()->route('result', ['id' => $playerId]);
        }

        return view('pages.home', [
            'player' => $player,
        ]);
    }

    public function select(Request $request): RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerAppService->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        if ($request->has('business')) {
            $situation = EventSituation::BUSINESS;
        } elseif ($request->has('happiness')) {
            $situation = EventSituation::HAPPINESS;
        } else {
            return redirect()->route('home', ['id' => $playerId]);
        }

        try {
            $event = $this->eventAppService->generate($player, $situation);

            return redirect()->route('event', ['id' => $playerId])->with([
                'event' => $event,
                'situation' => $situation,
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return redirect()->route('error', ['id' => $playerId]);
        }
    }

    public function event(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerAppService->find(PlayerId::from($playerId));
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
        $player = $this->playerAppService->find(PlayerId::from($playerId));
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
        DB::beginTransaction();
        try {
            // イベント結果を取得
            $eventResult = $this->eventAppService->result(
                $player,
                $situation,
                $event,
                $choice,
            );

            // プレイヤーの情報を更新
            $player = $this->playerAppService->update($player, $eventResult);

            if ($eventResult->dead) {
                $this->playerAppService->dead($player);
            } else {
                // 次のターンへ
                $this->playerAppService->nextTurn($player);
            }

            DB::commit();

            return redirect()->route('event.result', ['id' => $playerId])->with([
                'result' => $eventResult,
            ]);
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('error', ['id' => $playerId]);
        }
    }

    public function eventResult(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerAppService->find(PlayerId::from($playerId));
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

    public function result(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerAppService->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        return view('pages.end', [
            'player' => $player,
        ]);
    }

    public function error(Request $request): View
    {
        return view('pages.error', [
            'id' => $request->route('id'),
        ]);
    }

    public function face(Request $request)
    {
        $playerFaceId = $request->route('id') ?? null;
        $default = asset('images/player-default.png');
        if ($playerFaceId === null) {
            return Utility::getImageResponse(file_get_contents($default));
        } else {
            $image = $this->playerFaceRepository->find(PlayerFaceId::from($playerFaceId));
            if ($image === null) {
                return Utility::getImageResponse(file_get_contents($default));
            }
            return Utility::getImageResponse($image->image);
        }
    }

    public function background(Request $request)
    {
        $bgId = $request->route('id') ?? null;
        $default = asset('images/background.png');
        if ($bgId === null) {
            return Utility::getImageResponse(file_get_contents($default));
        } else {
            $image = $this->backgroundRepository->find(BackgroundId::from($bgId));
            if ($image === null) {
                return Utility::getImageResponse(file_get_contents($default));
            }
            return Utility::getImageResponse($image->image);
        }
    }
}
