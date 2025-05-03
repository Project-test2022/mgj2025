<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function title(): View
    {
        return view('pages.title');
    }

    public function home(): View
    {
        return view('pages.home');
    }
}
