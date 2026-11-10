@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="/css/product.css?v={{ time() }}">

<div class="content">
    
    <h2 class="product-title">Quản lý sản phẩm</h2>

    
    <form action="/admin/product" method="GET" class="search-form">
        <div class="search-box">
            <input type="text" name="search" value="{{ $search }}" placeholder="Tìm kiếm sản phẩm...">
            <button type="submit" class="search-btn">
                <img src="{{ asset('images/search_icon.png') }}" alt="Search">
            </button>
        </div>
        
        <a href="/admin/product" class="btn-reset">Xóa bộ lọc</a>
    </form>
    <div class="header-actions">
        <a href="/admin/product/add" class="add-btn">+ Thêm sản phẩm</a>
    </div>


    <div class="table-box">
        <table class="product-table">
            <thead>
                <tr>
                    <th>Mã sản phẩm</th>
                    <th>Tên sản phẩm</th>
                    <th>Đơn giá</th>
                    <th>Mô tả</th>
                    <th>Danh mục</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                <tr>
                    <td>{{ $item->SanPhamID }}</td>
                    <td class="product-name">{{ $item->TenSanPham }}</td>
                    <td class="product-price">{{ number_format($item->GiaSanPham, 0, ',', '.') }} VNĐ</td>
                    <td class="product-desc">{{ $item->MoTa }}</td>
                    <td>
                        <span class="category-badge">{{ $item->TenDanhMuc }}</span>
                    </td>
                    <td>
                        <img src="{{ $item->DuongDanAnh ? '/images/'.$item->DuongDanAnh : '/images/default.png' }}" alt="" class="product-img">
                    </td>
                    <td>
                        <div class="action-group">
                            <a href="/admin/product/edit/{{ $item->SanPhamID }}" class="edit-btn">Sửa</a>
                            <a href="/admin/product/delete/{{ $item->SanPhamID }}" onclick="return confirm('Xóa sản phẩm này?')" class="delete-btn">Xóa</a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection