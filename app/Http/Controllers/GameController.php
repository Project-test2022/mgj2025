<?php

namespace App\Http\Controllers;

use App\Factories\PlayerFactory;
use App\Repositories\PlayerRepository;
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
            // TODO: Dify でイベントを生成する
            $message = '仕事イベント';
        } elseif ($request->has('love')) {
            // TODO: Dify でイベントを生成する
            $message = '恋愛イベント';
        } else {
            return redirect()->route('home', ['id' => $playerId]);
        }

        return redirect()->route('event', ['id' => $playerId])->with([
            'message' => $message,
        ]);
    }

    public function event(Request $request): View|RedirectResponse
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

        return view('pages.select', [
            'player' => $player,
            'message' => $message,
        ]);
    }
}
