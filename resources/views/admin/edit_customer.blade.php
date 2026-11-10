@extends('admin.dashboard')

@section('content')

<div class="content">

    <h1 class="customer-title">

        Chỉnh sửa khách hàng

    </h1>

    <form action="/admin/customer/update/{{ $customer->KhachHangID }}"
          method="POST"
          class="add-form">

        @csrf

        <!-- HỌ TÊN -->

        <div class="form-group">

            <label>Họ tên *</label>

            <input type="text"
                   name="HoTen"

                   value="{{ old('HoTen', $customer->HoTen) }}">

            @error('HoTen')

                <div class="error-text">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <!-- NGÀY SINH -->

        <div class="form-group">

            <label>Ngày sinh *</label>

            <input type="date"
                   name="NgaySinh"

                   value="{{ old('NgaySinh', $customer->NgaySinh) }}">

            @error('NgaySinh')

                <div class="error-text">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <!-- SDT -->

        <div class="form-group">

            <label>Số điện thoại *</label>

            <input type="text"
                   name="SoDienThoai"

                   value="{{ old('SoDienThoai', $customer->SoDienThoai) }}">

            @error('SoDienThoai')

                <div class="error-text">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <!-- ĐỊA CHỈ -->

        <div class="form-group">

            <label>Địa chỉ</label>

            <input type="text"
                   name="DiaChi"

                   value="{{ old('DiaChi', $customer->DiaChi) }}">

        </div>

        <!-- EMAIL -->

        <div class="form-group">

            <label>Email *</label>

            <input type="text"
                   name="Email"

                   value="{{ old('Email', $customer->Email) }}">

            @error('Email')

                <div class="error-text">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <button type="submit"
                class="save-btn">

            Cập nhật khách hàng

        </button>

    </form>

</div>

@endsection