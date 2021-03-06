<?php

namespace App\Models\Races;

class RacesPenalty extends \Eloquent
{
    protected $fillable = ['points', 'reason'];

    public function entrant()
    {
        return $this->belongsTo(RacesSessionEntrant::class, 'races_session_entrant_id');
    }

    public function scopeForSession($query, RacesSession $session)
    {
        return $query->leftJoin('races_session_entrants', 'races_session_entrants.id', '=', 'races_penalties.races_session_entrant_id')
            ->where('races_session_entrants.races_session_id', '=', $session->id)
            ->select('races_penalties.*');
    }

    public function getEventSummaryAttribute()
    {
        return $this->entrant->session->name.': '.$this->points.' points: '.$this->reason;
    }

    public function getTeamEventSummaryAttribute()
    {
        return $this->entrant->session->name.': '.$this->entrant->championshipEntrant->driver->name.': '.$this->points.' points: '.$this->reason;
    }

    public function getChampionshipSummaryAttribute()
    {
        return $this->entrant->session->event->name.' '.$this->eventSummary;
    }

    public function getTeamChampionshipSummaryAttribute()
    {
        return $this->entrant->session->event->name.' '.$this->teamEventSummary;
    }

}
