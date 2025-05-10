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
                <a class="nav-link {{ Request::is('operator/v1/runQuery', 'operator/v1/runQuery/*') ? '' : 'collapsed' }}"
                    href="{{ url('operator/v1/runQuery') }}">
                    <i
                        class="bi {{ Request::is('operator/v1/runQuery', 'operator/v1/runQuery/*') ? 'bi-database-fill' : 'bi-database' }}"></i>
                    <span>Run Query</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('operator/v1/manageDatabase', 'operator/v1/manageDatabase/*') ? '' : 'collapsed' }}"
                    href="{{ url('operator/v1/manageDatabase') }}">
                    <i
                        class="bi {{ Request::is('operator/v1/manageDatabase', 'operator/v1/manageDatabase/*') ? 'bi-database-fill-add' : 'bi-database-add' }}"></i>
                    <span>Manage Database</span>
                </a>
            </li>
        @elseif (strtolower(Auth::user()->role) == 'checker')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('checker/v1/approveQuery', 'checker/v1/approveQuery/*') ? '' : 'collapsed' }}"
                    href="{{ url('checker/v1/approveQuery') }}">
                    <i
                        class="bi {{ Request::is('checker/v1/approveQuery', 'checker/v1/approveQuery/*') ? 'bi-database-fill' : 'bi-database' }}"></i>
                    <span>Query Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('checker/v1/approveDatabase', 'checker/v1/approveDatabase/*') ? '' : 'collapsed' }}"
                    href="{{ url('checker/v1/approveDatabase') }}">
                    <i
                        class="bi {{ Request::is('checker/v1/approveDatabase', 'checker/v1/approveDatabase/*') ? 'bi-database-fill-add' : 'bi-database-add' }}"></i>
                    <span>Database Approval</span>
                </a>
            </li>
        @elseif (strtolower(Auth::user()->role) == 'supervisor')
            <li class="nav-item">
                <a class="nav-link {{ Request::is('supervisor/v1/approveQuery', 'supervisor/v1/approveQuery/*') ? '' : 'collapsed' }}"
                    href="{{ url('supervisor/v1/approveQuery') }}">
                    <i
                        class="bi {{ Request::is('supervisor/v1/approveQuery', 'supervisor/v1/approveQuery/*') ? 'bi-database-fill' : 'bi-database' }}"></i>
                    <span>Query Approval</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('supervisor/v1/approveDatabase', 'supervisor/v1/approveDatabase/*') ? '' : 'collapsed' }}"
                    href="{{ url('supervisor/v1/approveDatabase') }}">
                    <i
                        class="bi {{ Request::is('supervisor/v1/approveDatabase', 'supervisor/v1/approveDatabase/*') ? 'bi-database-fill-add' : 'bi-database-add' }}"></i>
                    <span>Database Approval</span>
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
