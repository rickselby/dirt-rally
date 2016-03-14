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
            <a class="navbar-brand" href="{{ route('season.index') }}">Conelanders</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
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
