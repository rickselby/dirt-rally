@if (count($racesResults[$category->id]['best']['practice']))
    <div class="panel-heading">
        <h4 class="panel-title">
            <span class="position pull-right">{{ $racesResults[$category->id]['best']['practice']['best'] }}</span>
            Practice
            <span class="text-muted">
                @if (count($racesResults[$category->id]['best']['practice']['things']) == 1)
                    ({{ $racesResults[$category->id]['best']['practice']['things']->first()->fullName }})
                @else
                    <a role="button" data-toggle="collapse" href="#races-best-practices">
                        ({{ count($racesResults[$category->id]['best']['practice']['things']) }} times)
                    </a> <span class="caret"></span>
                @endif
            </span>
        </h4>
    </div>
    @if (count($racesResults[$category->id]['best']['practice']['things']) >= 2)
        <div id="races-best-practices" class="panel-collapse collapse" role="tabpanel">
            <ul class="list-group">
                @foreach($racesResults[$category->id]['best']['practice']['things'] AS $result)
                    <li class="list-group-item">{{ $result->fullName }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endif
