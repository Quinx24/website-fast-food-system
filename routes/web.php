<?php
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;


View::composer('*', function ($view) {

    $count = 0;

    if (session()->has('user_id')) {

        $khachHang = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();

        if ($khachHang) {

            $gioHang = DB::table('giohang')
                ->where('KhachHangID', $khachHang->KhachHangID)
                ->first();

            if ($gioHang) {

                $count = DB::table('giohangchitiet')
                    ->where('GioHangID', $gioHang->GioHangID)
                    ->sum('SoLuong');
            }
        }
    }

    $view->with('cartCount', $count);
});


/* ================= HOME ================= */

Route::get('/', function (Request $request) {

    $category = $request->category ?? 'all';

    $categories = DB::table('danhmucsanpham')->get();

    $query = DB::table('sanpham')
        ->where('TrangThai', 1);

    if ($category != 'all') {

        $query->where('DanhMucSanPhamID', $category);
    }

    $products = $query->get();

    return view('home', compact(
        'products',
        'categories',
        'category'
    ));
});


/* ================= PRODUCT DETAIL ================= */

Route::get('/product/{id}', function ($id) {

    $product = DB::table('sanpham')
        ->where('SanPhamID', $id)
        ->first();

    $details = DB::table('chitietsanpham')
        ->where('SanPhamID', $id)
        ->get();

    return view('product_detail', compact(
        'product',
        'details'
    ));
});


/* ================= SEARCH ================= */

Route::get('/search', function (Request $request) {

    $keyword = $request->keyword;

    if (!$keyword) {
        return redirect('/');
    }

    $products = DB::table('sanpham')
        ->where('TenSanPham', 'like', '%' . $keyword . '%')
        ->where('TrangThai', 1)
        ->orderBy('SanPhamID', 'desc')
        ->get();

    return view('search', compact(
        'products',
        'keyword'
    ));
})->name('search');



Route::get('/about', function () {
    return view('about');
});


/* ================= LOGIN ================= */

Route::get('/login', function () {

    return view('auth.login');
})->name('login');


Route::post('/login', function (Request $request) {

    $user = DB::table('taikhoan')
        ->where('Email', $request->email)
        ->where('MatKhau', $request->password)
        ->first();

    if ($user) {

        session([
            'user_id' => $user->TaiKhoanID,
            'user_name' => $user->HoTen,
            'vaitro' => $user->VaiTroID
        ]);

        // ADMIN
        if($user->VaiTroID == 1)
        {
            return redirect()->route('admin.dashboard');
        }

        // KHÁCH HÀNG
        
        return redirect('/');
    }

    return back()->with(
        'error',
        'Sai email hoặc mật khẩu'
    );
});





/* ================= ADMIN DASHBOARD ================= */

Route::get('/admin/dashboard', function () {

    if(session('vaitro') != 1)
    {
        return redirect('/');
    }

    $tongKhachHang = DB::table('khachhang')->count();

    $tongSanPham = DB::table('sanpham')->count();

    $tongDoanhThu = DB::table('donhang')
        ->where('TrangThai', 'Hoàn thành')
        ->sum('TongTien');

    return view('admin.home', compact(
        'tongKhachHang',
        'tongSanPham',
        'tongDoanhThu'
    ));

})->name('admin.dashboard');



/* ================= REGISTER ================= */

Route::get('/register', function () {

    return view('auth.register');
})->name('register');


Route::post('/register', function (Request $request) {

    $request->validate([
        'name' => ['required', 'regex:/^[\pL\s]+$/u'],
        'phone' => ['required', 'regex:/^[0-9]{10}$/'],
        'email' => ['required', 'email', 'unique:taikhoan,Email'],
        'password' => ['required', 'min:6'],
        'NgaySinh' => ['required'],
        'DiaChi' => ['required']
    ]);

    // lưu tài khoản
    DB::table('taikhoan')->insert([

        'VaiTroID' => 2,
        'HoTen' => $request->name,
        'Email' => $request->email,
        'MatKhau' => $request->password,
        'NgayTao' => now()

    ]);

    $taikhoanID = DB::getPdo()->lastInsertId();

    // lưu khách hàng
    DB::table('khachhang')->insert([

        'TaiKhoanID' => $taikhoanID,
        'HoTen' => $request->name,
        'Email' => $request->email,
        'SoDienThoai' => $request->phone,
        'NgaySinh' => $request->NgaySinh,
        'DiaChi' => $request->DiaChi,
        'NgayTao' => now()

    ]);

    return redirect('/login')
        ->with('success', 'Đăng ký thành công!');
});


