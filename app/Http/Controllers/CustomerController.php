<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    // ================= DANH SÁCH KHÁCH HÀNG =================

    public function index(Request $request)
{

    $search = $request->search;

    $customers = DB::table('khachhang')

        ->where('HoTen', 'like', '%' . $search . '%')

        ->orWhere('Email', 'like', '%' . $search . '%')

        ->orWhere('SoDienThoai', 'like', '%' . $search . '%')

        ->get();

    return view('admin.customer',

        compact('customers', 'search'));

}

    // ================= TRANG THÊM =================

    public function create()
    {

        return view('admin.add_customer');

    }


    // ================= LƯU KHÁCH HÀNG =================

    public function store(Request $request)
    {

        $request->validate([

            // HỌ TÊN

            'HoTen' => [

                'required',

                'regex:/^[\pL\s]+$/u'

            ],

            // NGÀY SINH

            'NgaySinh' => 'required',

            // SỐ ĐIỆN THOẠI

            'SoDienThoai' => [

                'required',

                'digits:10'

            ],

            // EMAIL

            'Email' => [

                'required',

                'email'

            ],

        ], [

            // ERROR MESSAGE

            'HoTen.required' => 'Vui lòng nhập họ tên',

            'HoTen.regex' => 'Họ tên không được chứa số',

            'NgaySinh.required' => 'Vui lòng nhập ngày sinh',

            'SoDienThoai.required' => 'Vui lòng nhập số điện thoại',

            'SoDienThoai.digits' => 'Số điện thoại phải đủ 10 số',

            'Email.required' => 'Vui lòng nhập email',

            'Email.email' => 'Email không đúng định dạng',

        ]);


        // INSERT DATABASE

        DB::table('khachhang')->insert([

            'HoTen' => $request->HoTen,

            'NgaySinh' => $request->NgaySinh,

            'SoDienThoai' => $request->SoDienThoai,

            'DiaChi' => $request->DiaChi,

            'Email' => $request->Email,

            'NgayTao' => now(),

        ]);


        // ALERT SUCCESS

        return redirect('/admin/customer')

            ->with('success', 'Đã thêm khách hàng thành công!');

    }







   public function delete($id)
{

    // LẤY KHÁCH HÀNG

    $customer = DB::table('khachhang')

        ->where('KhachHangID', $id)

        ->first();


    // KIỂM TRA TÀI KHOẢN

    if($customer->TaiKhoanID != null)
    {

        return redirect('/admin/customer')

            ->with('error',

            'Không thể xóa khách hàng này!');

    }


    // KIỂM TRA ĐƠN HÀNG

    $order = DB::table('donhang')

        ->where('KhachHangID', $id)

        ->exists();


    // NẾU CÓ ĐƠN HÀNG

    if($order)
    {

        return redirect('/admin/customer')

            ->with('error',

            'Không thể xóa khách hàng này!');

    }


    // XÓA

    DB::table('khachhang')

        ->where('KhachHangID', $id)

        ->delete();


    return redirect('/admin/customer')

        ->with('success',

        'Đã xóa khách hàng thành công!');

    }





    public function edit($id)
{

    $customer = DB::table('khachhang')

        ->where('KhachHangID', $id)

        ->first();

    return view('admin.edit_customer',

        compact('customer'));

}



public function update(Request $request, $id)
{

    $request->validate([

        'HoTen' => [

            'required',

            'regex:/^[\pL\s]+$/u'

        ],

        'NgaySinh' => 'required',

        'SoDienThoai' => [

            'required',

            'digits:10'

        ],

        'Email' => [

            'required',

            'email'

        ],

    ], [

        'HoTen.required' => 'Vui lòng nhập họ tên',

        'HoTen.regex' => 'Họ tên không được chứa số',

        'NgaySinh.required' => 'Vui lòng nhập ngày sinh',

        'SoDienThoai.required' => 'Vui lòng nhập số điện thoại',

        'SoDienThoai.digits' => 'Số điện thoại phải đủ 10 số',

        'Email.required' => 'Vui lòng nhập email',

        'Email.email' => 'Email không đúng định dạng',

    ]);


    DB::table('khachhang')

        ->where('KhachHangID', $id)

        ->update([

            'HoTen' => $request->HoTen,

            'NgaySinh' => $request->NgaySinh,

            'SoDienThoai' => $request->SoDienThoai,

            'DiaChi' => $request->DiaChi,

            'Email' => $request->Email,

        ]);


    return redirect('/admin/customer')

        ->with('success',

        'Đã cập nhật khách hàng thành công!');

}









}