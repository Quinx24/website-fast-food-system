<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Hàm này dùng chung để bắt buộc phải đăng nhập mới được xem đơn hàng
    public function __construct()
    {
        if (!session()->has('user_id')) {
            redirect('/login')->send();
        }
    }


    public function getDonHangsBySession()
    {
        $khachHang = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();

        if (!$khachHang) {
            $donHangs = collect([]);
        } else {
            $donHangs = DB::table('donhang')
                ->where('KhachHangID', $khachHang->KhachHangID)
                ->get();
        }
        return $donHangs;
    }

    public function history()
    {
        $donHangs = $this->getDonHangsBySession();
        return view('orders.history_index', compact('donHangs'));
    }

    public function tracking()
    {
        $donHangs = $this->getDonHangsBySession();
        return view('orders.tracking_index', compact('donHangs'));
    }


    public function getOrderInfo($id)
    {
        $khachHang = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();

        $donHang = DB::table('donhang')
            ->where('DonHangID', $id)
            ->where('KhachHangID', $khachHang->KhachHangID)
            ->first();

        $thongTinThanhToan = DB::table('thanhtoan')
            ->where('DonHangID', $donHang->DonHangID)
            ->first();

        if (!$donHang) {
            $items = collect([]);
        } else {
            $items = DB::table('donhang')
                ->join(
                    'chitietdonhang',
                    'donhang.DonHangID',
                    '=',
                    'chitietdonhang.DonHangID'
                )
                ->join(
                    'chitietsanpham',
                    'chitietdonhang.ChiTietSanPhamID',
                    '=',
                    'chitietsanpham.ChiTietSanPhamID'
                )
                ->join(
                    'sanpham',
                    'chitietsanpham.SanPhamID',
                    '=',
                    'sanpham.SanPhamID'
                )
                ->where('donhang.DonHangID', $id)
                ->select(
                    'sanpham.TenSanPham',
                    'chitietdonhang.SoLuong',
                    'chitietdonhang.DonGia'
                )
                ->get();
        }

        return [
            'donHang' => $donHang,
            'thongTinThanhToan' => $thongTinThanhToan,
            'items' => $items
        ];
    }

    public function orderHistory($id)
    {
        $orderInfo = $this->getOrderInfo($id);
        if (!$orderInfo || empty($orderInfo['donHang'])) {
            return redirect()->route('orders.history.index')->with('error', 'Không tìm thấy đơn hàng!');
        }
        return view('orders.history_show', [
            'donHang' => $orderInfo['donHang'],
            'items' => $orderInfo['items']
        ]);
    }

    public function orderTracking($id)
    {
        $orderInfo = $this->getOrderInfo($id);
        if (!$orderInfo || empty($orderInfo['donHang'])) {
            return redirect()->route('orders.tracking.index')->with('error', 'Không tìm thấy đơn hàng!');
        }
        return view('orders.tracking_show', [
            'donHang' => $orderInfo['donHang'],
            'thongTinThanhToan' => $orderInfo['thongTinThanhToan']
        ]);
    }


    // ================= PHẦN DÀNH CHO ADMIN =================

    // 1. Hiển thị danh sách toàn bộ đơn hàng
    public function adminIndex()
    {
        if (session('vaitro') != 1) return redirect('/'); // Chặn nếu không phải admin

        $donHangs = DB::table('donhang')
            ->join('khachhang', 'donhang.KhachHangID', '=', 'khachhang.KhachHangID')
            ->select('donhang.*', 'khachhang.HoTen', 'khachhang.SoDienThoai')
            ->orderBy('donhang.DonHangID', 'desc')
            ->get();

        return view('admin.order.index', compact('donHangs'));
    }

    // 2. Xem chi tiết 1 đơn hàng phía Admin
    public function adminShow($id)
    {
        if (session('vaitro') != 1) return redirect('/');

        $donHang = DB::table('donhang')
            ->leftJoin('khachhang', 'donhang.KhachHangID', '=', 'khachhang.KhachHangID')
            ->where('donhang.DonHangID', $id)
            ->select('donhang.*', 'khachhang.HoTen', 'khachhang.SoDienThoai', 'khachhang.DiaChi')
            ->first();

        // Query lấy chi tiết các món ăn của đơn hàng này
        $items = DB::table('chitietdonhang')
            ->join('chitietsanpham', 'chitietdonhang.ChiTietSanPhamID', '=', 'chitietsanpham.ChiTietSanPhamID')
            ->join('sanpham', 'chitietsanpham.SanPhamID', '=', 'sanpham.SanPhamID')
            ->where('chitietdonhang.DonHangID', $id)
            ->select('sanpham.TenSanPham', 'chitietsanpham.TenBienThe', 'chitietdonhang.SoLuong', 'chitietdonhang.DonGia')
            ->get();

        return view('admin.order.detail', compact('donHang', 'items'));
    }

    // 3. Cập nhật trạng thái đơn hàng (Không cho phép lùi trạng thái)
    public function adminUpdateStatus(Request $request, $id)
    {
        if (session('vaitro') != 1) return redirect('/');

        $donHang = DB::table('donhang')->where('DonHangID', $id)->first();
        if (!$donHang) return redirect()->back()->with('error', 'Đơn hàng không tồn tại.');

        $trangThaiCu = $donHang->TrangThai;
        $trangThaiMoi = $request->trang_thai;

        // Trọng số để so sánh thứ tự trạng thái
        $thuTu = [
            'Chờ xác nhận' => 1,
            'Đang chuẩn bị' => 2,
            'Đang giao' => 3,
            'Hoàn thành' => 4,
            'Đã hủy' => 5
        ];

        $bacCu = $thuTu[$trangThaiCu] ?? 0;
        $bacMoi = $thuTu[$trangThaiMoi] ?? 0;

        // Nếu đã hoàn thành hoặc hủy thì không đổi được nữa
        if (in_array($trangThaiCu, ['Hoàn thành', 'Đã hủy'])) {
            return redirect()->back()->with('error', "Đơn hàng đã '$trangThaiCu', không thể thay đổi.");
        }
        
        // Không cho phép lùi trạng thái
        if ($bacMoi < $bacCu) {
            return redirect()->back()
                ->with('error', "Không thể lùi trạng thái từ '$trangThaiCu' về '$trangThaiMoi'.");
        }

        DB::table('donhang')
            ->where('DonHangID', $id)
            ->update([
                'TrangThai' => $trangThaiMoi
            ]);

        return redirect()->back()
            ->with('success', 'Cập nhật trạng thái thành công!');
    }

    // 4. Báo cáo thống kê (Lọc theo ngày, Top bán chạy, Top bán ế)
    public function adminReport(Request $request)
    {
        if (session('vaitro') != 1) return redirect('/');

        $tuNgay = $request->tu_ngay;
        $denNgay = $request->den_ngay;

        // Khởi tạo query cho bảng đơn hàng
        $queryDonHang = DB::table('donhang');
        if ($tuNgay) $queryDonHang->whereDate('NgayDat', '>=', $tuNgay);
        if ($denNgay) $queryDonHang->whereDate('NgayDat', '<=', $denNgay);

        // Tổng số lượng đơn hàng
        $tongDonHang = (clone $queryDonHang)->count();
        
        // Tổng doanh thu (Chỉ tính đơn đã hoàn thành)
        $tongDoanhThu = (clone $queryDonHang)->where('TrangThai', 'Hoàn thành')->sum('TongTien');

        // Top 5 sản phẩm bán chạy nhất
        $queryBanChay = DB::table('chitietdonhang')
            ->join('chitietsanpham', 'chitietdonhang.ChiTietSanPhamID', '=', 'chitietsanpham.ChiTietSanPhamID')
            ->join('sanpham', 'chitietsanpham.SanPhamID', '=', 'sanpham.SanPhamID')
            ->join('donhang', 'chitietdonhang.DonHangID', '=', 'donhang.DonHangID');
            
        if ($tuNgay) $queryBanChay->whereDate('donhang.NgayDat', '>=', $tuNgay);
        if ($denNgay) $queryBanChay->whereDate('donhang.NgayDat', '<=', $denNgay);

        $sanPhamBanChay = $queryBanChay->select('sanpham.TenSanPham', DB::raw('SUM(chitietdonhang.SoLuong) as TongSoLuongBieu'))
            ->groupBy('sanpham.TenSanPham', 'sanpham.SanPhamID')
            ->orderByDesc('TongSoLuongBieu')
            ->limit(5)
            ->get();

        // Top 5 sản phẩm bán ế nhất (Bao gồm cả những món chưa bán được cái nào - TongSoLuongBieu = 0)
        $sanPhamBanE = DB::table('sanpham')
            ->leftJoin('chitietsanpham', 'sanpham.SanPhamID', '=', 'chitietsanpham.SanPhamID')
            ->leftJoin('chitietdonhang', 'chitietsanpham.ChiTietSanPhamID', '=', 'chitietdonhang.ChiTietSanPhamID')
            ->leftJoin('donhang', function($join) use ($tuNgay, $denNgay) {
                $join->on('chitietdonhang.DonHangID', '=', 'donhang.DonHangID');
                if ($tuNgay) $join->whereDate('donhang.NgayDat', '>=', $tuNgay);
                if ($denNgay) $join->whereDate('donhang.NgayDat', '<=', $denNgay);
            })
            ->select('sanpham.TenSanPham', DB::raw('IFNULL(SUM(chitietdonhang.SoLuong), 0) as TongSoLuongBieu'))
            ->groupBy('sanpham.TenSanPham', 'sanpham.SanPhamID')
            ->orderBy('TongSoLuongBieu', 'asc')
            ->limit(5)
            ->get();

        return view('admin.statistic.index', compact('tongDoanhThu', 'tongDonHang', 'sanPhamBanChay', 'sanPhamBanE', 'tuNgay', 'denNgay'));
    }
}