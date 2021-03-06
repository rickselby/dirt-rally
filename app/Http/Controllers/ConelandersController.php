<?php

namespace App\Http\Controllers;

use App\Services\Races\Signups;
use App\Services\News;

class ConelandersController extends Controller
{
    public function index(News $news, Signups $signups)
    {
        return view('index')
            ->with('pastNews', $news->getPast())
            ->with('upcomingNews', $news->getUpcoming())
            ->with('currentNews', $news->getCurrent())
            ->with('signups', $signups->getOpen());
    }

    public function calendar()
    {
        return view('calendar');
    }
}