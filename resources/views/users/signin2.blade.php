<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Fuel Station App') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/footer.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

    <div class="jumbotron text-center">
      <div class="container">
        <h1>Fuel Station App</h1>
      </div>
    </div>

    <div class="container">
        @include('inc.messages')
    </div>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <h1>Sign In</h1>
            <form action="{{ route('users.signin') }}" method="post">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-control">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">Sign In</button>
                {!! csrf_field() !!}
            </form>
            
        </div>
    </div>


</div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <footer id="footer">
        Developed by Avanet Technologies 2017    
    </footer>
</body>
</html>