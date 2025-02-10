<div id="scrollbar">
    <div class="container-fluid">
        <div id="two-column-menu">
        </div>

        <ul class="navbar-nav" id="navbar-nav">
            <li class="nav-item">
                <a class="nav-link menu-link {{ Request::RouteIs('lecturer.index') ? 'active' : '' }}"
                    href="{{ route('lecturer.index') }}">
                    <i class="ri-dashboard-2-line"></i> <span>Nguyen Van Doan</span>
                </a>
            </li>
        </ul>
    </div>
</div>