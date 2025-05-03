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
}
