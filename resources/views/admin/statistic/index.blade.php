@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="{{ asset('css/order_statistic.css') }}">

<div class="content">
    <div class="category-header-bar">
        <h1 class="customer-title">Báo cáo Thống kê</h1>
    </div>

    <form action="/admin/statistic" method="GET" style="background: #FFB4331A; padding: 25px 30px; border-radius: 25px; display: flex; gap: 20px; align-items: flex-end; margin-bottom: 30px; flex-wrap: wrap;">
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label style="font-weight: bold; color: #FFA629; font-size: 18px;">Từ ngày:</label>
            <input type="date" name="tu_ngay" value="{{ $tuNgay }}" style="padding: 10px 15px; border-radius: 15px; border: 1px solid #FFA629; outline: none; font-family: inherit; background: white; color: #444; font-size: 16px;">
        </div>
        <div style="display: flex; flex-direction: column; gap: 10px;">
            <label style="font-weight: bold; color: #FFA629; font-size: 18px;">Đến ngày:</label>
            <input type="date" name="den_ngay" value="{{ $denNgay }}" style="padding: 10px 15px; border-radius: 15px; border: 1px solid #FFA629; outline: none; font-family: inherit; background: white; color: #444; font-size: 16px;">
        </div>
        <button type="submit" class="save-btn" style="padding: 10px 25px; font-size: 18px; border-radius: 15px; margin-bottom: 0;">
            Lọc Thống Kê
        </button>
        <a href="/admin/statistic" class="add-btn btn-back-custom" style="padding: 10px 25px; font-size: 18px; border-radius: 15px; display: flex; align-items: center; height: 46px;">
            Xóa lọc
        </a>
    </form>

    <div class="cards mt-20">
        <div class="card">
            <div class="card-inner">
                <div class="number">{{ $tongDonHang }}</div>
                <img src="{{ asset('images/bag_icon.png') }}">
                <div class="card-title">Tổng đơn hàng</div>
            </div>
        </div>

        <div class="card">
            <div class="card-inner">
                <div class="number stat-number-large">
                    {{ number_format($tongDoanhThu, 0, ',', '.') }} đ
                </div>
                <img src="{{ asset('images/money_icon.png') }}" class="stat-icon">
                <div class="card-title stat-title-wrap">
                    Doanh thu<br><span class="stat-subtitle">(Đã giao)</span>
                </div>
            </div>
        </div>
    </div>

    <div style="display: flex; gap: 40px; margin-top: 40px; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 400px;">
            <h2 class="customer-title" style="margin-top: 0; font-size: 24px;">Top 5 Bán Chạy Nhất</h2>
            <div class="table-box">
                <table class="customer-table category-custom-table">
                    <thead>
                        <tr>
                            <th class="col-id">Hạng</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanPhamBanChay as $index => $sp)
                        <tr>
                            <td class="col-id"><span class="rank-badge">{{ $index + 1 }}</span></td>
                            <td class="col-name-text"><strong>{{ $sp->TenSanPham }}</strong></td>
                            <td class="text-center qty-text">{{ $sp->TongSoLuongBieu }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="empty-text-center">Chưa có dữ liệu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="flex: 1; min-width: 400px;">
            <h2 class="customer-title" style="margin-top: 0; font-size: 24px;">Top 5 Bán Chậm Nhất</h2>
            <div class="table-box">
                <table class="customer-table category-custom-table">
                    <thead>
                        <tr>
                            <th class="col-id">Hạng</th>
                            <th>Tên sản phẩm</th>
                            <th class="text-center">Đã bán</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sanPhamBanE as $index => $sp)
                        <tr>
                            <td class="col-id"><span class="rank-badge">{{ $index + 1 }}</span></td>
                            <td class="col-name-text"><strong>{{ $sp->TenSanPham }}</strong></td>
                            <td class="text-center qty-text">{{ $sp->TongSoLuongBieu }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="empty-text-center">Chưa có dữ liệu</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection