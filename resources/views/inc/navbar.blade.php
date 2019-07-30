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
            @if(empty(Session::get('fuelstapp.companylogo'))) 
                <a class="navbar-brand" 
                    href="{{ url('/dashboard') }}">
                    {{session('fuelstapp.companyname') }}
                </a>
            @else
                <a class="navbar-brand" 
                    href="{{ url('/dashboard') }}">
                    <img class="img-thumbnail" width="50" height="50" src="/storage/company_logos/{{session('fuelstapp.companylogo') }}" alt="{{session('fuelstapp.companyname') }}">
                </a>
            @endif
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                &nbsp;
            </ul>

            <ul class="nav navbar-nav">
                
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('txns.index') }}">Transactions</a></li>
                <li><a href="{{ route('txns.salessumm.index') }}">Sales Summary</a></li>
                @if(Auth::user()->usertype == 'admin')
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Administration <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('stations.index') }}">Stations</a></li>
                        <li><a href="{{ route('rates.index') }}">Rates</a></li>
                        <li><a href="{{ route('users.index') }}">Users</a></li>
                        <li><a href="{{ route('pumps.index') }}">Pumps</a></li>
                        <li><a href="{{ route('vehicles.index') }}">Vehicles</a></li>
                        <li><a href="{{ route('owners.index') }}">Owners</a></li>
                    </ul>
                </li>
                @endif
                <li>
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reports <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        @if(Auth::user()->usertype == 'stationadmin')
                        <li><a href="{{ route('readings.create') }}">Create End of Day Report</a></li>
                        @endif
                        <li><a href="{{ route('eodays.index') }}">Daily Report List</a></li>
                        <li><a href="{{ route('monthly.get') }}">Monthly Report List</a></li>
                        <li><a href="{{ route('loyalty.index') }}">Loyalty Program</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('dashboard.getstarted') }}">Get Started</a></li>
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