/* ================= GIỎ HÀNG ================= */

Route::post('/gio-hang/them', [CartController::class, 'them']);

Route::get('/cart', [CartController::class, 'index']);

Route::post('/cart/update', [CartController::class, 'update']);

Route::post('/cart/delete', [CartController::class, 'delete']);


/* ================= THANH TOÁN ================= CHỖ NÀY CÓ NHẦM KHÔNG VẬY */

Route::get('/profile/edit', function () {

    if (!session()->has('user_id')) {

        return redirect('/login');
    }

    $customer = DB::table('khachhang')

        ->join(
            'taikhoan',
            'khachhang.TaiKhoanID',
            '=',
            'taikhoan.TaiKhoanID'
        )

        ->where(
            'khachhang.TaiKhoanID',
            session('user_id')
        )

        ->select(
            'khachhang.*',
            'taikhoan.MatKhau'
        )

        ->first();

    return view('edit_profile', compact('customer'));
});


/* ================= ĐẶT HÀNG ================= */

Route::get('/buy-now/{id}', function ($id) {

    if (!session()->has('user_id')) {
        return redirect('/login');
    }

    $khachHang = DB::table('khachhang')
        ->where('TaiKhoanID', session('user_id'))
        ->first();

    $item = DB::table('chitietsanpham')
        ->join(
            'sanpham',
            'chitietsanpham.SanPhamID',
            '=',
            'sanpham.SanPhamID'
        )
        ->where('sanpham.SanPhamID', $id)
        ->select(
            'sanpham.TenSanPham',
            'sanpham.DuongDanAnh',
            'chitietsanpham.Gia',
            'chitietsanpham.ChiTietSanPhamID'
        )
        ->first();

    $item->SoLuong = 1;

    $items = collect([$item]);

    session([
        'buy_now' => true
    ]);

    return view('checkout', compact(
        'items',
        'khachHang'
    ));
});

Route::get('/checkout', function () {

    if (!session()->has('user_id')) {

        return redirect('/login');
    }

    $khachHang = DB::table('khachhang')
        ->where('TaiKhoanID', session('user_id'))
        ->first();

    $gioHang = DB::table('giohang')
        ->where('KhachHangID', $khachHang->KhachHangID)
        ->first();

    $items = DB::table('giohangchitiet')
        ->join(
            'chitietsanpham',
            'giohangchitiet.ChiTietSanPhamID',
            '=',
            'chitietsanpham.ChiTietSanPhamID'
        )
        ->join(
            'sanpham',
            'chitietsanpham.SanPhamID',
            '=',
            'sanpham.SanPhamID'
        )
        ->where('giohangchitiet.GioHangID', $gioHang->GioHangID)
        ->select(
            'giohangchitiet.*',
            'sanpham.TenSanPham',
            'sanpham.DuongDanAnh',
            'chitietsanpham.Gia'
        )
        ->get();

    return view('checkout', compact(
        'items',
        'khachHang'
    ));
});

