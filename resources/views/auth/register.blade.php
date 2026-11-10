@extends('layouts.main')

@section('content')

<link rel="stylesheet" href="{{ asset('css/auth.css') }}">

<div class="register-wrapper">

    <div class="register-card">

        <h2>Đăng ký</h2>

        <form method="POST" action="/register">
            @csrf

            <!-- HỌ TÊN -->
            <input
                type="text"
                name="name"
                placeholder="Họ tên"
                value="{{ old('name') }}">
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- SỐ ĐIỆN THOẠI -->
            <input
                type="text"
                name="phone"
                placeholder="Số điện thoại"
                value="{{ old('phone') }}">
            @error('phone')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- NGÀY SINH -->
            <input
                type="date"
                name="NgaySinh"
                value="{{ old('NgaySinh') }}">
            @error('NgaySinh')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- ĐỊA CHỈ -->
            <input
                type="text"
                name="DiaChi"
                placeholder="Địa chỉ"
                value="{{ old('DiaChi') }}">
            @error('DiaChi')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- EMAIL -->
            <input
                type="email"
                name="email"
                placeholder="Email"
                value="{{ old('email') }}">
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- MẬT KHẨU -->
            <input
                type="password"
                name="password"
                placeholder="Mật khẩu">
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror

            <!-- NÚT ĐĂNG KÝ -->
            <button type="submit">
                Đăng ký
            </button>

        </form>

    </div>

</div>

@endsection