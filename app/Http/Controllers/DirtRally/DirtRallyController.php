<?php

namespace App\Http\Controllers\DirtRally;

use App\Http\Controllers\Controller;
use App\Models\DirtRally\Championship;
use App\Models\DirtRally\PointsSystem;
use App\Services\DirtRally\Championships;

class DirtRallyController extends Controller
{
    public function index(Championships $championships)
    {
        return view('dirt-rally.index')
            ->with('currentChampionship', $championships->getCurrent())
            ->with('completeChampionships', $championships->getComplete())
            ->with('defaultPointSystem', PointsSystem::where('default', true)->first());
    }
}