@foreach($championships AS $championship)
    <li class="list-group-item">
        The
        <a href="{{ route('rallycross.standings.drivers', $championship) }}">{{ $championship->shortName }} Championship</a>
        is now complete.
    </li>
@endforeach
