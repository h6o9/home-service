<style>
/* Optional same styling (agar pehle se hai to repeat na karo) */
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
            <a href="{{ route('staff.dashboard') }}">
                <img class="w-75" src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}">
            </a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('staff.dashboard') }}">
                <img src="{{ asset('public/backend/img/admin-auth-bg.jpg') }}">
            </a>
        </div>

        <ul class="sidebar-menu">

            <!-- Dashboard -->
            <li class="{{ isRoute('staff.dashboard', 'active') }}">
                <a href="{{ route('staff.dashboard') }}">
                    <i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>

            <!-- ================= SHOP MANAGEMENT ================= -->
            <li class="menu-header">{{ __('Shop Management') }}</li>

            <li class="{{ isRoute('staff.shop.index', 'active') }}">
                <a href="{{ route('staff.shop.index') }}">
                    <i class="fas fa-list"></i>
                    <span>{{ __('Shop List') }}</span>
                </a>
            </li>

            <li class="{{ isRoute('staff.shop.create', 'active') }}">
                <a href="{{ route('staff.shop.create') }}">
                    <i class="fas fa-plus"></i>
                    <span>{{ __('Add Shop') }}</span>
                </a>
            </li>

        </ul>
    </aside>
</div>