@extends('layouts.auth')

@section('content')
    {{-- <div class="row h-100">
        <div class="col-lg-5 col-12">
            <div id="auth-left">
                <div class="auth-logo" style="padding-bottom: 50px !important; margin-bottom: 0px !important;">
                    <a href="#">
                        <img src="{{ asset('images/logo.webp') }}" alt="logo" style="width: 250px; height: 70px;">
                    </a>
                </div>
                <h1 class="auth-title">30SCLOTHING</h1>
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
                    <button class="btn btn-primary btn-block btn-lg shadow-lg mt-5">
                        Log in
                    </button>
                </form>
            </div>
        </div>
        <div class="col-lg-7 d-none d-lg-block">
            <div id="auth-right">

            </div>
        </div>
    </div> --}}
    <div class="container-fluid vh-100">
        <div class="row h-100 align-items-center">

            <!-- Logo -->
            <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                <img src="{{ asset('mazer\dist\assets\static\images\faces\logo.png') }}" class="img-fluid"
                    style="max-width:420px">
            </div>

            <!-- Login -->
            <div class="col-lg-6 d-flex justify-content-center align-items-center">
                <div class="card shadow border-0 rounded-4 p-4" style="width:430px">

                    <div class="text-center mb-4">
                        <h2 class="fw-bold">
                        Welcome Back</h2>
                        <p class="text-secondary">


                            Please sign in to your account.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username</label>

                            <input class="form-control form-control-lg" name="username"
                                placeholder="Enter Your Username">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>

                            <input type="password" class="form-control form-control-lg" name="password"
                                placeholder="Enter Password">
                        </div>

                        <button class="btn btn-outline-dark w-100 btn-lg">
                            Sign In
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
