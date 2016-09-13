<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Models\AssettoCorsa\AcChampionship;

class StandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('assetto-corsa.validateEvent')->only(['event']);
        $this->middleware('assetto-corsa.validateSession')->only(['lapChart']);
    }

    public function drivers(AcChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.driver.nation', 'entrants.driver.nation');
        $events = $championship->events()->get()->sortBy('time');
        return view('assetto-corsa.standings.drivers')
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\ACDriverStandings::championship($championship)));
    }

    public function constructors(AcChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant', 'events.championship', 'events.sessions.entrants.car');
        $events = $championship->events()->get()->sortBy('time');
        return view('assetto-corsa.standings.constructors')
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\ACConstructorStandings::championship($championship)));
    }

    public function teams(AcChampionship $championship)
    {
        $championship->load('events.sessions.entrants.championshipEntrant.team', 'events.championship');
        $events = $championship->events()->get()->sortBy('time');
        return view('assetto-corsa.standings.teams')
            ->with('championship', $championship)
            ->with('events', $events)
            ->with('points', \Positions::addEquals(\ACTeamStandings::championship($championship)));
    }
}
