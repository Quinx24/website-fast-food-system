@extends('layouts.main')

@section('content')
<div class="container my-5">
    <div class="mb-3">
        <a href="{{ route('orders.tracking.index') }}" class="btn btn-sm btn-danger fs-5">&larr; Quay lại danh sách</a>
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
                    <p><strong>Ghi chú:</strong> {{ $donHang->GhiChu ?? 'Không có' }}</p>

                </div>
                <div class="col-md-6 fs-5">
                    <p><strong>Tổng tiền thanh toán:</strong> <span class="text-danger fw-bold">{{ number_format($donHang->TongTien, 0, ',', '.') }} đ</span></p>
                    <p><strong>Hình thức thanh toán:</strong> {{ $thongTinThanhToan->PhuongThuc }}</p>
                    @if($thongTinThanhToan->TrangThai == 'Đã thanh toán')
                    <p>
                        <strong>Ngày thanh toán:</strong>
                        {{ date('d/m/Y H:i', strtotime($thongTinThanhToan->NgayThanhToan)) }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @if($donHang->TrangThai == 'Đã hủy')

    <div class="alert alert-danger text-center fw-bold fs-4">
        ❌ Đơn hàng đã bị hủy
    </div>

    @else

    <div class="card shadow-sm p-4">
        <h3 class="fw-bold text-center mb-4" style="font-family: 'Lobster', sans-serif;">
            <strong>Trạng Thái Vận Chuyển</strong>
        </h3>

        @php
        $status = $donHang->TrangThai;

        $step1 = true;
        // $step2 = ($status == 'Đang chuẩn bị' || $status == 'Đang giao' || $status == 'Đã giao');
        // $step3 = ($status == 'Đang giao' || $status == 'Đã giao');
        // $step4 = ($status == 'Đã giao');

        $step2 = in_array($status, [
            'Đang chuẩn bị',
            'Đang giao',
            'Hoàn thành'
        ]);

        $step3 = in_array($status, [
            'Đang giao',
            'Hoàn thành'
        ]);

        $step4 = ($status == 'Hoàn thành');

        $styleWidth = $step4 ? 'style="width: 100%"' : ($step3 ? 'style="width: 66%"' : ($step2 ? 'style="width: 33%"' : 'style="width: 0%"'));
        @endphp

        <div class="position-relative m-5" style="height: 40px;">

            <div class="progress" style="height: 6px; position: absolute; top:20%; left: 0; right: 0; transform: translateY(-50%); z-index: 1;">
                <div class="progress-bar bg-danger" role="progressbar" {!! $styleWidth !!}></div>
            </div>

            <div class="position-absolute start-0 translate-middle text-center" style="top: 50%; z-index: 2; width: 120px;">
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center mx-auto fw-bold" style="width: 40px; height: 40px; border: 3px solid #fff;">1</div>
                <div class="mt-2 fw-bold small text-nowrap">Chờ xác nhận</div>
            </div>

            <div class="position-absolute start-33 translate-middle text-center" style="top: 50%; left: 33%; z-index: 2; width: 120px;">
                <div class="rounded-circle {{ $step2 ? 'bg-danger text-white' : 'bg-secondary text-light' }} d-flex align-items-center justify-content-center mx-auto fw-bold" style="width: 40px; height: 40px; border: 3px solid #fff;">2</div>
                <div class="mt-2 fw-bold small text-nowrap">Đang chuẩn bị</div>
            </div>

            <div class="position-absolute start-66 translate-middle text-center" style="top: 50%; left: 66%; z-index: 2; width: 120px;">
                <div class="rounded-circle {{ $step3 ? 'bg-danger text-white' : 'bg-secondary text-light' }} d-flex align-items-center justify-content-center mx-auto fw-bold" style="width: 40px; height: 40px; border: 3px solid #fff;">3</div>
                <div class="mt-2 fw-bold small text-nowrap">Đang giao</div>
            </div>

            <div class="position-absolute start-100 translate-middle text-center" style="top: 50%; z-index: 2; width: 120px;">
                <div class="rounded-circle {{ $step4 ? 'bg-danger text-white' : 'bg-secondary text-light' }} d-flex align-items-center justify-content-center mx-auto fw-bold" style="width: 40px; height: 40px; border: 3px solid #fff;">4</div>
                <div class="mt-2 fw-bold small text-nowrap">
                    Hoàn thành
                </div>
            </div>
        </div>
    </div>
    @endif
    <div style="margin-top: 60px;"></div>
</div>
@endsection