<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = DB::table('SanPham')
            ->join('DanhMucSanPham', 'SanPham.DanhMucSanPhamID', '=', 'DanhMucSanPham.DanhMucSanPhamID')
            ->select('SanPham.*', 'DanhMucSanPham.TenDanhMuc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('SanPham.TenSanPham', 'like', '%' . $search . '%')
                ->orWhere('SanPham.MoTa', 'like', '%' . $search . '%')
                ->orWhere('DanhMucSanPham.TenDanhMuc', 'like', '%' . $search . '%');
            });
        }

        $products = $query->orderBy('SanPham.SanPhamID', 'asc')->get();
        
        $totalProducts = DB::table('SanPham')->count();

        return view('admin.product.product_index', compact('products', 'search', 'totalProducts'));
    }

    public function create()
    {
        $categories = DB::table('DanhMucSanPham')->get();
        return view('admin.product.product_add', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenSanPham' => 'required',
            'GiaSanPham' => 'required|numeric', 
            'DanhMucSanPhamID' => 'required'
        ]);

        $imageName = null;
        if ($request->hasFile('DuongDanAnh')) { 
            $image = $request->file('DuongDanAnh');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        DB::table('SanPham')->insert([
            'TenSanPham' => $request->input('TenSanPham'),
            'GiaSanPham' => $request->input('GiaSanPham'), 
            'MoTa' => $request->input('MoTa'),
            'DanhMucSanPhamID' => $request->input('DanhMucSanPhamID'),
            'DuongDanAnh' => $imageName,
            'TrangThai' => 1
        ]);

        return redirect('/admin/product')->with('success', 'Thêm sản phẩm mới thành công!');
    }
    public function edit($id)
    {
        // Lấy thông tin chi tiết của sản phẩm hiện tại theo ID
        $product = DB::table('SanPham')->where('SanPhamID', $id)->first();
        
        // Nếu không tìm thấy sản phẩm thì đá về trang danh sách
        if (!$product) {
            return redirect('/admin/product')->with('error', 'Sản phẩm không tồn tại!');
        }

        // Lấy danh sách danh mục để người dùng chọn lại nếu muốn
        $categories = DB::table('DanhMucSanPham')->get();

        return view('admin.product.product_edit', compact('product', 'categories'));
    }

    // 2. Hàm xử lý cập nhật dữ liệu khi bấm nút "Cập nhật sản phẩm"
    public function update(Request $request, $id)
    {
        $request->validate([
            'TenSanPham' => 'required',
            'GiaSanPham' => 'required|numeric',
            'DanhMucSanPhamID' => 'required'
        ]);

        // Lấy thông tin sản phẩm cũ trong DB ra trước để phòng hờ
        $product = DB::table('SanPham')->where('SanPhamID', $id)->first();

        // Mặc định giữ lại tên file ảnh cũ
        $imageName = $product->DuongDanAnh;

        // Nếu người dùng có chọn file ảnh mới tinh thì mới xử lý upload đè lên
        if ($request->hasFile('DuongDanAnh')) {
            $image = $request->file('DuongDanAnh');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images'), $imageName);
        }

        // Tiến hành Update dữ liệu theo đúng tên cột Database của nhóm bạn
        DB::table('SanPham')->where('SanPhamID', $id)->update([
            'TenSanPham' => $request->input('TenSanPham'),
            'GiaSanPham' => $request->input('GiaSanPham'),
            'MoTa' => $request->input('MoTa'),
            'DanhMucSanPhamID' => $request->input('DanhMucSanPhamID'),
            'DuongDanAnh' => $imageName,
            // Giữ nguyên trạng thái cũ hoặc cập nhật nếu có form gạt trạng thái
            'TrangThai' => $product->TrangThai 
        ]);

        return redirect('/admin/product')->with('success', 'Cập nhật sản phẩm thành công!');
    }
    public function delete($id)
    {
        $hasOrder = DB::table('ChiTietDonHang')->where('ChiTietSanPhamID', $id)->exists();

        if ($hasOrder) {
            return redirect('/admin/product')->with('error', 'Không thể xóa sản phẩm này vì nó đã tồn tại trong lịch sử đơn hàng của khách!');
        }

        $product = DB::table('SanPham')->where('SanPhamID', $id)->first();
        if ($product) {
            if ($product->DuongDanAnh && file_exists(public_path('images/' . $product->DuongDanAnh))) {
                unlink(public_path('images/' . $product->DuongDanAnh));
            }
            DB::table('SanPham')->where('SanPhamID', $id)->delete();
            return redirect('/admin/product')->with('success', 'Xóa sản phẩm thành công!');
        }
        return redirect('/admin/product')->with('error', 'Sản phẩm không tồn tại!');
    }
}