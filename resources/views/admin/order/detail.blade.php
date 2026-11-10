@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order_statistic.css') }}">

<div class="content">
    <div class="category-header-bar">
        <h1 class="customer-title">Chi tiết Đơn hàng #{{ $donHang->DonHangID }}</h1>
        <a href="/admin/order" class="add-btn btn-back-custom">← Quay lại</a>
    </div>

    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif
    
    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    <div class="order-detail-container">
        <div class="order-info-col">
            <h3 class="order-info-title">Thông tin giao hàng</h3>
            <hr class="order-info-line">
            
            <div class="order-info-row">
                <span class="order-info-label">Người nhận:</span>
                <span class="order-info-value">{{ $donHang->HoTen }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Điện thoại:</span>
                <span class="order-info-value">{{ $donHang->SoDienThoai }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Địa chỉ:</span>
                <span class="order-info-value">{{ $donHang->DiaChiGiaoHang ?? $donHang->DiaChi }}</span>
            </div>
            <div class="order-info-row">
                <span class="order-info-label">Ghi chú:</span>
                <span class="order-info-value">{{ $donHang->GhiChu ?? 'Không có' }}</span>
            </div>
        </div>

        <div class="order-status-col">
            <h3 class="order-status-title">CẬP NHẬT TRẠNG THÁI</h3>
            <form action="/admin/order/update-status/{{ $donHang->DonHangID }}" method="POST" class="order-status-form">
                @csrf
                <select name="trang_thai" class="order-status-select">

                    <option value="Chờ xác nhận"
                        {{ $donHang->TrangThai == 'Chờ xác nhận' ? 'selected' : '' }}>
                        Chờ xác nhận
                    </option>

                    <option value="Đang chuẩn bị"
                        {{ $donHang->TrangThai == 'Đang chuẩn bị' ? 'selected' : '' }}>
                        Đang chuẩn bị
                    </option>

                    <option value="Đang giao"
                        {{ $donHang->TrangThai == 'Đang giao' ? 'selected' : '' }}>
                        Đang giao
                    </option>

                    <option value="Hoàn thành"
                        {{ $donHang->TrangThai == 'Hoàn thành' ? 'selected' : '' }}>
                        Hoàn thành
                    </option>

                    <option value="Đã hủy"
                        {{ $donHang->TrangThai == 'Đã hủy' ? 'selected' : '' }}>
                        Đã hủy
                    </option>

                </select>
                <button type="submit" class="save-btn save-btn-compact">Lưu thay đổi</button>
            </form>
        </div>
    </div>

    <div class="table-box">
        <h3 class="customer-title mt-0">Danh sách món ăn</h3>
        <table class="customer-table category-custom-table">
            <thead>
                <tr>
                    <th>Tên món</th>
                    <th>Biến thể</th>
                    <th class="text-center">Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @if(count($items) > 0)
                    @foreach($items as $item)
                    <tr>
                        <td><strong>{{ $item->TenSanPham }}</strong></td>
                        <td>{{ $item->TenBienThe }}</td>
                        <td class="table-qty">{{ $item->SoLuong }}</td>
                        <td>{{ number_format($item->DonGia, 0, ',', '.') }} đ</td>
                        <td>{{ number_format($item->SoLuong * $item->DonGia, 0, ',', '.') }} đ</td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: #888; font-style: italic; font-size: 18px;">
                            Đơn hàng này chưa có dữ liệu chi tiết món ăn trong hệ thống.
                        </td>
                    </tr>
                @endif
                
                <tr class="total-row">
                    <td colspan="4" class="total-label">Tổng cộng (Bao gồm Ship nếu có):</td>
                    <td class="total-value">{{ number_format($donHang->TongTien, 0, ',', '.') }} đ</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection