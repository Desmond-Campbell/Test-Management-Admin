<?php

if ( ( $sso_id = get_user_id() ) !== false ) {

    $random = rand( 111111, 99999999 );
    $random = "$random." . dechex( crc32( $random ) );
    $r2 = rand( 700, 720 );
    $global_cookie_value = "$random.$sso_id.$r2";
    $skey = sha1(microtime().rand());

    if ( !arg( $_COOKIE, config( 'session.la_cookie' ) ) ) {

        setcookie( config( 'session.la_cookie' ), $skey, time() + ( 60 * 60 * 24 ), "/", "." . env( 'APP_DOMAIN' ) );

    }

}

setcookie( config( 'session.global_cookie' ), $global_cookie_value, time() + ( 60 * 60 * 24 ), "/", "." . env( 'APP_DOMAIN' ) );

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title') - {{ env('APP_NAME') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/img/icon.png">

    <link href="/css/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="/js/vendor/node_modules/angular-material/angular-material.css" rel="stylesheet">
    <link href="/css/vendor/bootstrap/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="/css/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">

    @yield('styles')

    <script src="/js/vendor/bootstrap/ie-emulation-modes-warning.js"></script>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body ng-app="testmy" ng-cloak>
    <div id="app">
        <nav class="navbar navbar-default navbar-inverse">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">{{___( "Toggle Navigation" )}}</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="http://www.{{ env('APP_DOMAIN') }}">
                        <img src="/img/st-slogan.png" style="height: 24px; margin-top: -2px !important" />
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">{{___( "Login" )}}</a></li>
                            <li><a href="{{ route('register') }}">{{___( "Register" )}}</a></li>
                        @else
                            <li><a href="/account">{{___( "Account" )}}</a></li>
                            <li><a href="/networks">{{___( "Networks" )}}</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            {{___( "Logout" )}}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/vendor/jquery-2.2.1.min.js"></script>
    <script src="/js/vendor/node_modules/angular/angular.js"></script>
    <script src="/js/vendor/node_modules/angular-aria/angular-aria.js"></script>
    <script src="/js/vendor/node_modules/angular-animate/angular-animate.js"></script>
    <script src="/js/vendor/node_modules/angular-material/angular-material.js"></script>
    <script src="/js/vendor/moment.js"></script>
    <script src="/js/vendor/angular-moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.0.0/lodash.min.js"></script>
    <script src="/js/vendor/angular-timezone-selector/angular-timezone-selector.js"></script>
    <script src="/js/app.js"></script>
    @yield('javascript')
    <script src="/js/vendor/bootstrap/bootstrap.min.js"></script>
</body>
</html>
