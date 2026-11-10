<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 

class CategoryController extends Controller
{
    //  Trang danh sách danh mục
    public function index(Request $request) {
        $search = $request->input('search');
        $query = DB::table('DanhMucSanPham');
        if ($search) {
            $query->where('TenDanhMuc', 'like', '%' . $search . '%');
        }
        $categories = $query->get();
        return view('admin.category.category_index', compact('categories', 'search'));
    }

    //  Hiển thị form thêm mới
    public function create() {
        return view('admin.category.category_add');
    }

    // lưu danh mục mới khi bấm nút thêm
    public function store(Request $request) {
        $request->validate(['TenDanhMuc' => 'required']);
        
        DB::table('DanhMucSanPham')->insert([
            'TenDanhMuc' => $request->input('TenDanhMuc')
        ]);
        return redirect('/admin/category')->with('success', 'Thêm danh mục mới thành công!');
    }

    //  Hiển thị form sửa
    public function edit($id) {
        $category = DB::table('DanhMucSanPham')->where('DanhMucSanPhamID', $id)->first();
        return view('admin.category.edit', compact('category'));
    }

    //  Xử lý cập nhật danh mục khi bấm nút Cập nhật
    public function update(Request $request, $id) {
        DB::table('DanhMucSanPham')->where('DanhMucSanPhamID', $id)->update([
            'TenDanhMuc' => $request->TenDanhMuc
        ]);
        return redirect('/admin/category')->with('success', 'Cập nhật thành công');
    }

    // Xử lý xóa danh mục
    public function delete($id) {
        DB::table('DanhMucSanPham')->where('DanhMucSanPhamID', $id)->delete();
        return redirect('/admin/category')->with('success', 'Xóa danh mục thành công!');
    }
}