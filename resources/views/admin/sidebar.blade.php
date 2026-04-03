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
            @adminCan('dashboard.view')
                <li class="{{ isRoute('admin.dashboard', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endadminCan

			      @if (checkAdminHasPermission('setting.view') ||
                    checkAdminHasPermission('basic.payment.view') ||
                    checkAdminHasPermission('payment.view') ||
                    checkAdminHasPermission('currency.view') ||
                    checkAdminHasPermission('tax.view') ||
                    checkAdminHasPermission('addon.view') ||
                    checkAdminHasPermission('language.view') ||
                    checkAdminHasPermission('role.view') ||
                    checkAdminHasPermission('admin.view') ||
                    checkAdminHasPermission('clubpoint.management'))
                <li class="menu-header">{{ __('Settings') }}</li>

                @if (Module::isEnabled('GlobalSetting'))
                    <li class="{{ isRoute('admin.settings', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endif

            @endif
          	@if (auth()->guard('admin')->check())
                <li class="menu-header">{{ __('Manage Staff') }}</li>
                <li class="{{ isRoute('admin.staff.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.staff.index') }}"><i class="fas fa-user-friends"></i>
                        <span>{{ __('Staff') }}</span>
                    </a>
                </li>
                <li class="{{ isRoute('admin.staff-permissions.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.staff-permissions.index') }}"><i class="fas fa-shield-alt"></i>
                        <span>{{ __('Staff Permissions') }}</span>
                    </a>
                </li>
                <li class="menu-header">{{ __('Shop Management') }}</li>
                <li class="{{ isRoute('admin.shop-management.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.shop-management.index') }}"><i class="fas fa-store"></i>
                        <span>{{ __('Shop Management') }}</span>
                    </a>
                </li>
            @endif
	
       </aside>
</div>
