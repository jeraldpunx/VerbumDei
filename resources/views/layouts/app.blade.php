<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- Styles -->
    <link href="{{ url('css/bootstrap.min.css') }}" rel="stylesheet" >
    <link href="{{ url('css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
    <link href="{{ url('css/style.css') }}" rel="stylesheet" >
    <link href="{{ url('css/myStyle.css') }}" rel="stylesheet" >
    
    @yield('style')
    

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
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
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        @if (Session::has('username') && Session::get('usertype') == 'MERCHANT')
                            <li><a href="{{ url('admin/event') }}">Events</a></li>
                            <li><a href="{{ url('admin/kyc') }}">Members</a></li>
                        @elseif (Session::has('username') && Session::get('usertype') == 'CLIENT')
                            <li><a href="{{ url('event') }}">Events</a></li>
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (!Session::has('username'))
                            <li><a href="{{ url('/login') }}">Login</a></li>
                            <li><a href="{{ url('/register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" style="text-transform: uppercase;" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Session::get('username') }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        @if(Session::get('usertype') == 'CLIENT')<a href="{{ url('profile') }}">Profile</a>@endif
                                        <a href="{{ url('/logout') }}">Logout</a>
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

    <footer>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="copyright">
                            © 2017, Verbum Dei, All rights reserved
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"> -->
                    <div class="pull-right">
                        <div class="design">
                             <a target="_blank" href="http://www.ixbase.net">iXBase Incorporated </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <!-- <script src="{{ url('js/app.js') }}"></scaript> -->
    <script src="{{ url('js/jquery-3.2.0.min.js') }}"></script>
    <script src="{{ url('js/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ url('js/myScript.js') }}"></script>
    <script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('js/dataTables.bootstrap.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            if(localStorage.getItem("communities") === null) {
                $.ajax({
                  type: 'GET',
                  url: 'http://52.74.115.167:703/index.php',
                  crossDomain: true,
                  data: {
                    mtmaccess_api: true, 
                    transaction: 20021
                  },
                  cache: false,
                  success: function(data) {
                    var data = JSON.parse(data);
                    if(data.success) {
                        localStorage.setItem('communities', JSON.stringify(data.result));
                    }
                  }
                });
            }
        });
    </script>
    @yield('script')
</body>
</html>
