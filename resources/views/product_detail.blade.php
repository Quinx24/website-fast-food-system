@extends('layouts.main')

@section('content')

<div class="container mt-5">
    <div class="detail-card">

        <div class="row align-items-center">

            <!-- ẢNH -->
            <div class="col-md-6 text-center">
                <img src="{{ asset('images/' . $product->DuongDanAnh) }}"
                     class="detail-img">
            </div>

            <!-- THÔNG TIN -->
            <div class="col-md-6 text-center">

                <!-- FORM MỞ Ở ĐÂY -->
                <form action="/gio-hang/them" method="POST">
                    @csrf
                    <input type="hidden" name="SanPhamID" value="{{ $product->SanPhamID }}">

                    <!-- TÊN -->
                    <h2 class="detail-title">
                        {{ $product->TenSanPham }}
                    </h2>

                    <!-- GIÁ -->
                    <h4 class="detail-price">
                        {{ number_format($product->GiaSanPham) }} VNĐ
                    </h4>

                    <!-- MÔ TẢ -->
                    <p class="detail-desc">
                        {{ $product->MoTa ?? 'Món ăn ngon, hấp dẫn với hương vị đặc trưng.' }}
                    </p>

                    <!-- BIẾN THỂ -->

                    <!-- SỐ LƯỢNG -->
                    <div class="quantity mt-3"
                        style="
                            border:2px solid #ffa629;
                            border-radius:25px;
                            padding:5px 18px;
                            display:inline-flex;
                            align-items:center;
                            gap:15px;
                            color:#ffa629;
                        ">

                        <!-- GIẢM -->
                        <button type="button"
                            onclick="changeQty(-1)"
                            style="background:none;border:none;color:#ffa629;">
                            -
                        </button>

                        <!-- INPUT -->
                        <input type="number"
                            id="qtyInput"
                            name="SoLuong"
                            value="1"
                            min="1"
                            max="10"
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
                            onclick="changeQty(1)"
                            style="background:none;border:none;color:#ffa629;">
                            +
                        </button>

                    </div>

                    <!-- BUTTON -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-add">Thêm</button>
                        <a href="/buy-now/{{ $product->SanPhamID }}"
                           class="btn btn-buy">
                            Mua ngay
                        </a>
                    </div>

                </form>

                <!-- MUA NGAY -->


            </div>

        </div>

    </div>
</div>

@endsection

<script>
let max = 10;

function tang() {
    let qty = document.getElementById('qty');
    let input = document.getElementById('qtyInput');

    let value = parseInt(qty.innerText);

    if (value < max) {
        value++;
        qty.innerText = value;
        input.value = value;
    }
}

function giam() {
    let qty = document.getElementById('qty');
    let input = document.getElementById('qtyInput');

    let value = parseInt(qty.innerText);

    if (value > 1) {
        value--;
        qty.innerText = value;
        input.value = value;
    }
}

function changeQty(change) {
    let input = document.getElementById('qtyInput');
    let value = parseInt(input.value) || 1;

    value += change;

    if (value < 1) value = 1;
    if (value > 10) value = 10;

    input.value = value;
}
</script>
