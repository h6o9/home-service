<style>
.main-sidebar {
    height: 100vh;
    overflow-y: auto;
}

#sidebar-wrapper {
    height: 100%;
    overflow-y: auto;
}

.sidebar-menu .menu-header {
    margin: 10px 0 5px;
    padding: 5px 15px;
    font-size: 12px;
}

.sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
}

.sidebar-menu li a span {
    white-space: nowrap;
}

.sidebar-menu li a i {
    margin-right: 10px;
}

/* tree style */
.sidebar-menu li {
    padding-left: 20px;
    position: relative;
}

.sidebar-menu li::before {
    content: "│";
    position: absolute;
    left: 5px;
    color: #ccc;
}
</style>

<div class="main-sidebar">
    <aside id="sidebar-wrapper">

        <!-- Logo -->
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}">
                <img class="w-75" src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}">
            </a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}">
            </a>
        </div>

        @php
            $admin = auth('admin')->user();
        @endphp

        <ul class="sidebar-menu">

            <!-- DASHBOARD -->
            <li class="menu-header">{{ __('Dashboard') }}</li>
            <li class="{{ isRoute('admin.dashboard', 'active') }}">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <!-- ADMIN SETTINGS -->
            @if(
                $admin->can('role.view') ||
                $admin->can('role.assign') ||
                $admin->can('activity.logs.view') ||
                $admin->can('admin.view')
            )
                <li class="menu-header">{{ __('Admin Settings') }}</li>

                @can('role.view')
                <li class="{{ isRoute('admin.role.index', 'active') }}">
                    <a href="{{ route('admin.role.index') }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Manage Roles') }}</span>
                    </a>
                </li>
                @endcan

                @can('role.assign')
                <li class="{{ isRoute('assign.permissions.form', 'active') }}">
                    <a href="{{ route('assign.permissions.form') }}">
                        <i class="fas fa-key"></i>
                        <span>{{ __('Assign Permissions') }}</span>
                    </a>
                </li>
                @endcan

                @can('role.assign')
                <li class="{{ isRoute('admin.assign-roles.index', 'active') }}">
                    <a href="{{ route('admin.assign-roles.index') }}">
                        <i class="fas fa-user-tag"></i>
                        <span>{{ __('Assign Roles') }}</span>
                    </a>
                </li>
                @endcan

                @can('activity.logs.view')
                <li class="{{ isRoute('admin.activity-logs.index', 'active') }}">
                    <a href="{{ route('admin.activity-logs.index') }}">
                        <i class="fas fa-history"></i>
                        <span>{{ __('Activity Logs') }}</span>
                    </a>
                </li>
                @endcan

                @can('admin.view')
                <li class="{{ isRoute('admin.admin.index', 'active') }}">
                    <a href="{{ route('admin.admin.index') }}">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('Add Sub Admin') }}</span>
                    </a>
                </li>
                @endcan
            @endif


            <!-- STAFF MANAGEMENT -->
            @if(
                $admin->can('staff.view') ||
                $admin->can('staff.create') ||
                $admin->can('staff.permission.view')
            )
                <li class="menu-header">{{ __('Staff Management') }}</li>

                @can('staff.view')
                <li class="{{ isRoute('admin.staff.index', 'active') }}">
                    <a href="{{ route('admin.staff.index') }}">
                        <i class="fas fa-users"></i>
                        <span>{{ __('Staff List') }}</span>
                    </a>
                </li>
                @endcan

                @can('staff.create')
                <li>
                    <a href="{{ route('admin.staff.create') }}">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('Add Staff') }}</span>
                    </a>
                </li>
                @endcan

                @can('staff.permission.view')
                <li class="{{ isRoute('admin.staff-permissions.index', 'active') }}">
                    <a href="{{ route('admin.staff-permissions.index') }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>{{ __('Staff Permissions') }}</span>
                    </a>
                </li>
                @endcan
            @endif


            <!-- SHOP MANAGEMENT -->
            @if(
                $admin->can('shop.view') ||
                $admin->can('shop.create')
            )
                <li class="menu-header">{{ __('Shop Management') }}</li>

                @can('shop.view')
                <li class="{{ isRoute('admin.shop-management.index', 'active') }}">
                    <a href="{{ route('admin.shop-management.index') }}">
                        <i class="fas fa-store"></i>
                        <span>{{ __('Shop List') }}</span>
                    </a>
                </li>
                @endcan
            @endif

        </ul>
    </aside>
</div>