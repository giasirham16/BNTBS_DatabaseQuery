<!-- ======= Header ======= -->
<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        @if (strtolower(Auth::user()->role) === 'operator')
            <a href="{{ url('operator/v1/runQuery') }}" class="logo d-flex align-items-center">
                <img src="{{ url('admin/assets/img/logobankntb.png') }}" alt="logo" class="logo-img" />
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        @elseif (strtolower(Auth::user()->role) === 'checker')
            <a href="{{ url('operator/v1/runQuery') }}" class="logo d-flex align-items-center">
                <img src="{{ url('admin/assets/img/logobankntb.png') }}" alt="logo" class="logo-img" />
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        @elseif (strtolower(Auth::user()->role) === 'supervisor')
            <a href="{{ url('operator/v1/runQuery') }}" class="logo d-flex align-items-center">
                <img src="{{ url('admin/assets/img/logobankntb.png') }}" alt="logo" class="logo-img" />
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        @else
            <a href="{{ url('admin/v1/login') }}" class="logo d-flex align-items-center">
                <img src="{{ url('admin/assets/img/logobankntb.png') }}" alt="logo" class="logo-img" />
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        @endif

    </div>


    <nav class="ms-auto d-flex align-items-center me-3 header-nav">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item dropdown">
                <a class="nav-link nav-profile d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                    <span class="d-sm-block dropdown-toggle fs-6">Bank NTB Syariah</span>
                </a><!-- End Profile Image Icon -->
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>Bank NTB Syariah</h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    {{-- <li>
                        <a class="dropdown-item" href="{{ route('pengguna.ubahPassword') }}">
                            <i class="bi bi-key"></i> Ubah Password
                        </a>
                    </li> --}}
                    <li>
                        <form id="logout-form" action="{{ url('admin/v1/logout') }}" method="POST"
                            style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-in-left"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->
        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->
