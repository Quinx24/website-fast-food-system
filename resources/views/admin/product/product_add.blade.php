@extends('admin.dashboard')

@section('content')
<link rel="stylesheet" href="/css/product.css">

<div class="content">
    
    <h2 class="product-title">Thêm sản phẩm mới</h2>

    <form action="/admin/product/store" method="POST" class="product-add-form" enctype="multipart/form-data">
        @csrf

        <div class="form-left-col">
            <div class="form-group">
                <label for="TenSanPham">Tên sản phẩm</label>
                <input type="text" id="TenSanPham" name="TenSanPham" placeholder="Nhập tên sản phẩm..." required>
            </div>

            <div class="form-group">
                <label for="DanhMucSanPhamID">Danh mục sản phẩm</label>
                <select name="DanhMucSanPhamID" id="DanhMucSanPhamID" class="form-select-category" required>
                    <option value="">-- Chọn danh mục món ăn --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->DanhMucSanPhamID }}">{{ $cat->TenDanhMuc }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="GiaSanPham">Đơn giá (VNĐ)</label>
                <input type="number" id="GiaSanPham" name="GiaSanPham" placeholder="Nhập giá tiền ..." required>
            </div>

            <div class="form-group">
                <label for="MoTa">Mô tả món ăn</label>
                <input type="text" id="MoTa" name="MoTa" placeholder="Nhập mô tả ngắn về sản phẩm...">
            </div>
        </div>

        <div class="form-right-col">
            <div class="form-group" style="width: 100%;">
                <label class="image-upload-label">Hình ảnh sản phẩm</label>
                
                <div class="image-preview-box">
                    <img id="imagePreview" src="/images/default.png" alt="Preview">
                </div>

                <input type="file" id="DuongDanAnh" name="DuongDanAnh" accept="image/*" onchange="previewImage(this)" class="file-input-control">
            </div>

            <div class="form-action-group">
                <button type="submit" class="save-btn">Lưu sản phẩm</button>
                <a href="/admin/product" class="delete-btn">Hủy bỏ</a>
            </div>
        </div>
    </form>

</div>

<script>
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