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
                                                <label class="form-label">{{ __('Permissions') }}</label>
                                                <div class="admin_role_border">
                                                    <div class="row">
                                                        <!-- Dashboard Permission -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_dashboard_view" value="dashboard.view">
                                                                <label for="perm_dashboard_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-tachometer-alt"></i> {{ __('Dashboard Access') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Role Management Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_view" value="role.view">
                                                                <label for="perm_role_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-shield-alt"></i> {{ __('View Roles') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_create" value="role.create">
                                                                <label for="perm_role_create" class="form-check-label fw-bold">
                                                                    <i class="fas fa-plus"></i> {{ __('Create Role') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_edit" value="role.edit">
                                                                <label for="perm_role_edit" class="form-check-label fw-bold">
                                                                    <i class="fas fa-edit"></i> {{ __('Edit Role') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_delete" value="role.delete">
                                                                <label for="perm_role_delete" class="form-check-label fw-bold">
                                                                    <i class="fas fa-trash"></i> {{ __('Delete Role') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_assign" value="role.assign">
                                                                <label for="perm_role_assign" class="form-check-label fw-bold">
                                                                    <i class="fas fa-key"></i> {{ __('Assign Permissions to Role') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Staff Management Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_view" value="staff.view">
                                                                <label for="perm_staff_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-users"></i> {{ __('View Staff') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_create" value="staff.create">
                                                                <label for="perm_staff_create" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-plus"></i> {{ __('Create Staff') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_edit" value="staff.edit">
                                                                <label for="perm_staff_edit" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-edit"></i> {{ __('Edit Staff') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_delete" value="staff.delete">
                                                                <label for="perm_staff_delete" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-times"></i> {{ __('Delete Staff') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Staff Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_permission_view" value="staff.permission.view">
                                                                <label for="perm_staff_permission_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-lock"></i> {{ __('View Staff Permissions') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Activity Logs Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_activity_view" value="activity.logs.view">
                                                                <label for="perm_activity_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-history"></i> {{ __('View Activity Logs') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_activity_view_subadmin" value="activity.logs.view.subadmin">
                                                                <label for="perm_activity_view_subadmin" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-shield"></i> {{ __('View Sub-admin Activity Logs') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Sub Admin Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_admin_create" value="admin.create">
                                                                <label for="perm_admin_create" class="form-check-label fw-bold">
                                                                    <i class="fas fa-user-plus"></i> {{ __('Create Sub Admin') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Shop Management Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_view" value="shop.view">
                                                                <label for="perm_shop_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-store"></i> {{ __('View Shops') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_create" value="shop.create">
                                                                <label for="perm_shop_create" class="form-check-label fw-bold">
                                                                    <i class="fas fa-plus"></i> {{ __('Create Shop') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_edit" value="shop.edit">
                                                                <label for="perm_shop_edit" class="form-check-label fw-bold">
                                                                    <i class="fas fa-edit"></i> {{ __('Edit Shop') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_shop_delete" value="shop.delete">
                                                                <label for="perm_shop_delete" class="form-check-label fw-bold">
                                                                    <i class="fas fa-trash"></i> {{ __('Delete Shop') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Task Management Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_view" value="task.view">
                                                                <label for="perm_task_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-tasks"></i> {{ __('View Tasks') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_create" value="task.create">
                                                                <label for="perm_task_create" class="form-check-label fw-bold">
                                                                    <i class="fas fa-plus"></i> {{ __('Create Task') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_edit" value="task.edit">
                                                                <label for="perm_task_edit" class="form-check-label fw-bold">
                                                                    <i class="fas fa-edit"></i> {{ __('Edit Task') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_task_delete" value="task.delete">
                                                                <label for="perm_task_delete" class="form-check-label fw-bold">
                                                                    <i class="fas fa-trash"></i> {{ __('Delete Task') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <!-- Settings Permissions -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_setting_view" value="setting.view">
                                                                <label for="perm_setting_view" class="form-check-label fw-bold">
                                                                    <i class="fas fa-cogs"></i> {{ __('View Settings') }}
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="form-check">
                                                                <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_setting_update" value="setting.update">
                                                                <label for="perm_setting_update" class="form-check-label fw-bold">
                                                                    <i class="fas fa-save"></i> {{ __('Update Settings') }}
                                                                </label>
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