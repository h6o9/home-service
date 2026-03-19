<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('staff.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('staff.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            <li class="{{ isRoute('staff.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('staff.dashboard') }}"><i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
        </ul>
    </aside>
</div>
