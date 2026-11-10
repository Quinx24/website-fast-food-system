@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h1 class="mb-4 fw-bold text-center" style="font-family: 'Lobster', sans-serif; color: #ffb433;">Đơn Hàng Của Bạn</h1>

    @if($donHangs->isEmpty())
    <div class="alert alert-info text-center">Bạn chưa có đơn hàng nào cả.</div>
    @else
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr style="background-color: #ffb433; color: #212529;">
                        <th class="ps-3" style="background-color: inherit; color: inherit">Đơn Hàng</th>
                        <th style="background-color: inherit; color: inherit">Ngày Đặt</th>
                        <th style="background-color: inherit; color: inherit">Tổng Tiền</th>
                        <th class="text-center" style="background-color: inherit; color: inherit">Trạng Thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($donHangs as $dh)
                    <tr data-href="{{ route('orders.tracking.show', $dh->DonHangID) }}"
                        onclick="window.location = this.dataset.href;"
                        style="cursor: pointer;">
                        <td class="fw-bold ps-3">#{{ $dh->DonHangID }}</td>
                        <td>{{ date('d/m/Y H:i', strtotime($dh->NgayDat)) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($dh->TongTien, 0, ',', '.') }} đ</td>
                        <td class="text-center">
                            @if($dh->TrangThai == 'Chờ xác nhận')
                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                            @elseif($dh->TrangThai == 'Đang chuẩn bị')
                            <span class="badge bg-info text-dark">Đang chuẩn bị</span>
                            @elseif($dh->TrangThai == 'Đang giao')
                            <span class="badge bg-primary">Đang giao</span>
                            @else
                            <span class="badge bg-success">Đã giao thành công</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection