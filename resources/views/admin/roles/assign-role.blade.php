@extends('admin.master_layout')
@section('title')
    <title>{{ __('Assign Permissions') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assign Permissions') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Admin Settings') => '#',
                __('Assign Permissions') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Assign Permissions')" />
                                <div>
                                    @adminCan('role.view')
                                        <x-admin.back-button :href="route('admin.role.index')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('admin.role.assign.update') }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="user_id">{{ __('Select Role') }} <span class="text-danger">*</span></label>
                                            <select name="user_id" id="user_id" class="form-control select2" required>
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ __('Admin Panel Menu Permissions') }}</label>
                                                <div class="admin_role_border">
                                                    <div class="row">
                                                        <!-- Dashboard Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="dashboard_section" value="1">
                                                                        <label for="dashboard_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-tachometer-alt"></i> {{ __('Dashboard') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 dashboard_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_dashboard_view" value="dashboard.view">
                                                                        <label for="perm_dashboard_view" class="form-check-label">{{ __('Can View') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Admin Settings Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="admin_settings_section" value="1">
                                                                        <label for="admin_settings_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-user-shield"></i> {{ __('Admin Settings') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 admin_settings_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_view" value="role.view">
                                                                        <label for="perm_role_view" class="form-check-label">{{ __('Manage Roles') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_assign" value="role.assign">
                                                                        <label for="perm_role_assign" class="form-check-label">{{ __('Assign Permissions') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_assign_roles" value="role.assign">
                                                                        <label for="perm_assign_roles" class="form-check-label">{{ __('Assign Roles') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_activity_logs" value="activity.logs.view">
                                                                        <label for="perm_activity_logs" class="form-check-label">{{ __('Activity Logs') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_admin_create" value="admin.create">
                                                                        <label for="perm_admin_create" class="form-check-label">{{ __('Add Sub Admin') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Staff Management Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="staff_management_section" value="1">
                                                                        <label for="staff_management_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-users"></i> {{ __('Staff Management') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 staff_management_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_view" value="staff.view">
                                                                        <label for="perm_staff_view" class="form-check-label">{{ __('Staff List') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_create" value="staff.create">
                                                                        <label for="perm_staff_create" class="form-check-label">{{ __('Add Staff') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_permission_view" value="staff.permission.view">
                                                                        <label for="perm_staff_permission_view" class="form-check-label">{{ __('Staff Permissions') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Shop Management Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="shop_management_section" value="1">
                                                                        <label for="shop_management_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-store"></i> {{ __('Shop Management') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 shop_management_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_view" value="shop.view">
                                                                        <label for="perm_shop_view" class="form-check-label">{{ __('Shop List') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_create" value="shop.create">
                                                                        <label for="perm_shop_create" class="form-check-label">{{ __('Add Shop') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Tasks & Jobs Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="tasks_jobs_section" value="1">
                                                                        <label for="tasks_jobs_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-tasks"></i> {{ __('Tasks & Jobs') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 tasks_jobs_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_create" value="task.create">
                                                                        <label for="perm_task_create" class="form-check-label">{{ __('Create Task') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_view" value="task.view">
                                                                        <label for="perm_task_view" class="form-check-label">{{ __('Assigned Tasks') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Settings Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="settings_section" value="1">
                                                                        <label for="settings_section" class="form-check-label text-capitalize fw-bold">
                                                                            <i class="fas fa-cog"></i> {{ __('Settings') }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 settings_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_permission_setup" value="permission.view">
                                                                        <label for="perm_permission_setup" class="form-check-label">{{ __('Permissions Setup') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_setting_view" value="setting.view">
                                                                        <label for="perm_setting_view" class="form-check-label">{{ __('System Settings') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> {{ __('Save Permissions') }}
                                            </button>
                                            <a href="{{ route('admin.role.index') }}" class="btn btn-secondary">
                                                <i class="fas fa-times"></i> {{ __('Cancel') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
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
        $(document).ready(function() {
            // Handle section checkboxes
            function handleSectionCheckbox(sectionId, permissionClass) {
                $('#' + sectionId).on('change', function() {
                    var isChecked = $(this).prop('checked');
                    $('.' + permissionClass + ' input[type="checkbox"]').prop('checked', isChecked);
                });
            }

            // Initialize section handlers
            handleSectionCheckbox('dashboard_section', 'dashboard_permissions');
            handleSectionCheckbox('admin_settings_section', 'admin_settings_permissions');
            handleSectionCheckbox('staff_management_section', 'staff_management_permissions');
            handleSectionCheckbox('shop_management_section', 'shop_management_permissions');
            handleSectionCheckbox('tasks_jobs_section', 'tasks_jobs_permissions');
            handleSectionCheckbox('settings_section', 'settings_permissions');

            // Handle individual permission checkboxes
            $('.admin_role_border input[type="checkbox"]').on('change', function() {
                // Update section checkbox based on individual permissions
                var sectionId = $(this).closest('[id$="_permissions"]').parent().find('input[type="checkbox"]:first').attr('id');
                var permissionClass = $(this).closest('[class$="_permissions"]').attr('class').split(' ')[0];
                var allChecked = $('.' + permissionClass + ' input[type="checkbox"]').length === 
                                $('.' + permissionClass + ' input[type="checkbox"]:checked').length;
                $('#' + sectionId).prop('checked', allChecked);
            });

            // Load role permissions when role is selected
            $('#user_id').on('change', function() {
                var roleId = $(this).val();
                if (roleId) {
                    $.get('{{ route("admin.role.assign.admin", ":id") }}'.replace(':id', roleId))
                        .done(function(response) {
                            if (response.success) {
                                // Clear all checkboxes first
                                $('.admin_role_border input[type="checkbox"]').prop('checked', false);
                                
                                // Check the permissions that belong to this role
                                if (response.permissions && response.permissions.length > 0) {
                                    $.each(response.permissions, function(index, permission) {
                                        $('input[value="' + permission + '"]').prop('checked', true);
                                    });
                                }
                            }
                        })
                        .fail(function() {
                            console.error('Error loading role permissions');
                        });
                } else {
                    // Clear all checkboxes if no role selected
                    $('.admin_role_border input[type="checkbox"]').prop('checked', false);
                }
            });
        });
    </script>
@endpush
