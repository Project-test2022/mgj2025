<?php

namespace App\Http\Controllers;

use App\Models\SexModel;
use Illuminate\View\View;

class DebugController extends Controller
{
    public function title(): View
    {
        $male = new SexModel();
        $male->sex_cd = '1';
        $male->sex_nm = '男';

        $female = new SexModel();
        $female->sex_cd = '2';
        $female->sex_nm = '女';

        $other = new SexModel();
        $other->sex_cd = '3';
        $other->sex_nm = '不明';

        $sexes = [
            $male,
            $female,
            $other,
        ];

        return view('pages.title', [
            'sexes' => $sexes,
        ]);
    }

    public function home(): View
    {
        return view('pages.home');
    }

    public function select(): View
    {
        return view('pages.select');
    }

    public function event(): View
    {
        return view('pages.event');
    }

    public function end(): View
    {
        return view('pages.end');
    }
}
