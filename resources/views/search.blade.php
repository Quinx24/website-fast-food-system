@extends('layouts.main')

@section('content')

<div class="container">

    <h2>Kết quả tìm kiếm: "{{ $keyword }}"</h2>

    @if($products->count() > 0)

    <div class="row">

        @foreach($products as $sp)

        <div class="col-md-3">

            <a href="/product/{{ $sp->SanPhamID }}" style="text-decoration:none; color:black;">

                <div class="product-card text-center p-3">

                    <!-- ẢNH -->
                    <img src="{{ asset('images/' . $sp->DuongDanAnh) }}" class="product-img">

                    <!-- TÊN -->
                    <h5>{{ $sp->TenSanPham }}</h5>

                    <!-- GIÁ -->
                    <p>{{ number_format($sp->GiaSanPham, 0, ',', '.') }} VNĐ</p>

                </div>

            </a>

        </div>

        @endforeach

    </div>

    @else
    <p style="color:red;">Không tìm thấy sản phẩm</p>
    @endif

</div>

@endsection