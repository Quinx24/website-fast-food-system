@extends('layouts.main')

@section('content')

@if(session('success'))

<script>

    alert("{{ session('success') }}");

</script>

@endif


<!-- BANNER -->
<div class="text-center">
    <img src="{{ asset('images/banner.png') }}" class="banner-img">
</div>


<!-- MENU -->
<div id="menu-section" class="text-center mt-3 menu">

    <a href="/?category=all#menu-section">
        <span class="{{ ($category == 'all' || !$category) ? 'active' : '' }}">
            Tất cả
        </span>
    </a>

    @foreach($categories as $cate)

        <a href="/?category={{ $cate->DanhMucSanPhamID }}#menu-section">

            <span class="{{ $category == $cate->DanhMucSanPhamID ? 'active' : '' }}">

                {{ $cate->TenDanhMuc }}

            </span>

        </a>

    @endforeach

</div>


<!-- PRODUCT -->
<div class="container-fluid mt-4 px-4">

    <div class="row g-4">

        @foreach($products as $product)

        <div class="col-md-4 d-flex justify-content-center">

            <div class="card product-card text-center p-3 shadow">

                <!-- ẢNH -->
                <a href="/product/{{ $product->SanPhamID }}">

                    <img src="{{ asset('images/' . $product->DuongDanAnh) }}"
                         class="product-img">

                </a>

                <!-- TÊN -->
                <a href="/product/{{ $product->SanPhamID }}"
                   style="
                        text-decoration:none;
                        color:black;
                   ">

                    <h5 class="mt-2">

                        {{ $product->TenSanPham }}

                    </h5>

                </a>

                <!-- GIÁ -->
                <p>

                    {{ number_format($product->GiaSanPham) }} VNĐ

                </p>

            </div>

        </div>

        @endforeach

    </div>

</div>

@endsection