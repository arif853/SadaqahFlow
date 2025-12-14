<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <title>{{config('app.name')}} | Login
    </title>

        <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#000000"/>
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="CPDS">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">
    <link rel="apple-touch-icon" href="{{asset('assets/images/logo-2.png')}}">

    <!-- shortcut icon-->
    <link rel="icon" href="{{asset('')}}assets/images/logo-2.png" type="image/x-icon">
    <link rel="shortcut icon" href="{{asset('')}}assets/images/logo-2.png" type="image/x-icon">
    <!-- Fonts css-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&amp;display=swap"
        rel="stylesheet">
    <!-- Font awesome -->
    <link href="{{asset('')}}assets/css/vendor/font-awesome.css" rel="stylesheet">
    <!-- themify icon-->
    <link href="{{asset('')}}assets/css/vendor/themify-icons.css" rel="stylesheet">
    <!-- Scrollbar-->
    <link href="{{asset('')}}assets/css/vendor/simplebar.css" rel="stylesheet">
    <!-- Bootstrap css-->
    <link href="{{asset('')}}assets/css/vendor/bootstrap.css" rel="stylesheet">
    <!-- Custom css-->
    <link href="{{asset('')}}assets/css/style.css" id="customstyle" rel="stylesheet">
</head>

<body>
    <!-- Login Start-->
    <div class="auth-main">
        <div class="codex-authbox">
            <div class="auth-header">
                <div class="codex-brand">
                    <a href="#"><img class="img-fluid light-logo"
                            src="{{asset('')}}assets/images/logo-2.png" alt="" style="width: 40%; height: auto; ">
                        <img class="img-fluid dark-logo" src="{{asset('')}}assets/images/logo-2.png" alt="" style="width: 40%; height: auto;">
                    </a>
                </div>
                <x-auth-session-status class="mb-4" :status="session('status')" />
                <h3>welcome to {{config('app.name')}}</h3>
                {{-- <h6>don't have an account? <a class="text-primary" href="register.html">creat an account</a></h6> --}}
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">User Name</label>
                    <input class="form-control" type="text" name="username" value="{{old('username')}}" required autofocus
                    autocomplete="username" placeholder="Enter Your Username">
                    @error('username')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="Password">Password</label>
                    <div class="input-group group-input">
                        <input class="form-control showhide-password" type="password" id="Password"
                            placeholder="Enter Your Password" required="" name="password">
                        <span class="input-group-text toggle-show fa fa-eye"></span>
                    </div>
                    @error('password')
                    <span class="text-danger">{{$message}}</span>
                    @enderror
                </div>
                <div class="form-group mb-0">
                    <div class="auth-remember">
                        <div class="form-check custom-chek">
                            <input class="form-check-input" id="agree" type="checkbox" name="remember" required="" checked>
                            <label class="form-check-label" for="agree">Remember me</label>
                        </div>
                        {{-- <a class="text-primary f-pwd" href="forgot-password.html">Forgot your password?</a> --}}
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-sign-in"></i> Login</button>
                </div>
            </form>
            {{-- <div class="auth-footer">
                <h5 class="auth-with">Or login in with </h5>
                <ul class="login-list">
                    <li><a class="bg-fb" href="javascript:void(0);"> <img class="img-fluid"
                                src="{{asset('')}}assets/images/auth/1.png" alt="facebook">facebook</a></li>
                    <li><a class="bg-google" href="javascript:void(0);"> <img class="img-fluid"
                                src="{{asset('')}}assets/images/auth/2.png" alt="google">google</a></li>
                </ul>
            </div> --}}
        </div>
    </div>
    <!-- Login End-->
    <!-- main jquery-->
    <script src="{{asset('')}}assets/js/jquery-3.6.0.js"></script>
    <!-- Theme Customizer-->
    <script src="{{asset('')}}assets/js/layout-storage.js"></script>
    <!-- Feather icons js-->
    <script src="{{asset('')}}assets/js/icons/feather-icon/feather.js"></script>
    <!-- Bootstrap js-->
    <script src="{{asset('')}}assets/js/bootstrap.bundle.min.js"></script>
    <!-- Scrollbar-->
    <script src="{{asset('')}}assets/js/vendors/simplebar.js"></script>
    <!-- Custom script-->
    <script src="{{asset('')}}assets/js/custom-script.js"></script>
</body>

</html>
