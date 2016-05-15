@extends('page')

@section('content')

    <h1>
        @include('nation.image', ['nation' => $driver->nation])
        {{ $driver->name }}
    </h1>

    <div>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#dirt-rally" aria-controls="home" role="tab" data-toggle="tab">
                    Dirt Rally
                </a>
            </li>
            <li role="presentation">
                <a href="#assetto-corsa" aria-controls="profile" role="tab" data-toggle="tab">
                    Assetto Corsa
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="dirt-rally">
                @include('dirt-rally.driver.show')
            </div>
            <div role="tabpanel" class="tab-pane" id="assetto-corsa">
                @include('assetto-corsa.driver.show')
            </div>
        </div>

    </div>

@endsection