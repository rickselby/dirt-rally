<?php

namespace App\Http\Controllers\AssettoCorsa;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssettoCorsa\ChampionshipRaceRequest;
use App\Jobs\AssettoCorsa\ImportQualifyingJob;
use App\Jobs\AssettoCorsa\ImportRaceJob;
use App\Models\AssettoCorsa\AcChampionship;
use App\Models\AssettoCorsa\AcRace;
use App\Services\AssettoCorsa\Import;
use App\Services\AssettoCorsa\Results;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

class ChampionshipRaceController extends Controller
{
    use DispatchesJobs;

    public function __construct()
    {
        $this->middleware('admin');
        $this->middleware('assetto-corsa.validateRace', ['except' => ['create', 'store']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param AcChampionship $championship
     * @return \Illuminate\Http\Response
     */
    public function create(AcChampionship $championship)
    {
        return view('assetto-corsa.race.create')
            ->with('championship', $championship);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChampionshipRaceRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChampionshipRaceRequest $request, AcChampionship $championship)
    {
        /** @var AcRace $race */
        $race = AcRace::create($request->all());
        $championship->races()->save($race);
        \Notification::add('success', 'Race "'.$race->name.'" created');
        return \Redirect::route('assetto-corsa.championship.race.show', [$championship, $race]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $championship
     * @param  string $race
     * @return \Illuminate\Http\Response
     */
    public function show($championship, $race, Results $resultsService)
    {
        $race = \Request::get('race');
        $race->load('entrants.championshipEntrant.driver');
        return view('assetto-corsa.race.show')
            ->with('race', $race);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string $championship
     * @param  string $race
     * @return \Illuminate\Http\Response
     */
    public function edit($championship, $race)
    {
        return view('assetto-corsa.race.edit')
            ->with('race', \Request::get('race'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ChampionshipRaceRequest $request
     * @param  string $championship
     * @param  string $race
     * @return \Illuminate\Http\Response
     */
    public function update(ChampionshipRaceRequest $request, $championship, $race)
    {
        $race = \Request::get('race');
        $race->fill($request->all());
        $race->save();
        \Notification::add('success', 'Race "'.$race->name.'" updated');
        return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $championship
     * @param  string $race
     * @return \Illuminate\Http\Response
     */
    public function destroy($championship, $race)
    {
        $race = \Request::get('race');
        if ($race->entrants()->count()) {
            \Notification::add('error', 'Race "'.$race->name.'" cannot be deleted - there are results entered');
            return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
        } else {
            $race->delete();
            \Notification::add('success', 'Race "'.$race->name.'" deleted');
            return \Redirect::route('assetto-corsa.championship.show', $race->championship);
        }
    }

    public function qualifyingResultsUpload(Request $request, $championship, $race, Import $import)
    {
        $race = \Request::get('race');
        $import->saveUpload($request, $race, true);
        if (!count($race->entrants)) {
            // Need entrants first
            return \Redirect::route('assetto-corsa.championship.race.entrants', [$race->championship, $race])
                ->with('from', 'qualifying');
        } else {
            $this->dispatch(new ImportQualifyingJob($race));
            \Notification::add('success', 'Qualifying import job queued. Results will be imported shortly.');
            return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
        }
    }

    public function raceResultsUpload(Request $request, $championship, $race, Import $import)
    {
        $race = \Request::get('race');
        $import->saveUpload($request, $race);
        if (!count($race->entrants)) {
            // Need entrants first
            return \Redirect::route('assetto-corsa.championship.race.entrants', [$race->championship, $race])
                ->with('from', 'race');
        } else {
            $this->dispatch(new ImportRaceJob($race));
            \Notification::add('success', 'Race import job queued. Results will be imported shortly.');
            return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
        }
    }

    public function entrants($championship, $race, Import $import)
    {
        $race = \Request::get('race');
        return view('assetto-corsa.race.entrants')
            ->with('race', $race)
            ->with('entrants', $import->processEntrants($race, (session('from') == 'qualifying')));
    }

    public function saveEntrants(Request $request, $championship, $race, Import $import)
    {
        $race = \Request::get('race');
        $import->saveEntrants($request, $race);
        \Notification::add('success', 'Entrants and '.($request->get('from') == 'qualifying' ? 'qualifying' : 'race').' results added');
        return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
    }

    public function updateEntrants(Request $request, $championship, $race)
    {
        $race = \Request::get('race');
        \ACEntrants::updateCarsAndColours($request, $race);
        \Notification::add('success', 'Entrants updated');
        return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
    }

    public function updateReleaseDate(Request $request, $championship, $race)
    {
        $race = \Request::get('race');
        $race->release = Carbon::createFromFormat('jS F Y, H:i', $request->release);
        $race->save();
        \Notification::add('success', 'Release Date Updated');
        return \Redirect::route('assetto-corsa.championship.race.show', [$race->championship, $race]);
    }
}