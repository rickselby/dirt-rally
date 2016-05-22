<?php

namespace App\Models\DirtRally;

use App\Models\PointsSequence;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;


class DirtChampionship extends \Eloquent implements SluggableInterface
{
    use SluggableTrait;

    protected $fillable = ['name', 'event_points_sequence', 'stage_points_sequence'];

    protected $sluggable = [
        'build_from' => 'shortName'
    ];

    public function seasons()
    {
        // Can't sort at database level
        return $this->hasMany(DirtSeason::class);
    }

    public function eventPointsSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'event_points_sequence');
    }

    public function stagePointsSequence()
    {
        return $this->belongsTo(PointsSequence::class, 'stage_points_sequence');
    }

    public function getOpensAttribute()
    {
        $dates = [];
        foreach($this->seasons AS $season) {
            $dates[] = $season->opens;
        }
        if (count($dates)) {
            return min($dates);
        } else {
            // No seasons; push to bottom of list
            return Carbon::now();
        }
    }

    public function getClosesAttribute()
    {
        $dates = [];
        foreach($this->seasons AS $season) {
            $dates[] = $season->closes;
        }
        if (count($dates)) {
            return max($dates);
        } else {
            // No seasons; push to bottom of list
            return Carbon::now();
        }
    }

    public function getShortNameAttribute()
    {
        return trim(str_ireplace('championship', '', $this->name));
    }

    public function isComplete() {
        foreach($this->seasons AS $season) {
            if (!$season->isComplete()) {
                return false;
            }
        }
        return true;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
