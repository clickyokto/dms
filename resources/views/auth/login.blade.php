@extends('layouts.login')

@section('content')



    <!-- Log In page -->
    <div class="container">
        <div class="row vh-100 ">
            <div class="col-12 align-self-center">
                <div class="auth-page">
                    <div class="card auth-card shadow-lg">
                        <div class="card-body">
                            <div class="px-3">
                                <div class="auth-logo-box">
                                    <a href="{{url('/')}}" class="logo logo-admin"><img
                                                src="{{asset('/images/logo.png')}}" height="55" alt="logo"
                                                class="auth-logo"></a>
                                </div><!--end auth-logo-box-->

                                <div class="text-center auth-logo-text">
                                    <h4 class="mt-0 mb-3 mt-5">CMK Enterprises</h4>
                                    <p class="text-muted mb-0">Sign in to continue.</p>
                                </div> <!--end auth-logo-text-->


                                <form class="form-horizontal auth-form my-4" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="form-group">


                                        <label for="email"
                                               class="@error('email') is-invalid @enderror">{{ __('E-Mail Address') }}</label>
                                        <div class="input-group mb-3">
                                              <span class="auth-form-icon">
                                                    <i class="dripicons-user"></i>
                                                </span>
                                            <input placeholder="Enter username" id="email" type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                                   autofocus>
                                        </div>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div><!--end form-group-->

                                    <div class="form-group">



                                        <label for="password">{{ __('Password') }}</label>
                                        <div class="input-group mb-3">
                                             <span class="auth-form-icon">
                                                    <i class="dripicons-lock"></i>
                                                </span>
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror" name="password" required
                                               autocomplete="current-password" placeholder="Enter password">
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                    </div><!--end form-group-->

                                    <div class="form-group row mt-4">
                                        <div class="col-sm-6">
                                            <div class="custom-control custom-switch switch-success">

                                                <input class="custom-control-input" type="checkbox" name="remember"
                                                       id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="custom-control-label text-muted" for="remember">
                                                    {{ __('Remember Me') }}
                                                </label>


                                            </div>
                                        </div><!--end col-->

                                    </div><!--end form-group-->

                                    <div class="form-group mb-0 row">
                                        <div class="col-12 mt-2">


                                            <button class="btn btn-gradient-primary btn-round btn-block waves-effect waves-light"
                                                    type="submit">Log In <i class="fas fa-sign-in-alt ml-1"></i>
                                            </button>
                                        </div><!--end col-->
                                    </div> <!--end form-group-->
                                </form><!--end form-->
                            </div><!--end /div-->

                        </div><!--end card-body-->
                    </div><!--end card-->
                </div><!--end auth-page-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
    <!-- End Log In page -->



@endsection
