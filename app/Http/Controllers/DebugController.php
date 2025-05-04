<?php

namespace App\Http\Controllers;

use App\Entities\Event;
use App\Entities\EventResult;
use App\Entities\Player;
use App\Models\SexModel;
use App\ValueObjects\Ability;
use App\ValueObjects\Action;
use App\ValueObjects\BirthYear;
use App\ValueObjects\Business;
use App\ValueObjects\Evaluation;
use App\ValueObjects\Happiness;
use App\ValueObjects\Health;
use App\ValueObjects\Income;
use App\ValueObjects\Intelligence;
use App\ValueObjects\Job;
use App\ValueObjects\Money;
use App\ValueObjects\PlayerId;
use App\ValueObjects\PlayerName;
use App\ValueObjects\Sense;
use App\ValueObjects\SexName;
use App\ValueObjects\Sport;
use App\ValueObjects\Turn;
use App\ValueObjects\Visual;
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
        $actions = [
            Action::from('遊び'),
            Action::from('仕事'),
            Action::from('勉強'),
            Action::from('運動'),
            Action::from('食事'),
        ];

        return view('pages.home', [
            'player' => $this->player(),
            'actions' => $actions,
        ]);
    }

    public function select(): View
    {
        return view('pages.select', [
            'player' => $this->player(),
            'event' => Event::dummy(),
            'action' => Action::from('遊び'),
        ]);
    }

    public function event(): View
    {
        $result = EventResult::dummy(true);

        return view('pages.event', [
            'result' => $result,
            'player' => $this->player(),
            'newJob' => null,
            'incomeDiff' => null,
        ]);
    }

    public function end(): View
    {
        return view('pages.end', [
            'player' => $this->player(),
            'sexCode' => '1',
        ]);
    }

    private function player(): Player
    {
        return new Player(
            PlayerId::from(1),
            PlayerName::from('山田 太郎'),
            SexName::from('男'),
            BirthYear::from(2000),
            Turn::from('20'),
            Money::from(1000000),
            Health::from(100),
            Ability::from(
                Intelligence::from(10),
                Sport::from(10),
                Visual::from(10),
                Sense::from(10),
            ),
            Evaluation::from(
                Business::from(10),
                Happiness::from(10),
            ),
            null,
            null,
            null,
            Job::from('教師'),
            Income::from(300000),
            null,
        );
    }
}
