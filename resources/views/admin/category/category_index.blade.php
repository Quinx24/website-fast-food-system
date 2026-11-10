@extends('admin.dashboard')

@section('content')

@if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif

<div class="content">

    <form action="/admin/category" method="GET" class="search-box">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Tìm kiếm danh mục...">
        <button type="submit" class="search-btn">
            <img src="{{ asset('images/search_icon.png') }}">
        </button>
    </form>

    <div class="category-header-bar">
        <h1 class="customer-title">
            Quản lý danh mục sản phẩm
        </h1>
        
        <a href="/admin/category/add" class="add-btn">
            + Thêm danh mục mới
        </a>
    </div>

    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

    <div class="table-box">
        <table class="customer-table category-custom-table">
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-name">Tên danh mục</th>
                    <th class="col-action">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @if(count($categories) > 0)
                    @foreach($categories as $item)
                    <tr>
                        <td class="col-id">{{ $item->DanhMucSanPhamID }}</td>
                        <td class="col-name-text">{{ $item->TenDanhMuc }}</td>
                        <td>
                            <div class="action-group">
                                <a href="/admin/category/edit/{{ $item->DanhMucSanPhamID }}" class="edit-btn">
                                    Sửa
                                </a>

                                <a href="/admin/category/delete/{{ $item->DanhMucSanPhamID }}" class="delete-btn" onclick="return confirm('Bạn có muốn xóa danh mục này không?')">
                                    Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3" class="empty-text-center">
                            Không có danh mục phù hợp
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>

@endsection