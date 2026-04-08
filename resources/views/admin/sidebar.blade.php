<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"><img class="w-75" src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            <!-- Dashboard Section -->
            <li class="menu-header">{{ __('Dashboard') }}</li>
            @can('dashboard.view')
                <li class="{{ isRoute('admin.dashboard', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endcan

            <!-- Admin Settings Section -->
            <li class="menu-header">{{ __('Admin Settings') }}</li>
            
            @can('role.view')
                <li class="{{ isRoute('admin.role.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.role.index') }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Manage Roles') }}</span>
                    </a>
                </li>
            @endcan

            @can('role.assign')
                <li class="{{ isRoute('admin.role.assign', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.role.assign') }}">
                        <i class="fas fa-key"></i>
                        <span>{{ __('Assign Permissions') }}</span>
                    </a>
                </li>
            @endcan

            @can('role.assign')
                <li class="{{ isRoute('admin.assign-roles.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.assign-roles.index') }}">
                        <i class="fas fa-user-tag"></i>
                        <span>{{ __('Assign Roles') }}</span>
                    </a>
                </li>
            @endcan

            @can('activity.logs.view')
                <li class="{{ isRoute('admin.activity-logs.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.activity-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>{{ __('Activity Logs') }}</span>
                    </a>
                </li>
            @endcan

            @if (Module::isEnabled('GlobalSetting'))
                @can('setting.view')
                    <li class="{{ isRoute('admin.settings', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.settings') }}">
                            <i class="fas fa-cogs"></i>
                            <span>{{ __('System Settings') }}</span>
                        </a>
                    </li>
                @endcan
            @endif

            <!-- Staff Management Section -->
            <li class="menu-header">{{ __('Staff Management') }}</li>
            
            @can('staff.view')
                <li class="{{ isRoute('admin.staff.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.staff.index') }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('Staff List') }}</span>
                    </a>
                </li>
            @endcan

            @can('staff.permission.view')
                <li class="{{ isRoute('admin.staff-permissions.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.staff-permissions.index') }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Staff Permissions') }}</span>
                    </a>
                </li>
            @endcan

            <!-- Shop Management Section -->
            <li class="menu-header">{{ __('Shop Management') }}</li>
            
            <li class="submenu">
                <a href="#" class="nav-link">
                    <i class="fas fa-store"></i>
                    <span>{{ __('Shop Management') }}</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="submenu-nav">
                    @can('shop.view')
                        <li class="{{ isRoute('admin.shop-management.index', 'active') }}">
                            <a class="nav-link" href="{{ route('admin.shop-management.index') }}">
                                <i class="fas fa-list"></i>
                                <span>{{ __('Shop List') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('shop.create')
                        <li>
                            <a class="nav-link" href="#">
                                <i class="fas fa-plus"></i>
                                <span>{{ __('Add Shop') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

        </ul>
    </aside>
</div>
