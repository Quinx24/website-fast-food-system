<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        if (!session()->has('user_id')) {
            redirect('/login')->send();
        }
    }
    public function index()
    {
        $khachHang = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();

        $khachHangID = $khachHang->KhachHangID;

        $gioHang = DB::table('giohang')
            ->where('KhachHangID', $khachHangID)
            ->first();

        if (!$gioHang) {
            return view('cart', ['items' => []]);
        }

        $items = DB::table('giohangchitiet')
            ->join('chitietsanpham', 'giohangchitiet.ChiTietSanPhamID', '=', 'chitietsanpham.ChiTietSanPhamID')
            ->join('sanpham', 'chitietsanpham.SanPhamID', '=', 'sanpham.SanPhamID')
            ->where('giohangchitiet.GioHangID', $gioHang->GioHangID)
            ->select(
                'giohangchitiet.*',
                'sanpham.TenSanPham',
                'sanpham.DuongDanAnh'
            )
            ->get();

        return view('cart', compact('items'));
    }

    public function delete(Request $request)
    {
        DB::table('giohangchitiet')
            ->where('GioHangChiTietID', $request->id)
            ->delete();

        return redirect('/cart');
    }

    public function them(Request $request)
    {
        $khachHang = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();

        $khachHangID = $khachHang->KhachHangID;

        // 1. tìm giỏ hàng
        $gioHang = DB::table('giohang')
            ->where('KhachHangID', $khachHangID)
            ->first();

        // 2. nếu chưa có → tạo
        if (!$gioHang) {
            DB::table('giohang')->insert([
                'KhachHangID' => $khachHangID,
                'TrangThai' => 'Mới'
            ]);

            $gioHangID = DB::getPdo()->lastInsertId();
        } else {
            $gioHangID = $gioHang->GioHangID;
        }


        // 3. kiểm tra đã có trong giỏ chưa
        $ctsp = DB::table('chitietsanpham')
            ->where('SanPhamID', $request->SanPhamID)
            ->first();

        if (!$ctsp) {
            return redirect()->back()->with('error', 'Không tìm thấy chi tiết sản phẩm');
        }

        $exist = DB::table('giohangchitiet')
            ->where('GioHangID', $gioHangID)
            ->where('ChiTietSanPhamID', $ctsp->ChiTietSanPhamID)
            ->first();

        if ($exist) {
            DB::table('giohangchitiet')
                ->where('GioHangChiTietID', $exist->GioHangChiTietID)
                ->update([
                    'SoLuong' => $exist->SoLuong + $request->SoLuong
                ]);
        } else {
            DB::table('giohangchitiet')->insert([
                'GioHangID' => $gioHangID,
                'ChiTietSanPhamID' => $ctsp->ChiTietSanPhamID,
                'SoLuong' => $request->SoLuong,
                'DonGia' => $ctsp->Gia
            ]);
        }

        return redirect('/cart');
    }
    public function update(Request $request)
    {
        $soLuong = max(1, min(10, $request->SoLuong));

        DB::table('giohangchitiet')
            ->where('GioHangChiTietID', $request->id)
            ->update([
                'SoLuong' => $soLuong
            ]);

        return redirect('/cart');
    }
}
