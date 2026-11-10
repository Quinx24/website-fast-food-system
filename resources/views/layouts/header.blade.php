<div class="header">

    <!-- LEFT: LOGO + SEARCH -->
    <div class="header-left">

        <a href="/">
            <div class="logo">FUDDI</div>
        </a>

        <!-- SEARCH -->
        <form action="{{ route('search') }}" method="GET">
            <div class="search-box">
                <input type="text" name="keyword" placeholder="Search...">

                <button type="submit" style="background:none;border:none;padding:0;">
                    <img src="{{ asset('images/search_icon.png') }}" class="search-icon">
                </button>
            </div>
        </form>

    </div>

    <!-- RIGHT: MENU + ICON -->
    <div class="header-right">

        <div class="nav">
            <a href="/">Trang chủ</a>
            <a href="/#menu-section">Thực đơn</a>
            <a href="/about">Về Fuddi</a>
        </div>

        <div class="header-icons">
            <a href="/cart"
                style="position:relative; display:inline-block;">

                    <img src="{{ asset('images/bag_icon.png') }}" class="bag">

                    @if($cartCount > 0)
                        <span style="
                            position:absolute;
                            top:-6px;
                            right:-6px;
                            background:#ffa629;
                            color:white;
                            border-radius:50%;
                            font-size:12px;
                            padding:2px 6px;
                        ">
                            {{ $cartCount }}
                        </span>
                    @endif

            </a>

            @if(session('user_id'))

        <a href="/profile">
        <img src="{{ asset('images/avatar_icon.png') }}" class="avatar">
        </a>
        @else

            <a href="{{ route('login') }}">
                <img src="{{ asset('images/avatar_icon.png') }}" class="avatar">
            </a>

        @endif
        </div>

    </div>
</div>