@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="/css/category.css">

<div class="content">

    <div class="category-box-long">
        
        <h1 class="customer-title">
            + Sửa Danh Mục
        </h1>

        <form action="/admin/category/update/{{ $category->DanhMucSanPhamID }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="TenDanhMuc">Tên danh mục</label>
                
                <input type="text" name="TenDanhMuc" id="TenDanhMuc" value="{{ $category->TenDanhMuc }}" required>
                
                @error('TenDanhMuc')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="category-btn-group">
                <button type="submit" class="save-btn">
                    Cập nhật
                </button>
                <a href="/admin/category" class="save-btn">
                    ← Quay lại
                </a>
            </div>
        </form>

    </div>

</div>

@endsection