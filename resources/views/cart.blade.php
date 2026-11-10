@extends('layouts.main')

@section('content')

<div class="container-fluid mt-4 px-4">

    <!-- TITLE -->
    <h3 style="color: #ffb433; font-family: Lobster; font-size:32px; margin-bottom:25px;">
        Giỏ hàng của bạn
    </h3>

    @if(count($items) == 0)
    <div class="text-center mt-4">
        <h4 style="font-weight:100;">Giỏ hàng trống</h4>
    </div>
    @endif

    @foreach($items as $item)

    <div class="p-3 mb-4 d-flex flex-row align-items-center justify-content-between"
        style="background:#fff5e4; border-radius:25px; box-shadow:0 4px 15px rgba(0,0,0,0.15);">

        <!-- LEFT -->
        <div class="d-flex align-items-center">

            <div style="width:120px; height:100px; display:flex; align-items:center;">
                <img src="{{ asset('images/' . $item->DuongDanAnh) }}"
                    style="max-width:100%; max-height:100%; object-fit:contain;">
            </div>

            <div class="ms-4">
                <h5 style="font-weight:600;">{{ $item->TenSanPham }}</h5>
                <p style="color:#555; margin-bottom:10px;">
                    Giá: {{ number_format($item->DonGia) }} VNĐ
                </p>

                <form action="/cart/update" method="POST" class="form-update" style="display:inline;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->GioHangChiTietID }}">

                    <div style="
                    border:2px solid #ffa629;
                    border-radius:25px;
                    padding:5px 18px;
                    display:inline-flex;
                    align-items:center;
                    gap:15px;
                    color:#ffa629;
                    font-weight:500;
                ">

                        <!-- GIẢM -->
                        <button type="button"
                            onclick="handleDecrease(this)"
                            style="background:none;border:none;color:#ffa629;font-size:16px;">
                            -
                        </button>

                        <!-- INPUT -->
                        <input type="number"
                            name="SoLuong"
                            value="{{ $item->SoLuong }}"
                            min="1"
                            max="10"
                            onchange="this.form.submit()"
                            style="
                                width:40px;
                                text-align:center;
                                border:none;
                                outline:none;
                                color:#ffa629;
                                background:transparent;
                        ">

                        <!-- TĂNG -->
                        <button type="button"
                            onclick="changeQty(this, 1)"
                            style="background:none;border:none;color:#ffa629;font-size:16px;">
                            +
                        </button>

                    </div>
                </form>
                <form action="/cart/delete" method="POST" class="form-delete" style="display:none;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $item->GioHangChiTietID }}">
                </form>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="text-end">

            <!-- DELETE -->
            <form action="/cart/delete" method="POST">
                @csrf
                <input type="hidden" name="id" value="{{ $item->GioHangChiTietID }}">
                <button class="btn mb-2"
                    style="background:#ffa629; color:white; border-radius:20px; padding:6px 18px; margin-right: 60px; ">
                    Xóa
                </button>
            </form>
            <p style="font-weight:600;">
                Thành tiền: {{ number_format($item->SoLuong * $item->DonGia) }} VNĐ
            </p>

        </div>

    </div>

    @endforeach

    <!-- BUTTON -->
    <div class="text-center mt-5">

        @if(count($items) == 0)

        <a href="/">
            <button class="btn"
                style="
                        background:#ffa629;
                        color:white;
                        border-radius:30px;
                        padding:15px 80px;
                        font-size:18px;
                        box-shadow:0 4px 10px rgba(0,0,0,0.2);
                    ">
                Quay lại trang chủ
            </button>
        </a>

        @else
        @php
        $tong = 0;
        @endphp

        @foreach($items as $item)
        @php
        $tong += $item->SoLuong * $item->DonGia;
        @endphp
        @endforeach

        @php
        $tong = $items->sum(function($item){
        return $item->SoLuong * $item->DonGia;
        });

        $tongSoLuong = $items->sum('SoLuong');
        @endphp
        <div class="text-end me-4 mb-4">

            <h5 style="margin-bottom:5px; color:#555;">
                Tổng số lượng: {{ $tongSoLuong }}
            </h5>

            <h4 style="font-weight:600;">
                Tổng hóa đơn: {{ number_format($tong) }} VNĐ
            </h4>

        </div>

        <a href="/checkout">
            <button class="btn"
                style="
            background:#ffa629;
            color:white;
            border-radius:30px;
            padding:15px 80px;
            font-size:18px;
            box-shadow:0 4px 10px rgba(0,0,0,0.2);
        ">
                Thanh Toán
            </button>
        </a>

        @endif

    </div>

</div>

@endsection

<script>
    function changeQty(btn, change) {
        let form = btn.closest('form');
        let input = form.querySelector('input[name="SoLuong"]');

        let value = parseInt(input.value) || 1;

        value += change;

        if (value < 1) value = 1;
        if (value > 10) value = 10;

        input.value = value;

        form.submit();
    }
</script>

<script>
    function handleDecrease(btn) {
        let wrapper = btn.closest('form'); // form update
        let input = wrapper.querySelector('input[name="SoLuong"]');
        let value = parseInt(input.value) || 1;

        if (value <= 1) {
            let confirmDelete = confirm("Bạn có muốn xóa món này khỏi giỏ hàng không?");

            if (confirmDelete) {
                let deleteForm = wrapper.parentElement.querySelector('.form-delete');
                deleteForm.submit();
            }
            return;
        }

        // giảm bình thường
        value--;
        input.value = value;
        wrapper.submit();
    }
</script>