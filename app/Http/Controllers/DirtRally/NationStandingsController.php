<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\Championship;
use App\Models\DirtRally\Event;
use App\Models\DirtRally\Point;
use App\Models\DirtRally\PointsSystem;
use App\Models\DirtRally\Season;
use App\Models\DirtRally\Stage;

use App\Http\Requests;

class NationStandingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('dirt-rally.validateSeason')->only(['season']);
        $this->middleware('dirt-rally.validateEvent')->only(['event']);
        $this->middleware('dirt-rally.validateStage')->only(['stage']);
    }

    public function index()
    {
        return view('dirt-rally.nationstandings.index')
            ->with('systems', PointsSystem::all());

    }

    public function system(PointsSystem $system)
    {
        return view('dirt-rally.nationstandings.system')
            ->with('system', $system)
            ->with('championships', Championship::all()->sortBy('closes'));
    }

    public function championship(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.championship')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyNationPoints::overall($system, $seasons));
    }

    public function overview(PointsSystem $system, Championship $championship)
    {
        $seasons = $championship->seasons()->with(['events.stages.results.driver.nation', 'events.positions.driver.nation'])->get()->sortBy('closes');
        return view('dirt-rally.nationstandings.overview')
            ->with('system', $system)
            ->with('championship', $championship)
            ->with('seasons', $seasons)
            ->with('points', \DirtRallyNationPoints::overview($system, $seasons));
    }

    public function season(PointsSystem $system, $championship, $season)
    {
        $season = \Request::get('season');
        $season->load(['events.stages.results.driver.nation', 'events.positions.driver.nation', 'championship']);
        return view('dirt-rally.nationstandings.season')
            ->with('system', $system)
            ->with('season', $season)
            ->with('points', \DirtRallyNationPoints::forSeason($system, $season));
    }

    public function event(PointsSystem $system, $championship, $season, $event)
    {
        $event = \Request::get('event');
        $event->load(['season.championship', 'stages.results.driver.nation', 'positions.driver.nation']);
        return view('dirt-rally.nationstandings.event')
            ->with('system', $system)
            ->with('event', $event)
            ->with('points', \DirtRallyNationPoints::forEvent($system, $event));
    }
}