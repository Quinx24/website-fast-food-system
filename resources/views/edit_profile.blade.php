@extends('layouts.main')

@section('hideHeader', true)

@section('content')

<link rel="stylesheet" href="{{ asset('css/account.css') }}">

@if(session('success'))

<script>
    alert("{{ session('success') }}");
</script>

@endif


<div class="container pt-3 pb-5">

    <div class="mx-auto p-5 edit-card">

        <h1 class="edit-title">
            Chỉnh sửa thông tin
        </h1>


        <form method="POST" action="/profile/update">

            @csrf


            <!-- HỌ TÊN -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Họ tên
                </label>

                <input type="text"
                       name="hoten"
                       required
                       value="{{ old('hoten', $customer->HoTen) }}"
                       class="form-control edit-input">

                @error('hoten')

                    <div class="edit-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- SỐ ĐIỆN THOẠI -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Số điện thoại
                </label>

                <input type="text"
                       name="sdt"
                       required
                       value="{{ old('sdt', $customer->SoDienThoai) }}"
                       class="form-control edit-input">

                @error('sdt')

                    <div class="edit-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- NGÀY SINH -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Ngày sinh
                </label>

                <input type="date"
                       name="NgaySinh"
                       required
                       value="{{ old('NgaySinh', $customer->NgaySinh) }}"
                       class="form-control edit-input">

            </div>


            <!-- ĐỊA CHỈ -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Địa chỉ
                </label>

                <input type="text"
                       name="diachi"
                       required
                       value="{{ old('diachi', $customer->DiaChi) }}"
                       class="form-control edit-input">

                @error('diachi')

                    <div class="edit-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- EMAIL -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Email
                </label>

                <input type="email"
                       name="email"
                       required
                       value="{{ old('email', $customer->Email) }}"
                       class="form-control edit-input">

                @error('email')

                    <div class="edit-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


                            <!-- MẬT KHẨU -->
            <div class="mb-4">

                <label class="mb-2 edit-label">
                    Mật khẩu
                </label>

            <div class="password-box">

            <input type="password"
                    id="password"
                    name="password"
                    required
                    value="{{ old('password', $customer->MatKhau) }}"
                    class="form-control edit-input">

                <span class="toggle-password"
              onclick="togglePassword()">
            👁️
             </span>

            </div>

    @error('password')

        <div class="edit-error">
            {{ $message }}
        </div>

    @enderror

</div>
            

            <button type="submit" class="edit-btn">

                Lưu thay đổi

            </button>

        </form>

    </div>

</div>

<script src="{{ asset('js/account.js') }}"></script>
