<style>
.main-sidebar {
    height: 100vh;
    overflow-y: auto;
}

#sidebar-wrapper {
    height: 100%;
    overflow-y: auto;
}

/* Fix header spacing (keep it clean, not too tight) */
.sidebar-menu .menu-header {
    margin: 10px 0 5px;
    padding: 5px 15px;
    font-size: 12px;
}

/* Fix menu item alignment */
.sidebar-menu li a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 15px;
}

/* Prevent text from breaking into two lines */
.sidebar-menu li a span {
    white-space: nowrap;
}

/* Fix icon + text spacing */
.sidebar-menu li a i {
    margin-right: 10px;
}

/* Submenu spacing (keep minimal but safe) */
.submenu-nav {
    padding-left: 15px;
}
</style>
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

            <!-- ========== ADMIN SETTINGS DROPDOWN ========== -->
            <li class="menu-header">{{ __('Admin Settings') }}</li>
            
            <li class="submenu">
                <a href="#" class="nav-link">
                    <i class="fas fa-user-shield"></i>
                    <span>{{ __('Admin Settings') }}</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="submenu-nav">
                    @can('role.view')
                        <li class="{{ isRoute('admin.role.index', 'active') }}">
                            <a class="nav-link" href="{{ route('admin.role.index') }}">
                                <i class="fas fa-shield-alt"></i>
                                <span>{{ __('Manage Roles') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('role.assign')
                        <li class="{{ isRoute('assign.permissions.form', 'active') }}">
                            <a class="nav-link" href="{{ route('assign.permissions.form') }}">
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

                    @can('admin.create')
                        <li class="{{ isRoute('admin.admin.index', 'active') }}">
                            <a class="nav-link" href="{{ route('admin.admin.index') }}">
                                <i class="fas fa-user-plus"></i>
                                <span>{{ __('Add Sub Admin') }}</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>

            <!-- ========== STAFF MANAGEMENT DROPDOWN ========== -->
            <li class="menu-header">{{ __('Staff Management') }}</li>
            
            <li class="submenu">
                <a href="#" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>{{ __('Staff Management') }}</span>
                    <i class="fas fa-angle-left"></i>
                </a>
                <ul class="submenu-nav">
                    @can('staff.view')
                        <li class="{{ isRoute('admin.staff.index', 'active') }}">
                            <a class="nav-link" href="{{ route('admin.staff.index') }}">
                                <i class="fas fa-list"></i>
                                <span>{{ __('Staff List') }}</span>
                            </a>
                        </li>
                    @endcan

                    @can('staff.create')
                        <li>
                            <a class="nav-link" href="{{ route('admin.staff.create') }}">
                                <i class="fas fa-plus"></i>
                                <span>{{ __('Add Staff') }}</span>
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
                </ul>
            </li>

            <!-- ========== SHOP MANAGEMENT DROPDOWN ========== -->
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
                </ul>
            </li>

            <!-- ========== TASKS & JOBS DROPDOWN ========== -->

            <!-- ========== SETTINGS DROPDOWN ========== -->
          
        </ul>
    </aside>
</div>