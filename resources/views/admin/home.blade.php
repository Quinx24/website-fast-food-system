@extends('admin.dashboard')

@section('content')

<div class="content">

    <div class="search-box">

        <input type="text" placeholder="Tìm kiếm">

        <img src="{{ asset('images/search_icon.png') }}">

    </div>

    <div class="cards">

        <div class="card">

            <div class="card-inner">

                <div class="number">
                    {{ $tongKhachHang }}
                </div>

                <img src="{{ asset('images/avatar_icon.png') }}">

                <div class="card-title">
                    Khách hàng
                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-inner">

            <div class="number">
                {{ $tongSanPham }}
            </div>

                <img src="{{ asset('images/image8.png') }}">

                <div class="card-title">
                    Sản phẩm
                </div>

            </div>

        </div>

        <div class="card">

            <div class="card-inner">

            <div class="number">
                {{ number_format($tongDoanhThu, 0, ',', '.') }}
            </div>

                <img src="{{ asset('images/money_icon.png') }}">

                <div class="card-title">
                    Doanh thu
                </div>

            </div>

        </div>

    </div>

</div>

@endsection