Route::post('/checkout', function (Request $request) {

    $khachHang = DB::table('khachhang')
        ->where('TaiKhoanID', session('user_id'))
        ->first();


        if (session('buy_now')) {

    $items = collect([
        (object)[
            'ChiTietSanPhamID' => $request->ChiTietSanPhamID,
            'SoLuong' => 1,
            'Gia' => $request->Gia
        ]
    ]);

} else {

    $gioHang = DB::table('giohang')
        ->where('KhachHangID', $khachHang->KhachHangID)
        ->first();

    $items = DB::table('giohangchitiet')
        ->join(
            'chitietsanpham',
            'giohangchitiet.ChiTietSanPhamID',
            '=',
            'chitietsanpham.ChiTietSanPhamID'
        )
        ->where('giohangchitiet.GioHangID', $gioHang->GioHangID)
        ->get();
}

    

    $tong = 0;

    foreach ($items as $item) {

        $tong += $item->SoLuong * $item->Gia;
    }

    $ship = 35000;

    $diachi = strtolower($request->diachi);

    if (
        str_contains($diachi, 'quận 1') ||
        str_contains($diachi, 'quan 1') ||
        str_contains($diachi, 'quận 3') ||
        str_contains($diachi, 'quan 3') ||
        str_contains($diachi, 'quận 5') ||
        str_contains($diachi, 'quan 5') ||
        str_contains($diachi, 'quận 10') ||
        str_contains($diachi, 'quan 10')
    ) {
        $ship = 0;
    }

    $tong += $ship;

    DB::table('donhang')->insert([

        'NgayDat' => now(),
        'TongTien' => $tong,
        'TrangThai' => 'Chờ xác nhận',
        'DiaChiGiaoHang' => $request->diachi,
        'GhiChu' => $request->ghichu,
        'KhachHangID' => $khachHang->KhachHangID

    ]);

    $donHangID = DB::getPdo()->lastInsertId();

    foreach ($items as $item) {

        DB::table('chitietdonhang')->insert([

            'DonHangID' => $donHangID,
            'ChiTietSanPhamID' => $item->ChiTietSanPhamID,
            'SoLuong' => $item->SoLuong,
            'DonGia' => $item->Gia

        ]);
    }

    DB::table('thanhtoan')->insert([

        'DonHangID' => $donHangID,
        'PhuongThuc' => $request->thanhtoan,
        'TrangThai' => 'Chưa thanh toán',
        'NgayThanhToan' => now()

    ]);

    

    $gioHang = DB::table('giohang')
    ->where('KhachHangID', $khachHang->KhachHangID)
    ->first();

    DB::table('giohangchitiet')
    ->where('GioHangID', $gioHang->GioHangID)
    ->delete();

    session()->forget('buy_now');
    return redirect('/')
        ->with('success', 'Đặt hàng thành công!');
    });


/* ================= PROFILE ================= */


Route::get('/profile', function () {

    if (!session()->has('user_id')) {
        return redirect('/login');
    }

    // Nếu là Admin (VaiTroID = 1)
    if (session('vaitro') == 1) {
        $admin = DB::table('taikhoan')
            ->where('TaiKhoanID', session('user_id'))
            ->first();
            
        // Tạo một object giả lập để giao diện profile không bị lỗi
        $customer = (object) [
            'HoTen' => $admin->HoTen,
            'Email' => $admin->Email,
            'SoDienThoai' => 'Không áp dụng (Tài khoản Admin)',
            'DiaChi' => 'Không áp dụng',
            'NgaySinh' => null
        ];
    } 
    // Nếu là Khách hàng (VaiTroID = 2)
    else {
        $customer = DB::table('khachhang')
            ->where('TaiKhoanID', session('user_id'))
            ->first();
    }

    return view('profile', compact('customer'));
});


/* ================= EDIT PROFILE ================= */


Route::post('/profile/update', function (Illuminate\Http\Request $request) {

    if (!session()->has('user_id')) {

        return redirect('/login');
    }

    // validate

    $request->validate([

        'hoten' => [
            'required',
            'regex:/^[\pL\s]+$/u'
        ],

        'sdt' => [
            'required',
            'regex:/^0[0-9]{9}$/'
        ],

        'diachi' => [
            'required'
        ],

        'email' => [
            'required',
            'email'
        ],

        'NgaySinh' => [
            'required'
        ],

        'password' => [
            'required',
            'min:6'
        ]

    ], [

        'hoten.required' => 'Vui lòng nhập họ tên',
        'hoten.regex' => 'Họ tên không được chứa số hoặc ký tự đặc biệt',

        'sdt.required' => 'Vui lòng nhập số điện thoại',
        'sdt.regex' => 'Số điện thoại phải gồm 10 số và bắt đầu bằng số 0',

        'diachi.required' => 'Vui lòng nhập địa chỉ',

        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không đúng định dạng',

        'NgaySinh.required' => 'Vui lòng nhập ngày sinh',

        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min' => 'Mật khẩu tối thiểu 6 ký tự'

    ]);


    // cập nhật bảng khách hàng
    DB::table('khachhang')
        ->where('TaiKhoanID', session('user_id'))
        ->update([

            'HoTen' => $request->hoten,
            'SoDienThoai' => $request->sdt,
            'DiaChi' => $request->diachi,
            'NgaySinh' => $request->NgaySinh,
            'Email' => $request->email

        ]);

    // cập nhật bảng tài khoản
    DB::table('taikhoan')
        ->where('TaiKhoanID', session('user_id'))
        ->update([

            'HoTen' => $request->hoten,
            'Email' => $request->email,
            'MatKhau' => $request->password

        ]);

    // cập nhật session
    session([
        'user_name' => $request->hoten
    ]);

    return redirect('/profile')
        ->with('success', 'Cập nhật thành công');
});


