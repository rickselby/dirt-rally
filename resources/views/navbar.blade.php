<!-- Static navbar -->
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">Conelanders</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Results <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($championships AS $championship)
                            <li>
                                <a href="{{ route('championship.show', $championship) }}">{{ $championship->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Standings <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($championships AS $championship)
                            <li>
                                <a href="{{ route('standings.championship', [$defaultPointsSystem, $championship]) }}">{{ $championship->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Nation Standings <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($championships AS $championship)
                            <li>
                                <a href="{{ route('nationstandings.championship', [$defaultPointsSystem, $championship]) }}">{{ $championship->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Total Time <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($championships AS $championship)
                            <li>
                                <a href="{{ route('times.championship', $championship) }}">{{ $championship->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @if (Auth::user() && Auth::user()->admin)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        Admin <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="{{ route('nation.index') }}">Manage Nations</a>
                        </li>
                        <li>
                            <a href="{{ route('points-system.index') }}">Points Systems</a>
                        </li>
                    </ul>
                </li>
            @endif
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li>
                @if (Auth::check())
                    <a href="{{route('logout')}}">Logout</a>
                @else
                    <form method="get" action="{{ route('login.google') }}">
                        <button class="btn btn-social btn-google navbar-btn btn-sm">
                            <span class="fa fa-google"></span> Sign in with Google
                        </button>
                    </form>
                @endif
                </li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</nav>