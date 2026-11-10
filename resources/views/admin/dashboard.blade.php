<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css?family=Lobster&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=STIX+Two+Text:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>

<div class="container">

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="logo">
            FUDDI
        </div>

        <ul class="menu">

            <li>
                <a href="/admin/dashboard"
                   class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <img src="{{ asset('images/home_icon.png') }}">
                    Tổng quan
                </a>
            </li>

            <li>
                <a href="/admin/customer"
                   class="{{ request()->is('admin/customer') ? 'active' : '' }}">
                    <img src="{{ asset('images/formkit_icon.png') }}">
                    Khách hàng
                </a>
            </li>

            <li>
                <a href="/admin/product"
                   class="{{ request()->is('admin/product') ? 'active' : '' }}">
                    <img src="{{ asset('images/food_icon.png') }}">
                    Sản phẩm
                </a>
            </li>

            <li>
                <a href="/admin/category"
                   class="{{ request()->is('admin/category') ? 'active' : '' }}">
                    <img src="{{ asset('images/note_icon.png') }}">
                    Danh mục
                </a>
            </li>

            <li>
                <a href="/admin/order"
                   class="{{ request()->is('admin/order') ? 'active' : '' }}">
                    <img src="{{ asset('images/bag_icon.png') }}">
                    Đơn hàng
                </a>
            </li>

            <li>
                <a href="/admin/statistic"
                   class="{{ request()->is('admin/statistic') ? 'active' : '' }}">
                    <img src="{{ asset('images/frame_icon.png') }}">
                    Thống kê
                </a>
            </li>

        </ul>

        <div class="logout logout-text">

            <a href="{{ route('logout') }}"
               style="text-decoration:none;color:#FFA629;font-size:30px;font-weight:600;display:flex;align-items:center;gap:15px;">

                <img src="{{ asset('images/logout_icon.png') }}" width="32">

                Đăng xuất

            </a>

        </div>

    </div>

    <!-- CONTENT -->
    @yield('content')

</div>

</body>
</html>