<?php

namespace App\Services\DirtRally;

use App\Models\DirtRally\DirtEvent;
use App\Models\DirtRally\DirtSeason;
use Illuminate\Database\Eloquent\Collection;

class DriverPoints
{
    public function forEvent(DirtEvent $event)
    {
        $points = [];

        if ($event->isComplete()) {
            $system['event'] = \PointSequences::get($event->season->championship->eventPointsSequence);
            $system['stage'] = \PointSequences::get($event->season->championship->stagePointsSequence);
            /**
             * Get the results for this event, and mangle them into points
             */
            foreach (\DirtRallyResults::getEventResults($event) AS $result) {
                $points[$result['driver']->id] = [
                    'entity' => $result['driver'],
                    'stageTimesByOrder' => $result['stage'],
                    'dnf' => $result['dnf'],
                    'total' => [
                        'time' => $result['total'],
                        'points' => 0
                    ],
                    'stagePoints' => [],
                    'stagePositions' => [],
                    'eventPosition' => $result['position'],
                    'eventPoints' => (isset($system['event'][$result['position']]) && !$result['dnf'] && $result['total'])
                        ? $system['event'][$result['position']]
                        : 0,
                ];
            }

            // Get points for each result for each stage
            foreach ($event->stages AS $stage) {
                foreach ($stage->results AS $result) {
                    $points[$result->driver->id]['stagePoints'][$stage->id] =
                        isset($system['stage'][$result->position]) && !$result->dnf
                            ? $system['stage'][$result->position]
                            : 0;
                    $points[$result->driver->id]['stagePositions'][$stage->id] = $result->position;
                }
                foreach($points AS $driverID => $point) {
                    // Map the stage times by ID, not order
                    $points[$driverID]['stageTimes'][$stage->id] =
                        $point['stageTimesByOrder'][$stage->order];
                }
            }

            // Sum event points and stage points to get total points
            foreach ($points AS $driverID => $point) {
                $points[$driverID]['total']['points'] = $point['eventPoints'];
                foreach ($point['stagePoints'] AS $stagePoint) {
                    $points[$driverID]['total']['points'] += $stagePoint;
                }
            }

            // Sort by points and position
            usort($points, function ($a, $b) {
                if ($a['total']['points'] != $b['total']['points']) {
                    return $b['total']['points'] - $a['total']['points'];
                } else {
                    return $a['eventPosition'] - $b['eventPosition'];
                }
            });

            $points = \Positions::addToArray($points, [$this, 'areEventPointsEqual']);
        }

        return $points;
    }

    /**
     * Check two event results to see if they are equal
     * @param $a
     * @param $b
     * @return bool
     */
    public function areEventPointsEqual($a, $b)
    {
        return ($a['total']['points'] == $b['total']['points'])
            && ($a['eventPosition'] == $b['eventPosition']);
    }

    /**
     * Get points for the given system for the given season
     * @param DirtSeason $season
     * @return array
     */
    public function forSeason(DirtSeason $season)
    {
        $points = [];
        foreach($season->events AS $event) {
            if ($event->isComplete()) {
                foreach ($this->forEvent($event) AS $result) {
                    $points[$result['entity']->id]['entity'] = $result['entity'];
                    $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                    $points[$result['entity']->id]['positions'][$event->id] = $result['position'];
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Get points for the given system for each event in the given championship
     * @param Collection $seasons
     * @return array
     */
    public function overview(Collection $seasons)
    {
        $points = [];
        foreach($seasons AS $season) {
            foreach ($season->events AS $event) {
                if ($event->isComplete()) {
                    foreach ($this->forEvent($event) AS $result) {
                        foreach($result['stagePoints'] AS $stage => $stagePoints) {
                            $points[$result['entity']->id]['stages'][$stage] = $stagePoints;
                        }
                        $points[$result['entity']->id]['events'][$event->id] = $result['eventPoints'];
                        $points[$result['entity']->id]['entity'] = $result['entity'];
                        $points[$result['entity']->id]['points'][$event->id] = $result['total']['points'];
                        $points[$result['entity']->id]['positions'][] = $result['position'];
                    }
                }
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Get overall points for the given system (on the given collection of seasons)
     * @param Collection $seasons
     * @return array
     */
    public function overall(Collection $seasons)
    {
        $points = [];
        // Step through the seasons and pull in results
        foreach($seasons AS $season) {
            foreach ($this->forSeason($season) AS $result) {
                $points[$result['entity']->id]['entity'] = $result['entity'];
                $points[$result['entity']->id]['points'][$season->id] = $result['total'];
                $points[$result['entity']->id]['positions'][$season->id] = $result['position'];
            }
        }

        return $this->sumAndSort($points);
    }

    /**
     * Take a list of points, sum them, and sort them...
     * @param array $points
     * @return array
     */
    protected function sumAndSort($points)
    {
        // Step through each driver, sum their points, and sort their positions
        foreach($points AS $driverID => $point) {
            $points[$driverID]['total'] = array_sum($point['points']);
            $points[$driverID]['sortedPositions'] = $points[$driverID]['positions'];
            sort($points[$driverID]['sortedPositions']);
        }

        // Sort the drivers
        usort($points, [$this, 'pointsSort']);

        $points = \Positions::addToArray($points, [$this, 'arePointsEqual']);

        return $points;
    }

    /**
     * Sort overall points
     * @param mixed $a
     * @param mixed $b
     * @return int
     */
    protected function pointsSort($a, $b)
    {
        // First, total points
        if (!is_array($a['total'])) {
            if ($a['total'] != $b['total']) {
                return $b['total'] > $a['total'] ? 1 : -1;
            }
        } else {
            if ($a['total']['points'] != $b['total']['points']) {
                return $b['total']['points'] > $a['total']['points'] ? 1 : -1;
            }
        }

        // Then, best finishing positions; all the way down...
        for($i = 0; $i < max(count($a['sortedPositions']), count($b['sortedPositions'])); $i++) {
            // Check both have a position set
            if (isset($a['sortedPositions'][$i]) && isset($b['sortedPositions'][$i])) {
                // If they're different, compare them
                // If not, loop again
                if ($a['sortedPositions'][$i] != $b['sortedPositions'][$i]) {
                    return $a['sortedPositions'][$i] - $b['sortedPositions'][$i];
                }
            } elseif (isset($a['sortedPositions'][$i])) {
                // $a has less results; $b takes priority
                return -1;
            } elseif (isset($b['sortedPositions'][$i])) {
                // $b has less results; $a takes priority
                return 1;
            }
        }

        // There's nothing more we can do to separate these drivers!
        return 0;
    }

    /**
     * Check if points are equal
     * @param array $a
     * @param array $b
     * @return bool
     */
    public function arePointsEqual($a, $b)
    {
        return $a['total'] == $b['total']
            && $a['positions'] == $b['positions']
            && count($a['points']) == count($b['points']);
    }

}
