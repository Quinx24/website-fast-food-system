@extends('layouts.main')

@section('content')

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="register-wrapper">

    <div class="register-card">

        <h2>Đăng nhập</h2>

        <!-- THÔNG BÁO -->
        @if(session('success'))
            <p style="color: green; text-align:center;">
                {{ session('success') }}
            </p>
        @endif

        @if(session('error'))
            <p class="error" style="text-align:center;">
                {{ session('error') }}
            </p>
        @endif

        <!-- FORM -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">

            <!-- PASSWORD -->
            <input type="password" name="password" placeholder="Mật khẩu">

            <button type="submit">Đăng nhập</button>

            <!-- LINK -->
            <p class="auth-links">
                <a href="#">Quên mật khẩu?</a> 
                hoặc 
                <a href="{{ route('register') }}">Đăng ký</a>
            </p>

        </form>

    </div>

</div>

@endsection