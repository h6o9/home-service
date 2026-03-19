@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Role') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Roles') => route('admin.role.index'),
                __('Create Role') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Create Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form role="form" action="{{ route('admin.role.store') }}" method="POST">
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">{{ __('Role Name') }}</label>
                                                    <input name="name" type="text"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        id="role_name" placeholder="{{ __('Enter role name') }}">
                                                    @error('name')
                                                        <span class="invalid-feedback"
                                                            role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="permission"
                                                        class="form-label">{{ __('Permissions') }}</label>
                                                    <div class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox" id="permission_all"
                                                            value="1">
                                                        <label for="permission_all"
                                                            class="form-check-label permission_all">{{ __('All Permissions') }}</label>
                                                    </div>
                                                    <hr>
                                                    <div class="admin_role_border">
                                                        <div class="row">
                                                            <!-- Dashboard Permissions -->
                                                            <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="1management"
                                                                                onclick="CheckPermissionByGroup('role-1-management-checkbox', this)"
                                                                                value="1">
                                                                            <label for="1management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Dashboard') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-1-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_dashboard_view"
                                                                                value="dashboard.view"
                                                                                data-role-id="1">
                                                                            <label for="permission_dashboard_view"
                                                                                class="form-check-label">{{ __('View Dashboard') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Manage Staff Permissions -->
                                                            <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="2management"
                                                                                onclick="CheckPermissionByGroup('role-2-management-checkbox', this)"
                                                                                value="2">
                                                                            <label for="2management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Staff') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-2-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_staff_view"
                                                                                value="staff.view"
                                                                                data-role-id="2">
                                                                            <label for="permission_staff_view"
                                                                                class="form-check-label">{{ __('View Staff') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_staff_create"
                                                                                value="staff.create"
                                                                                data-role-id="2">
                                                                            <label for="permission_staff_create"
                                                                                class="form-check-label">{{ __('Create Staff') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_staff_edit"
                                                                                value="staff.edit"
                                                                                data-role-id="2">
                                                                            <label for="permission_staff_edit"
                                                                                class="form-check-label">{{ __('Edit Staff') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_staff_delete"
                                                                                value="staff.delete"
                                                                                data-role-id="2">
                                                                            <label for="permission_staff_delete"
                                                                                class="form-check-label">{{ __('Delete Staff') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Manage Roles Permissions -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="3management"
                                                                                onclick="CheckPermissionByGroup('role-3-management-checkbox', this)"
                                                                                value="3">
                                                                            <label for="3management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Roles') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-3-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_role_view"
                                                                                value="role.view"
                                                                                data-role-id="3">
                                                                            <label for="permission_role_view"
                                                                                class="form-check-label">{{ __('View Roles') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_role_create"
                                                                                value="role.create"
                                                                                data-role-id="3">
                                                                            <label for="permission_role_create"
                                                                                class="form-check-label">{{ __('Create Role') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_role_edit"
                                                                                value="role.edit"
                                                                                data-role-id="3">
                                                                            <label for="permission_role_edit"
                                                                                class="form-check-label">{{ __('Edit Role') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_role_delete"
                                                                                value="role.delete"
                                                                                data-role-id="3">
                                                                            <label for="permission_role_delete"
                                                                                class="form-check-label">{{ __('Delete Role') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Manage Shops Permissions -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="4management"
                                                                                onclick="CheckPermissionByGroup('role-4-management-checkbox', this)"
                                                                                value="4">
                                                                            <label for="4management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Shops') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-4-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_shop_view"
                                                                                value="shop.view"
                                                                                data-role-id="4">
                                                                            <label for="permission_shop_view"
                                                                                class="form-check-label">{{ __('View Shops') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_shop_create"
                                                                                value="shop.create"
                                                                                data-role-id="4">
                                                                            <label for="permission_shop_create"
                                                                                class="form-check-label">{{ __('Create Shop') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_shop_edit"
                                                                                value="shop.edit"
                                                                                data-role-id="4">
                                                                            <label for="permission_shop_edit"
                                                                                class="form-check-label">{{ __('Edit Shop') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_shop_delete"
                                                                                value="shop.delete"
                                                                                data-role-id="4">
                                                                            <label for="permission_shop_delete"
                                                                                class="form-check-label">{{ __('Delete Shop') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Manage Settings Permissions -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="5management"
                                                                                onclick="CheckPermissionByGroup('role-5-management-checkbox', this)"
                                                                                value="5">
                                                                            <label for="5management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Settings') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-5-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_settings_view"
                                                                                value="settings.view"
                                                                                data-role-id="5">
                                                                            <label for="permission_settings_view"
                                                                                class="form-check-label">{{ __('View Settings') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_settings_edit"
                                                                                value="settings.edit"
                                                                                data-role-id="5">
                                                                            <label for="permission_settings_edit"
                                                                                class="form-check-label">{{ __('Edit Settings') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Visit History Permissions -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                id="6management"
                                                                                onclick="CheckPermissionByGroup('role-6-management-checkbox', this)"
                                                                                value="6">
                                                                            <label for="6management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Visit History') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-6-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_visit_view"
                                                                                value="visit.view"
                                                                                data-role-id="6">
                                                                            <label for="permission_visit_view"
                                                                                class="form-check-label">{{ __('View Visits') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="permission_visit_create"
                                                                                value="visit.create"
                                                                                data-role-id="6">
                                                                            <label for="permission_visit_create"
                                                                                class="form-check-label">{{ __('Create Visit') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <x-admin.save-button :text="__('Save')" />
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <script>
        "use strict"

        // Function to check if all permissions are selected
        function permission_all_checked() {
            // Get all permission checkboxes (excluding group checkboxes and permission_all)
            var allPermissionCheckboxes = $('input[name^="permissions[]"]');
            var checkedPermissionCheckboxes = allPermissionCheckboxes.filter(':checked');
            
            // Check if all permission checkboxes are checked
            var allChecked = allPermissionCheckboxes.length === checkedPermissionCheckboxes.length;
            
            // Update permission_all checkbox
            $('#permission_all').prop('checked', allChecked);
        }

        // When group checkbox changes
        $('input[id$="management"]').on('change', function() {
            permission_all_checked();
        });

        // When permission_all is clicked
        $('#permission_all').on('click', function() {
            var isChecked = $(this).prop('checked');
            
            // Check/uncheck all permission checkboxes
            $('input[name^="permissions[]"]').prop('checked', isChecked);
            
            // Check/uncheck all group checkboxes
            $('input[id$="management"]').prop('checked', isChecked);
        });

        // Function to check/uncheck permissions by group
        function CheckPermissionByGroup(classname, checkthis) {
            var isChecked = $(checkthis).prop('checked');
            
            // Check/uncheck all permissions in this group
            $('.' + classname + ' input[name^="permissions[]"]').prop('checked', isChecked);
            
            // Update permission_all checkbox
            permission_all_checked();
        }

        // When individual permission checkbox changes
        $('input[name^="permissions[]"]').on('change', function() {
            var roleId = $(this).data('role-id');
            var groupCheckbox = $('#' + roleId + 'management');
            var groupPermissions = $('input[name^="permissions[]"][data-role-id="' + roleId + '"]');

            var checkedPermissionsCount = groupPermissions.filter(':checked').length;
            var totalPermissionsCount = groupPermissions.length;

            // Check/uncheck group checkbox based on whether all permissions in group are checked
            groupCheckbox.prop('checked', checkedPermissionsCount === totalPermissionsCount);

            // Update permission_all checkbox
            permission_all_checked();
        });

        // Initialize on page load
        $(document).ready(function() {
            permission_all_checked();
        });
    </script>
@endpush