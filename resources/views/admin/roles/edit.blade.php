@extends('admin.master_layout')
@section('title')
    <title>{{ __('Update Role') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section admin_role_edit">
            <x-admin.breadcrumb title="{{ __('Update Role') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Roles') => route('admin.role.index'),
                __('Update Role') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Update Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form role="form" action="{{ route('admin.role.update', $role->id) }}"
                                            method="POST">
                                            @method('PUT')
                                            @csrf
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">{{ __('Role Name') }}</label>
                                                    <input value="{{ $role->name }}" name="name" type="text"
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
                                                        <input
                                                            {{ App\Models\Admin::roleHasPermission($role, $permissions) ? 'checked' : '' }}
                                                            class="form-check-input" type="checkbox" id="permission_all"
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
                                                                            @php
                                                                                $dashboardPermission = \Spatie\Permission\Models\Permission::where('name', 'dashboard.view')->first();
                                                                                $dashboardChecked = $role->hasPermissionTo('dashboard.view');
                                                                            @endphp
                                                                            <input
                                                                                {{ $dashboardChecked ? 'checked' : '' }}
                                                                                class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="1management"
                                                                                onclick="CheckPermissionByGroup('role-1-management-checkbox', this)"
                                                                                value="1"
                                                                                name="permession_group"
                                                                                data-role-id="1">
                                                                            <label for="1management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Dashboard') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-1-management-checkbox">
                                                                        @php
                                                                            $permission = \Spatie\Permission\Models\Permission::where('name', 'dashboard.view')->first();
                                                                        @endphp
                                                                        @if($permission)
                                                                            <div class="form-check mb-2">
                                                                                <input
                                                                                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                                                    name="permissions[]"
                                                                                    class="form-check-input"
                                                                                    type="checkbox"
                                                                                    id="permission_checkbox_{{ $permission->id }}"
                                                                                    value="{{ $permission->name }}"
                                                                                    data-role-id="1">
                                                                                <label for="permission_checkbox_{{ $permission->id }}"
                                                                                    class="custom-control-label">{{ __('View Dashboard') }}</label>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Manage Staff Permissions -->
                                                            <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            @php
                                                                                $staffPermissions = ['staff.view', 'staff.create', 'staff.edit', 'staff.delete'];
                                                                                $allStaffChecked = true;
                                                                                foreach ($staffPermissions as $permName) {
                                                                                    if (!$role->hasPermissionTo($permName)) {
                                                                                        $allStaffChecked = false;
                                                                                        break;
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                            <input
                                                                                {{ $allStaffChecked ? 'checked' : '' }}
                                                                                class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="2management"
                                                                                onclick="CheckPermissionByGroup('role-2-management-checkbox', this)"
                                                                                value="2"
                                                                                name="permession_group"
                                                                                data-role-id="2">
                                                                            <label for="2management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Staff') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-2-management-checkbox">
                                                                        @php
                                                                            $staffPermissionsList = [
                                                                                ['name' => 'staff.view', 'label' => 'View Staff'],
                                                                                ['name' => 'staff.create', 'label' => 'Create Staff'],
                                                                                ['name' => 'staff.edit', 'label' => 'Edit Staff'],
                                                                                ['name' => 'staff.delete', 'label' => 'Delete Staff'],
                                                                            ];
                                                                        @endphp
                                                                        @foreach ($staffPermissionsList as $permData)
                                                                            @php
                                                                                $permission = \Spatie\Permission\Models\Permission::where('name', $permData['name'])->first();
                                                                            @endphp
                                                                            @if($permission)
                                                                                <div class="form-check mb-2">
                                                                                    <input
                                                                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                                                        name="permissions[]"
                                                                                        class="form-check-input"
                                                                                        type="checkbox"
                                                                                        id="permission_checkbox_{{ $permission->id }}"
                                                                                        value="{{ $permission->name }}"
                                                                                        data-role-id="2">
                                                                                    <label for="permission_checkbox_{{ $permission->id }}"
                                                                                        class="custom-control-label">{{ __($permData['label']) }}</label>
                                                                                </div>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Manage Roles Permissions - Commented -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="3management"
                                                                                onclick="CheckPermissionByGroup('role-3-management-checkbox', this)"
                                                                                value="3"
                                                                                name="permession_group"
                                                                                data-role-id="3">
                                                                            <label for="3management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Roles') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-3-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="role.view"
                                                                                data-role-id="3">
                                                                            <label class="custom-control-label">{{ __('View Roles') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="role.create"
                                                                                data-role-id="3">
                                                                            <label class="custom-control-label">{{ __('Create Role') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="role.edit"
                                                                                data-role-id="3">
                                                                            <label class="custom-control-label">{{ __('Edit Role') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="role.delete"
                                                                                data-role-id="3">
                                                                            <label class="custom-control-label">{{ __('Delete Role') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Manage Shops Permissions - Commented -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="4management"
                                                                                onclick="CheckPermissionByGroup('role-4-management-checkbox', this)"
                                                                                value="4"
                                                                                name="permession_group"
                                                                                data-role-id="4">
                                                                            <label for="4management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Shops') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-4-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="shop.view"
                                                                                data-role-id="4">
                                                                            <label class="custom-control-label">{{ __('View Shops') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="shop.create"
                                                                                data-role-id="4">
                                                                            <label class="custom-control-label">{{ __('Create Shop') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="shop.edit"
                                                                                data-role-id="4">
                                                                            <label class="custom-control-label">{{ __('Edit Shop') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="shop.delete"
                                                                                data-role-id="4">
                                                                            <label class="custom-control-label">{{ __('Delete Shop') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Manage Settings Permissions - Commented -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="5management"
                                                                                onclick="CheckPermissionByGroup('role-5-management-checkbox', this)"
                                                                                value="5"
                                                                                name="permession_group"
                                                                                data-role-id="5">
                                                                            <label for="5management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Manage Settings') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-5-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="settings.view"
                                                                                data-role-id="5">
                                                                            <label class="custom-control-label">{{ __('View Settings') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="settings.edit"
                                                                                data-role-id="5">
                                                                            <label class="custom-control-label">{{ __('Edit Settings') }}</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> -->

                                                            <!-- Visit History Permissions - Commented -->
                                                            <!-- <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input permession_group"
                                                                                type="checkbox"
                                                                                id="6management"
                                                                                onclick="CheckPermissionByGroup('role-6-management-checkbox', this)"
                                                                                value="6"
                                                                                name="permession_group"
                                                                                data-role-id="6">
                                                                            <label for="6management"
                                                                                class="form-check-label text-capitalize fw-bold">{{ __('Visit History') }}</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 role-6-management-checkbox">
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="visit.view"
                                                                                data-role-id="6">
                                                                            <label class="custom-control-label">{{ __('View Visits') }}</label>
                                                                        </div>
                                                                        <div class="form-check mb-2">
                                                                            <input name="permissions[]"
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                value="visit.create"
                                                                                data-role-id="6">
                                                                            <label class="custom-control-label">{{ __('Create Visit') }}</label>
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
                                                    <x-admin.update-button :text="__('Update')" />
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
        $('.permession_group').on('change', function() {
            permission_all_checked();
        });

        // When permission_all is clicked
        $('#permission_all').on('click', function() {
            var isChecked = $(this).prop('checked');
            
            // Check/uncheck all permission checkboxes
            $('input[name^="permissions[]"]').prop('checked', isChecked);
            
            // Check/uncheck all group checkboxes
            $('.permession_group').prop('checked', isChecked);
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