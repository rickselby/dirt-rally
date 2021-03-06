<?php

namespace App\Interfaces\DirtRally;

use App\Models\DirtRally\DirtChampionship;
use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;

interface DriverPointsInterface
{
    /**
     * Get driver points for an event
     * @param DirtEvent $event
     * @return mixed
     */
    public function forEvent(DirtEvent $event);

    /**
     * Get points for the given system for the given season
     * @param DirtSeason $season
     * @return array
     */
    public function forSeason(DirtSeason $season);

    /**
     * Get points for the given system for each event in the given championship
     * @param DirtChampionship $championship
     * @return array
     */
    public function overview(DirtChampionship $championship);

    /**
     * Get overall points for the given system (on the given collection of seasons)
     * @param DirtChampionship $championship
     * @return array
     */
    public function overall(DirtChampionship $championship);

}