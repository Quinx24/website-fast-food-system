@extends('layouts.main')

@section('content')
<div class="container my-5">
    <div class="mb-3">
        <a href="{{ route('orders.history.index') }}" class="btn btn-sm btn-danger fs-5">&larr; Quay lại danh sách</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header p-3" style="background-color: #ffb433 ; color: #212529; font-family: 'Lobster', sans-serif;">
            <h3 class="mb-0 fw-bold text-center">Chi Tiết Đơn Hàng #{{ $donHang->DonHangID }}</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 fs-5">
                    <p><strong>Ngày đặt:</strong> {{ date('d/m/Y H:i', strtotime($donHang->NgayDat)) }}</p>
                    <p><strong>Địa chỉ giao hàng:</strong> {{ $donHang->DiaChiGiaoHang }}</p>
                </div>
                <div class="col-md-6 fs-5">
                    <p><strong>Ghi chú:</strong> {{ $donHang->GhiChu ?? 'Không có' }}</p>
                    <p><strong>Tổng tiền thanh toán:</strong> <span class="text-danger fw-bold">{{ number_format($donHang->TongTien, 0, ',', '.') }} đ</span></p>
                </div>
            </div>
        </div>
    </div>

    <div style="margin-top: 60px;"></div>

</div>

<div class="container my-4">
    <div class="card shadow-sm border-0">

        <div class="card-header p-3" style="background-color: #ffb433 ; color: #212529; font-family: 'Lobster', sans-serif;">
            <h3 class="mb-0 fw-bold text-center">Danh Sách Món Ăn</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #ffb433; color: #212529;">
                        <tr>
                            <th class="ps-4">Tên món</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Đơn giá</th>
                            <th class="text-end pe-4">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $sum = 0;
                        @endphp

                        @foreach($items as $item)
                        @php
                        $sum += $item->SoLuong * $item->DonGia;
                        @endphp
                        <tr>
                            <td class="fw-bold ps-4">{{ $item->TenSanPham }}</td>
                            <td class="text-center">{{ $item->SoLuong }}</td>
                            <td class="text-end">{{ number_format($item->DonGia, 0, ',', '.') }} đ</td>
                            <td class="text-end text-danger fw-bold pe-4">
                                {{ number_format($item->SoLuong * $item->DonGia, 0, ',', '.') }} đ
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center px-2">
                <span class="fw-bold text-secondary">Phí ship:</span>
                <span class="fw-bold text-danger">
                    {{ number_format($donHang->TongTien - $sum, 0, ',', '.') }} đ
                </span>
            </div>
        </div>

        <div class="card-footer bg-light p-3">
            <div class="d-flex justify-content-between align-items-center px-2">
                <span class="fw-bold text-secondary fs-5">Tổng cộng:</span>
                <span class="fs-4 fw-bold text-danger">
                    {{ number_format($donHang->TongTien, 0, ',', '.') }} đ
                </span>
            </div>
        </div>

    </div>
</div>

@endsection