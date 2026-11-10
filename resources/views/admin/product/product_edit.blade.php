@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="/css/product.css">

<div class="content">
    
    <h2 class="product-title">Chỉnh sửa sản phẩm</h2>

    <form action="/admin/product/update/{{ $product->SanPhamID }}" method="POST" class="product-add-form" enctype="multipart/form-data">
        @csrf

        <div class="form-left-col">
            <div class="form-group">
                <label for="TenSanPham">Tên sản phẩm</label>
                <input type="text" id="TenSanPham" name="TenSanPham" value="{{ $product->TenSanPham }}" placeholder="Nhập tên sản phẩm..." required>
            </div>

            <div class="form-group">
                <label for="DanhMucSanPhamID">Danh mục sản phẩm</label>
                <select name="DanhMucSanPhamID" id="DanhMucSanPhamID" class="form-select-category" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->DanhMucSanPhamID }}" {{ $product->DanhMucSanPhamID == $cat->DanhMucSanPhamID ? 'selected' : '' }}>
                            {{ $cat->TenDanhMuc }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="GiaSanPham">Đơn giá (VNĐ)</label>
                <input type="number" id="GiaSanPham" name="GiaSanPham" value="{{ intval($product->GiaSanPham) }}" placeholder="Nhập giá tiền..." required>
            </div>

            <div class="form-group">
                <label for="MoTa">Mô tả món ăn</label>
                <input type="text" id="MoTa" name="MoTa" value="{{ $product->MoTa }}" placeholder="Nhập mô tả...">
            </div>
        </div>

        <div class="form-right-col">
            <div class="form-group" style="width: 100%;">
                <label class="image-upload-label">Hình ảnh sản phẩm</label>
                
                <div class="image-preview-box">
                    <img id="imagePreview" src="{{ $product->DuongDanAnh ? '/images/'.$product->DuongDanAnh : '/images/default.png' }}" alt="Preview">
                </div>

                <input type="file" id="DuongDanAnh" name="DuongDanAnh" accept="image/*" onchange="previewImage(this)" class="file-input-control">
            </div>

            <div class="form-action-group">
                <button type="submit" class="save-btn">Cập nhật</button>
                <a href="/admin/product" class="delete-btn">Hủy bỏ</a>
            </div>
        </div>
    </form>

</div>

<script>
// Script cập nhật ảnh preview thời gian thực khi người dùng chọn ảnh mới
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection