@extends('layouts.main')

@section('content')

<div class="container-fluid mt-4 px-4">

    <h3 style="
        color:#ffb433;
        font-family:Lobster;
        font-size:32px;
        margin-bottom:25px;
    ">
        Thanh toán
    </h3>

    <div class="mx-auto p-4"
        style="
            max-width:850px;
            background:#fff5e4;
            border-radius:25px;
            box-shadow:0 4px 15px rgba(0,0,0,0.15);
         ">

        @foreach($items as $item)

        <div class="d-flex justify-content-between align-items-center py-4"
            style="border-bottom:1px solid #f2c57c;">

            <div class="d-flex align-items-center">

                <div style="
                    width:120px;
                    height:100px;
                    display:flex;
                    align-items:center;
                ">

                    <img src="{{ asset('images/' . $item->DuongDanAnh) }}"
                        style="
                            max-width:100%;
                            max-height:100%;
                            object-fit:contain;
                         ">

                </div>

                <div class="ms-4">

                    <h4 style="font-weight:600;">
                        {{ $item->TenSanPham }}
                    </h4>

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
                        {{ $item->SoLuong }}
                    </div>

                </div>

            </div>

            <h5 style="font-weight:500;">
                {{ number_format($item->SoLuong * $item->Gia) }} VNĐ
            </h5>

        </div>

        @endforeach


        @php

        $tong = $items->sum(function($item){

        return $item->SoLuong * $item->Gia;

        });

        @endphp


        <form method="POST" action="/checkout">

            @csrf
            @if(session('buy_now'))

            <input type="hidden"
                name="ChiTietSanPhamID"
                value="{{ $items[0]->ChiTietSanPhamID }}">

            <input type="hidden"
                name="Gia"
                value="{{ $items[0]->Gia }}">

            @endif

            

            <div class="mt-5">

                <h4 style="
                    font-weight:600;
                    margin-bottom:30px;
                ">
                    Thông tin khách hàng
                </h4>


                <div class="row mb-4 align-items-center">

                    <div class="col-3">
                        <label>Họ tên:</label>
                    </div>

                    <div class="col-9">

                        <input type="text"
                            name="hoten"
                            readonly
                            required
                            value="{{ $khachHang->HoTen }}"
                            class="form-control"
                            style="
                                    background:#f8e7c5;
                                    border:none;
                                    border-radius:20px;
                                    padding:12px 20px;
                               ">

                    </div>

                </div>


                <div class="row mb-4 align-items-center">

                    <div class="col-3">
                        <label>Địa chỉ:</label>
                    </div>

                    <div class="col-9">

                        <input type="text"
                            name="diachi"
                            id="diachi"
                            required
                            onkeyup="tinhShip()"
                            placeholder="Vui lòng nhập địa chỉ giao hàng"
                            class="form-control"
                            style="
                                    background:#f8e7c5;
                                    border:none;
                                    border-radius:20px;
                                    padding:12px 20px;
                               ">

                    </div>

                </div>


                <div class="row mb-4 align-items-center">

                    <div class="col-3">
                        <label>Số điện thoại:</label>
                    </div>

                    <div class="col-9">

                        <input type="text"
                            name="sdt"
                            readonly
                            required
                            value="{{ $khachHang->SoDienThoai }}"
                            class="form-control"
                            style="
                                    background:#f8e7c5;
                                    border:none;
                                    border-radius:20px;
                                    padding:12px 20px;
                               ">

                    </div>

                </div>


                <div class="mt-5">

                    <h4 style="
                        font-weight:600;
                        margin-bottom:25px;
                    ">
                        Phương thức thanh toán
                    </h4>


                    <div class="d-flex gap-5">

                        <label>

                            <input type="radio"
                                name="thanhtoan"
                                value="Tiền mặt"
                                checked
                                onclick="hideBank()">

                            Thanh toán bằng tiền mặt

                        </label>


                        <label>

                            <input type="radio"
                                name="thanhtoan"
                                value="Chuyển khoản"
                                onclick="showBank()">

                            Thanh toán bằng chuyển khoản

                        </label>

                    </div>


                    <div id="bankBox"
                        style="
                            display:none;
                            margin-top:25px;
                         ">

                        <select name="nganhang"
                            class="form-control"
                            onchange="showQR()"
                            style="
                                    background:#f8e7c5;
                                    border:none;
                                    border-radius:20px;
                                    padding:12px 20px;
                                ">

                            <option value="">
                                -- Chọn ngân hàng --
                            </option>

                            <option value="MB Bank">
                                MB Bank
                            </option>

                            <option value="Vietcombank">
                                Vietcombank
                            </option>

                            <option value="Techcombank">
                                Techcombank
                            </option>

                            <option value="ACB">
                                ACB
                            </option>

                            <option value="BIDV">
                                BIDV
                            </option>

                            <option value="TPBank">
                                TPBank
                            </option>

                            <option value="Sacombank">
                                Sacombank
                            </option>

                        </select>


                        <div id="qrBox"
                            style="
                                display:none;
                                text-align:center;
                                margin-top:25px;
                             ">

                            <img src="{{ asset('images/qr.png') }}"
                                style="
                                    width:250px;
                                    border-radius:20px;
                                 ">

                            <p style="
                                margin-top:15px;
                                font-weight:bold;
                                color:#ff9900;
                            ">
                                Nội dung CK: FASTFOOD
                            </p>


                            <input type="text"
                                name="magiaodich"
                                placeholder="Nhập mã giao dịch sau khi chuyển khoản"
                                class="form-control mt-3"
                                style="
                                        background:#f8e7c5;
                                        border:none;
                                        border-radius:20px;
                                        padding:12px 20px;
                                   ">

                        </div>

                    </div>

                </div>


                <div class="mt-4">

                    <textarea name="ghichu"
                        rows="5"
                        placeholder="Ghi chú..."
                        class="form-control"
                        style="
                                background:#f1dfbb;
                                border:none;
                                border-radius:25px;
                                resize:none;
                                padding:20px;
                              "></textarea>

                </div>


                <div class="text-end mt-4">

                    <h5 id="phiShip"
                        style="
                            margin-bottom:10px;
                            color:#666;
                        ">

                        Phí ship: 0 VNĐ

                    </h5>

                    <h2 id="tongTien"
                        style="
                            color:#ffa629;
                            font-weight:700;
                        ">

                        Tổng: {{ number_format($tong) }} VNĐ

                    </h2>

                </div>


                <div class="text-center mt-4">

                    <button type="submit"
                        class="btn"
                        style="
                                background:#ffa629;
                                color:white;
                                border-radius:35px;
                                padding:15px 120px;
                                font-size:22px;
                                font-family:Lobster;
                                box-shadow:0 4px 10px rgba(0,0,0,0.2);
                            ">

                        Đặt hàng

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<script>

    let tongGoc = Number('{{ $tong }}');

    function tinhShip() {

        let diachi = document
            .getElementById('diachi')
            .value
            .toLowerCase();

        diachi = diachi.normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");

        diachi = diachi.replace(/\s+/g, ' ').trim();

        let ship = 35000;

        let freeShip = [
            'quan 1',
            'quan 3',
            'quan 5',
            'quan 10',
            'quan 8'
        ];

        for (let i = 0; i < freeShip.length; i++) {

            let regex = new RegExp('\\b' + freeShip[i] + '\\b');

            if (regex.test(diachi)) {

                ship = 0;
                break;

            }

        }

        document.getElementById('phiShip').innerHTML =
            "Phí ship: " + ship.toLocaleString() + " VNĐ";

        document.getElementById('tongTien').innerHTML =
            "Tổng: " + (tongGoc + ship).toLocaleString() + " VNĐ";

    }

    tinhShip();

</script>

@endsection