/* ================= CUSTOMER ================= */


Route::get('/admin/customer',
    [CustomerController::class, 'index']);

Route::get('/admin/customer/add',
    [CustomerController::class, 'create']);

Route::post('/admin/customer/store',
    [CustomerController::class, 'store']);

Route::get('/admin/customer/delete/{id}',
[CustomerController::class, 'delete']);

Route::get('/admin/customer/edit/{id}',
    [CustomerController::class, 'edit']);

Route::post('/admin/customer/update/{id}',
    [CustomerController::class, 'update']);

/* ================= LOGOUT ================= */

Route::get('/logout', function () {

    session()->flush();

    return redirect('/login');

})->name('logout');

/* ================= CHANGE PASSWORD ================= */

Route::get('/change-password', function () {

    if (!session()->has('user_id')) {

        return redirect('/login');
    }

    return view('change_password');
});


Route::post('/change-password', function (Illuminate\Http\Request $request) {

    if (!session()->has('user_id')) {

        return redirect('/login');
    }

    $request->validate([

        'old_password' => ['required'],
        'new_password' => ['required', 'min:6']

    ]);

    $user = DB::table('taikhoan')
        ->where('TaiKhoanID', session('user_id'))
        ->first();

    // kiểm tra mật khẩu cũ
    if ($user->MatKhau != $request->old_password) {

        return back()->with(
            'error',
            'Mật khẩu cũ không đúng'
        );
    }

    // cập nhật mật khẩu mới
    DB::table('taikhoan')
        ->where('TaiKhoanID', session('user_id'))
        ->update([

            'MatKhau' => $request->new_password

        ]);

    return redirect('/profile')
        ->with('success', 'Đổi mật khẩu thành công');
});







/* ================= ORDER: HISTORY + TRACKING ================= */

Route::get('/orders-history', [OrderController::class, 'history'])->name('orders.history.index');

Route::get('/orders-history/{id}', [OrderController::class, 'orderHistory'])->name('orders.history.show');

Route::get('/orders-tracking', [OrderController::class, 'tracking'])->name('orders.tracking.index');

Route::get('/orders-tracking/{id}', [OrderController::class, 'orderTracking'])->name('orders.tracking.show');

/* ================= ADMIN: DANH MỤC SẢN PHẨM ================= */
Route::get('/admin/category/add', [CategoryController::class, 'create']);
Route::post('/admin/category/store', [CategoryController::class, 'store']);
Route::get('/admin/category', [CategoryController::class, 'index']);
Route::get('/admin/category/edit/{id}', [CategoryController::class, 'edit']);
Route::post('/admin/category/update/{id}', [CategoryController::class, 'update']);
Route::get('/admin/category/delete/{id}', [CategoryController::class, 'delete']);

// ================= ADMIN: QUẢN LÝ SẢN PHẨM =================
Route::get('/admin/product', [ProductController::class, 'index']);
Route::get('/admin/product/add', [ProductController::class, 'create']);
Route::post('/admin/product/store', [ProductController::class, 'store']);
Route::get('/admin/product/edit/{id}', [ProductController::class, 'edit']);
Route::post('/admin/product/update/{id}', [ProductController::class, 'update']);
Route::get('/admin/product/delete/{id}', [ProductController::class, 'delete']);


/* ================= ADMIN: QUẢN LÝ ĐƠN HÀNG ================= */
Route::get('/admin/order', [OrderController::class, 'adminIndex'])->name('admin.order.index');
Route::get('/admin/order/detail/{id}', [OrderController::class, 'adminShow'])->name('admin.order.show');
Route::post('/admin/order/update-status/{id}', [OrderController::class, 'adminUpdateStatus'])->name('admin.order.update-status');

/* ================= ADMIN: BÁO CÁO THỐNG KÊ ================= */
Route::get('/admin/statistic', [OrderController::class, 'adminReport'])->name('admin.report');
