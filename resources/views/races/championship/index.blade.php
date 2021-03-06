@extends('page')

@section('content')

    <h2>Championships</h2>

    <p>
        <a class="btn btn-small btn-info" href="{{ route('races.category.championship.create', [$category]) }}">Add a new championship</a>
    </p>

    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>Name</th>
            <th>Events</th>
            <th>Entrants</th>
            <th>Complete?</th>
        </tr>
        </thead>
        <tbody>
        @forelse($championships AS $championship)
            <tr class="{{ $championship->isComplete() ? '' : 'info' }}">
                <td>
                    <a href="{{ route('races.category.championship.show', [$category, $championship]) }}">
                        {{ $championship->name }}
                    </a>
                </td>
                <td>{{ count($championship->events) }}</td>
                <td>{{ count($championship->entrants) }}</td>
                <td>{{ $championship->isComplete() ? 'Yes' : 'No' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No championships</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@endsection