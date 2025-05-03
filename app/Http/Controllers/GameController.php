<?php

namespace App\Http\Controllers;

use App\Factories\PlayerFactory;
use App\Repositories\PlayerRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class GameController extends Controller
{
    public function __construct(
        private readonly PlayerRepository $player_repository,
        private readonly PlayerFactory $player_factory,
    ) {
    }

    public function title(): View
    {
        return view('pages.title');
    }

    public function start(): RedirectResponse
    {
        $player = $this->player_factory->create();
        $this->player_repository->save($player);

        return redirect()->route('home');
    }

    public function home(): View
    {
        return view('pages.home');
    }
}
