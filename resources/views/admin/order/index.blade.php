@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order_statistic.css') }}">

<div class="content">
    <div class="category-header-bar">
        <h1 class="customer-title">Quản lý Đơn hàng</h1>
    </div>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    <div class="table-box">
        <table class="customer-table category-custom-table">
            <thead>
                <tr>
                    <th class="col-id">Mã ĐH</th>
                    <th>Khách hàng</th>
                    <th>Ngày đặt</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th class="col-action">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if(count($donHangs) > 0)
                    @foreach($donHangs as $item)
                    <tr>
                        <td class="col-id">{{ $item->DonHangID }}</td>
                        <td>
                            <strong>{{ $item->HoTen }}</strong> <br>
                            <span class="phone-text">{{ $item->SoDienThoai }}</span>
                        </td>
                        <td>{{ date('d/m/Y H:i', strtotime($item->NgayDat)) }}</td>
                        <td class="price-text">{{ number_format($item->TongTien, 0, ',', '.') }} đ</td>
                        <td>
                            <span class="status-badge {{ $item->TrangThai == 'Hoàn thành' ? 'status-completed' : ($item->TrangThai == 'Đã hủy' ? 'status-canceled' : 'status-pending') }}">
                                {{ $item->TrangThai }}
                            </span>
                        </td>
                        <td>
                            <div class="action-group">
                                <a href="/admin/order/detail/{{ $item->DonHangID }}" class="edit-btn">Chi tiết</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="empty-text-center">Chưa có đơn hàng nào</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection