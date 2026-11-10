@extends('layouts.main')

@section('content')

<link rel="stylesheet" href="{{ asset('css/account.css') }}">

@if(session('success'))

<script>
    alert("{{ session('success') }}");
</script>

@endif


<div class="container-fluid px-5 py-5">

    <div class="row">

        <!-- LEFT MENU -->
        <div class="col-md-4">

            <div class="profile-menu">

                <a href="/profile/edit" class="profile-link">

                    <div class="profile-btn">
                        Chỉnh sửa thông tin
                    </div>

                </a>


                <a href="{{ route('orders.history.index') }}" class="profile-link">

                    <div class="profile-btn">
                        Lịch sử mua hàng
                    </div>

                </a>


                <a href="{{ route('orders.tracking.index') }}" class="profile-link">

                    <div class="profile-btn">
                        Theo dõi đơn hàng
                    </div>

                </a>


                <a href="/logout" class="profile-link">

                    <div class="profile-btn">
                        Đăng xuất
                    </div>

                </a>

            </div>

        </div>


        <!-- RIGHT CONTENT -->
        <div class="col-md-8 p-4 rounded-3">

            <h1 class="profile-title">
                TÀI KHOẢN CỦA BẠN
            </h1>

            <hr class="profile-line">


            <div class="profile-info">

                <div>

                    <strong>Họ tên:</strong>

                    <span class="profile-value">
                        {{ $customer->HoTen }}
                    </span>

                </div>


                <div>

                    <strong>Gmail:</strong>

                    <span class="profile-value">
                        {{ $customer->Email }}
                    </span>

                </div>


                <div>

                    <strong>Số điện thoại:</strong>

                    <span class="profile-value">
                        {{ $customer->SoDienThoai }}
                    </span>

                </div>


                <div>

                    <strong>Ngày sinh:</strong>

                    <span class="profile-value">
                        {{ $customer->NgaySinh }}
                    </span>

                </div>


                <div>

                    <strong>Địa chỉ:</strong>

                    <span class="profile-value">
                        {{ $customer->DiaChi }}
                    </span>

                </div>


                <div>

                    <strong>Mật khẩu:</strong>

                    <span class="profile-value">
                        ********
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection