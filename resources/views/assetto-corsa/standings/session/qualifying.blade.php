<h3>Results</h3>
@include('assetto-corsa.standings.session.lap-table', ['lapTimes' => \ACResults::fastestLaps($session)] )
