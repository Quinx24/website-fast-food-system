@extends('admin.dashboard')

@section('content')



@if(session('error'))

<script>

    alert("{{ session('error') }}");

</script>

@endif

<div class="content">

    <!-- SEARCH -->

    <form action="/admin/customer"
      method="GET"
      class="search-box">

    <input type="text"
           name="search"

           value="{{ $search ?? '' }}"

           placeholder="Tìm kiếm khách hàng">

    <button type="submit"
            class="search-btn">

        <img src="{{ asset('images/search_icon.png') }}">

    </button>

</form>

    <!-- TITLE -->
    @if(session('success'))

        <script>

        alert("{{ session('success') }}");

        </script>

    @endif

    <h1 class="customer-title">

        Quản lý khách hàng

    </h1>

    <!-- BUTTON -->

    <a href="/admin/customer/add"
       class="add-btn">

        + Thêm khách hàng

    </a>

    <!-- TABLE -->

    <div class="table-box">

        <table class="customer-table">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Họ tên</th>

                    <th>Ngày sinh</th>

                    <th>Email</th>

                    <th>Số điện thoại</th>

                    <th>Địa chỉ</th>

                    <th>Ngày tạo</th>

                    <th>Hành động</th>

                </tr>

            </thead>

            <tbody>

                @if(count($customers) > 0)

                @foreach($customers as $customer)

                <tr>

                    <td>
                        {{ $customer->KhachHangID }}
                    </td>

                    <td>
                        {{ $customer->HoTen }}
                    </td>

                    <td>
                        {{ $customer->NgaySinh }}
                    </td>

                    <td>
                        {{ $customer->Email }}
                    </td>

                    <td>
                        {{ $customer->SoDienThoai }}
                    </td>

                    <td>
                        {{ $customer->DiaChi }}
                    </td>

                    <td>
                        {{ $customer->NgayTao }}
                    </td>

                    <td>

                        <!-- SỬA -->

                        <a href="/admin/customer/edit/{{ $customer->KhachHangID }}"
                           class="edit-btn">

                            Sửa

                        </a>

                        <!-- XÓA -->

                        <a href="/admin/customer/delete/{{ $customer->KhachHangID }}"
                            class="delete-btn"

                            onclick="return confirm('Bạn có muốn xóa khách hàng này không?')">

                             Xóa

                        </a>
                    </td>

                </tr>

                    @endforeach

                @else

                <tr>

                    <td colspan="8"
                        class="empty-text">

                        Không có khách hàng phù hợp

                        </td>

                </tr>

@endif

            </tbody>

        </table>

    </div>

</div>

@endsection