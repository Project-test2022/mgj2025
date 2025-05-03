<?php

namespace App\Http\Controllers;

use App\Dify\DifyApi;
use App\Entities\Event;
use App\Factories\PlayerFactory;
use App\Repositories\PlayerRepository;
use App\ValueObjects\Choice;
use App\ValueObjects\EventSituation;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\SexName;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GameController extends Controller
{
    public function __construct(
        private readonly PlayerRepository $playerRepository,
        private readonly PlayerFactory $playerFactory,
        private readonly DifyApi $dify,
    ) {
    }

    public function title(): View
    {
        return view('pages.title');
    }

    public function start(): RedirectResponse
    {
        // TODO: Dify でランダムに生成する
        $name = '山田 太郎';
        $sex = '男';

        // TODO: Dify でキャラ画像を生成する

        $player = $this->playerFactory->create(
            PlayerName::from($name),
            SexName::from($sex),
        );
        $this->playerRepository->save($player);

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

        // TODO: Dify でイベント結果を生成する
        $response = $this->dify->eventResult(
            $player,
            $situation,
            $event,
            $choice,
            $result,
        );

        // TODO: 結果に応じてステータスを更新する

        return redirect()->route('event.result', ['id' => $playerId])->with([

        ]);
    }

    public function eventResult(Request $request): View|RedirectResponse
    {
        $playerId = $request->route('id');
        $player = $this->playerRepository->find(PlayerId::from($playerId));
        if ($player === null) {
            return redirect()->route('title');
        }

        $message = $request->session()->get('message');
        if (empty($message)) {
            return redirect()->route('home', ['id' => $playerId]);
        }

        return view('pages.event', [
            'player' => $player,
            'message' => $message,
        ]);
    }

    private function choice(Choice $choice): bool
    {
        // TODO: イベント成否判定
        return false;
    }
}
