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
            <li class="{{ isRoute('staff.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('staff.dashboard') }}"><i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
			<li class="{{ isRoute('staff.shop.index', 'active') }}">
				<a class="nav-link" href="{{ route('staff.shop.index') }}"><i class="fas fa-store"></i>
					<span>{{ __('Manage Shops') }}</span>
				</a>
			
        </ul>
    </aside>
</div>
