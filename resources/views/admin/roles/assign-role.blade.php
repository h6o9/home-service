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
                                                        <!-- Dashboard Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="dashboard_section" value="1">
                                                                        <label for="dashboard_section" class="form-check-label text-capitalize fw-bold">{{ __('Dashboard') }}</label>
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

                                                        <!-- Roles Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="roles_section" value="1">
                                                                        <label for="roles_section" class="form-check-label text-capitalize fw-bold">{{ __('Roles') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 roles_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_view" value="role.view">
                                                                        <label for="perm_role_view" class="form-check-label">{{ __('Can View') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_create" value="role.create">
                                                                        <label for="perm_role_create" class="form-check-label">{{ __('Can Create') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_edit" value="role.edit">
                                                                        <label for="perm_role_edit" class="form-check-label">{{ __('Can Edit') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_delete" value="role.delete">
                                                                        <label for="perm_role_delete" class="form-check-label">{{ __('Can Delete') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_role_assign" value="role.assign">
                                                                        <label for="perm_role_assign" class="form-check-label">{{ __('Can Assign') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Staff Management Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="staff_section" value="1">
                                                                        <label for="staff_section" class="form-check-label text-capitalize fw-bold">{{ __('Staff Management') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 staff_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_view" value="staff.view">
                                                                        <label for="perm_staff_view" class="form-check-label">{{ __('Can View') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_create" value="staff.create">
                                                                        <label for="perm_staff_create" class="form-check-label">{{ __('Can Create') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_edit" value="staff.edit">
                                                                        <label for="perm_staff_edit" class="form-check-label">{{ __('Can Edit') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_staff_delete" value="staff.delete">
                                                                        <label for="perm_staff_delete" class="form-check-label">{{ __('Can Delete') }}</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Activity Logs Section -->
                                                        <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                            <div class="row">
                                                                <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input" type="checkbox" id="activity_section" value="1">
                                                                        <label for="activity_section" class="form-check-label text-capitalize fw-bold">{{ __('Activity Logs') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 activity_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_activity_view" value="activity.logs.view">
                                                                        <label for="perm_activity_view" class="form-check-label">{{ __('Can View') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_activity_view_subadmin" value="activity.logs.view.subadmin">
                                                                        <label for="perm_activity_view_subadmin" class="form-check-label">{{ __('Can View Sub-admin') }}</label>
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
                                                                        <label for="settings_section" class="form-check-label text-capitalize fw-bold">{{ __('Settings') }}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-md-7 col-lg-7 col-xl-8 settings_permissions">
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_setting_view" value="setting.view">
                                                                        <label for="perm_setting_view" class="form-check-label">{{ __('Can View') }}</label>
                                                                    </div>
                                                                    <div class="form-check mb-2">
                                                                        <input name="permissions[]" class="form-check-input" type="checkbox" id="perm_setting_update" value="setting.update">
                                                                        <label for="perm_setting_update" class="form-check-label">{{ __('Can Update') }}</label>
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
            handleSectionCheckbox('roles_section', 'roles_permissions');
            handleSectionCheckbox('staff_section', 'staff_permissions');
            handleSectionCheckbox('activity_section', 'activity_permissions');
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
                                
                                // Parse and set the permissions
                                var tempDiv = $('<div>').html(response.data);
                                tempDiv.find('option[selected]').each(function() {
                                    var permissionValue = $(this).val();
                                    $('input[value="' + permissionValue + '"]').prop('checked', true);
                                });
                                
                                // Update section checkboxes
                                $('.admin_role_border input[type="checkbox"]:first').trigger('change');
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
