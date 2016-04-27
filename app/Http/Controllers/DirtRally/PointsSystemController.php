<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\PointsSequence;
use App\Models\DirtRally\PointsSystem;
use Illuminate\Http\Request;

use App\Http\Requests;

class PointsSystemController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dirt-rally.points-system.index')
            ->with('systems', PointsSystem::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dirt-rally.points-system.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $system = PointsSystem::create($request->all());
        // Also create the two sequences
        $system->eventSequence()->associate(PointsSequence::create([]));
        $system->stageSequence()->associate(PointsSequence::create([]));
        $system->save();

        if ($request->default) {
            $this->setDefault($system->id);
        }

        \Notification::add('success', 'Points System "'.$system->name.'" created');
        return \Redirect::route('dirt-rally.points-system.show', $system);
    }

    /**
     * Display the specified resource.
     *
     * @param  PointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function show(PointsSystem $points_system)
    {
        return view('dirt-rally.points-system.show')
            ->with('system', $points_system)
            ->with('points', \DirtRallyPointSequences::forSystem($points_system));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function edit(PointsSystem $points_system)
    {
        return view('dirt-rally.points-system.edit')
            ->with('system', $points_system);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PointsSystem $points_system)
    {
        $points_system->fill($request->all());
        $points_system->save();

        if ($request->default) {
            $this->setDefault($points_system->id);
        }

        \Notification::add('success', 'Points System "'.$points_system->name.'" updated');
        return \Redirect::route('dirt-rally.points-system.show', $points_system);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PointsSystem $points_system
     * @return \Illuminate\Http\Response
     */
    public function destroy(PointsSystem $points_system)
    {
        $points_system->delete();
        \Notification::add('success', 'Points System deleted');
        return \Redirect::route('dirt-rally.points-system.index');
    }

    public function points(Request $request, PointsSystem $system)
    {
        $system->load(['eventSequence', 'stageSequence']);
        \DirtRallyPointSequences::set($system->eventSequence, $request['event']);
        \DirtRallyPointSequences::set($system->stageSequence, $request['stage']);
        \Notification::add('success', 'Points updated');
        return \Redirect::route('dirt-rally.points-system.show', $system);
    }

    private function setDefault($id)
    {
        \DB::table('points_systems')->update(['default' => false]);
        \DB::table('points_systems')->where('id', $id)->update(['default' => true]);
    }
}