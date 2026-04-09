<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('staff.dashboard') }}"><img class="w-75" src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('staff.dashboard') }}"><img src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            <!-- Dashboard -->
            <li class="{{ isRoute('staff.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('staff.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <!-- Shop Management Dropdown (Loy Madok Style) -->
            <li class="submenu {{ isRoute('staff.shop.*', 'active') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-store"></i>
                    <span>{{ __('Shop Management') }}</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="submenu-nav">
                    <li class="{{ isRoute('staff.shop.index', 'active') }}">
                        <a class="nav-link" href="{{ route('staff.shop.index') }}">
                            <i class="fas fa-list"></i>
                            <span>{{ __('Shop List') }}</span>
                        </a>
                    </li>
                    <li class="{{ isRoute('staff.shop.create', 'active') }}">
                        <a class="nav-link" href="{{ route('staff.shop.create') }}">
                            <i class="fas fa-plus"></i>
                            <span>{{ __('Add Shop') }}</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </aside>
</div>