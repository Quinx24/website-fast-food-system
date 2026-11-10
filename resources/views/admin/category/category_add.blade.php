@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="/css/category.css">
<div class="content">

    <div class="category-box-long">
        
        <h1 class="customer-title">
            + Thêm Danh Mục
        </h1>

        <form action="/admin/category/store" method="POST">
            @csrf

            <div class="form-group">
                <label for="TenDanhMuc">Tên danh mục</label>
                <input type="text" name="TenDanhMuc" id="TenDanhMuc" placeholder="Nhập tên danh mục..." required>
                
                @error('TenDanhMuc')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="category-btn-group">
                <button type="submit" class="save-btn">
                    + Thêm
                </button>
                <a href="/admin/category" class="save-btn">
                    ← Quay lại
                </a>
            </div>
        </form>

    </div>

</div>

@endsection