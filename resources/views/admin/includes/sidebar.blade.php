<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">

        {{-- <li class="nav-item">
            <a class="nav-link {{ Request::is('admin/v1') ? '' : 'collapsed' }}" href="{{ url('admin/v1') }}">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li> --}}
        @if (strtolower(Auth::user()->role) === 'operator')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('viewQuery') ? '' : 'collapsed' }}"
                    href="{{ route('viewQuery') }}">
                    <i
                        class="bi {{ request()->routeIs('viewQuery') ? 'bi-file-text-fill' : 'bi-file-text' }}"></i>
                    <span>Run Query</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('viewDatabase') ? '' : 'collapsed' }}"
                    href="{{ route('viewDatabase') }}">
                    <i
                        class="bi {{ request()->routeIs('viewDatabase') ? 'bi-collection-fill' : 'bi-collection' }}"></i>
                    <span>Manage Database</span>
                </a>
            </li>
        @elseif (strtolower(Auth::user()->role) == 'checker')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('chkViewQuery') ? '' : 'collapsed' }}"
                    href="{{ route('chkViewQuery') }}">
                    <i
                        class="bi {{ request()->routeIs('chkViewQuery') ? 'bi-file-check-fill' : 'bi-file-check' }}"></i>
                    <span>Query Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('chkViewDatabase') ? '' : 'collapsed' }}"
                    href="{{ route('chkApproveDatabase') }}">
                    <i
                        class="bi {{ request()->routeIs('chkViewDatabase') ? 'bi-collection-fill' : 'bi-collection' }}"></i>
                    <span>Database Approval</span>
                </a>
            </li>
        @elseif (strtolower(Auth::user()->role) == 'supervisor')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spvViewQuery') ? '' : 'collapsed' }}"
                    href="{{ route('spvViewQuery') }}">
                    <i
                        class="bi {{ request()->routeIs('spvViewQuery') ? 'bi-file-check-fill' : 'bi-file-check' }}"></i>
                    <span>Query Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spvViewDatabase') ? '' : 'collapsed' }}"
                    href="{{ route('spvApproveDatabase') }}">
                    <i
                        class="bi {{ request()->routeIs('spvViewDatabase') ? 'bi-collection-fill' : 'bi-collection' }}"></i>
                    <span>Database Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('spvViewLogActivity') ? '' : 'collapsed' }}"
                    href="{{ route('spvViewLogActivity') }}">
                    <i
                        class="bi {{ request()->routeIs('spvViewLogActivity') ? 'bi-book-fill' : 'bi-book' }}"></i>
                    <span>Log Activity</span>
                </a>
            </li>
        @elseif (strtolower(Auth::user()->role) == 'superadmin')
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('viewUser') ? '' : 'collapsed' }}"
                    href="{{ route('viewUser') }}">
                    <i
                        class="bi {{ request()->routeIs('viewUser') ? 'bi-person-badge-fill' : 'bi-person-badge' }}"></i>
                    <span>Manage User</span>
                </a>
            </li>
        @else
            <span>Invalid role</span>
        @endif
        @auth
            {{-- <li class="nav-item">
                <form id="logout-form" action="{{ url('admin/v1/logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <a class="nav-link collapsed" href="#"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-in-left"></i>
                    <span>Sign Out</span>
                </a>
            </li> --}}
        @else
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ url('admin/v1/login') }}">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Login</span>
                </a>
            </li>
        @endauth

    </ul>
</aside><!-- End Sidebar-->
