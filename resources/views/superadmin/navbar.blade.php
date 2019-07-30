<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" 
                href="{{ url('/superadmin') }}">
                {{ config('app.name', 'Fuel Station App') }}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="nav navbar-nav">
                
                <li><a href="{{ url('/superadmin') }}">Dashboard</a></li>
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Company<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('superadmin.company.index') }}">List</a></li>
                        <li><a href="{{ route('superadmin.company.create') }}">Create New</a></li>
                    </ul>
                </li>
                
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('users.signin') }}">Login</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false"><i class="fa fa-user" aria-hidden="true"></i> <?php echo Auth::user()->fullname;  ?> <span
                                class="caret"></span></a>
                        <ul class="dropdown-menu">
                            @if(Auth::check())
                                <li><a href="{{ route('users.profile') }}">Profile</a></li>
                                <li role="separator" class="divider"></li>
                                <li><a href="{{ route('users.logout') }}">Logout</a></li>
                            @else
                                <li><a href="{{ route('users.signup') }}">Signup</a></li>
                                <li><a href="{{ route('users.signin') }}">Signin</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>