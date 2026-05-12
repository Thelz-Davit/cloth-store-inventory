@extends('layouts.auth')

@section('content')
    <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo" style="padding-bottom: 50px !important; margin-bottom: 0px !important;">
                    <a href="#">
                        {{-- <img src="{{ asset('images/logo.webp') }}" alt="logo" style="width: 250px; height: 70px;"> --}}
                    </a>
                </div>
                <h1 class="auth-title">30sclothing</h1>
                <p class="auth-subtitle mb-5">Please enter your details</p>

                <form method="POST" action="{{ route('login.post') }}" data-use-loader="true">
                    @csrf

                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="text" name="username"
                            class="form-control form-control-xl @error('username') is-invalid @enderror" placeholder="Username"
                            autocomplete="off" readonly onfocus="this.removeAttribute('readonly')"
                            value="{{ old('username') }}">
                        <div class="form-control-icon">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group position-relative has-icon-left mb-3">
                        <input type="password" name="password"
                            class="form-control form-control-xl @error('password') is-invalid @enderror"
                            placeholder="Password" autocomplete="current-password" readonly
                            onfocus="this.removeAttribute('readonly')">
                        <div class="form-control-icon">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- <div class="form-check form-check-lg d-flex align-items-end">
                        <input class="form-check-input me-2" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label text-gray-600" for="remember">
                            Keep me logged in
                        </label>
                    </div> --}}

                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                        Log in
                    </button>
                </form>

                {{-- <div class="text-center mt-5 text-lg fs-4">
                    <p class="text-gray-600">Don't have an account? <a href="auth-register.html" class="font-bold">Sign
                            up</a>.</p>
                    <p><a class="font-bold" href="auth-forgot-password.html">Forgot password?</a>.</p>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div>
@endsection
