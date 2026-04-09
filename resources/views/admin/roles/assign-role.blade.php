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
                                <x-admin.form-title :text="__('Assign Permissions to Role')" />
                                <div>
                                    @adminCan('role.view')
                                        <a href="{{ url('/admin/role') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                                        </a>
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <form id="permissionForm" action="{{ url('/update-role-permissions') }}" method="POST">
                                    @method('PUT')
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="role_id">{{ __('Select Role') }} <span class="text-danger">*</span></label>
                                            <select name="user_id" id="role_id" class="form-control select2" required>
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4" id="permissionsSection" style="display: none;">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <label class="form-label font-weight-bold">{{ __('Admin Panel Menu Permissions') }}</label>
                                                    <div>
                                                        <button type="button" id="checkAll" class="btn btn-sm btn-primary">Check All</button>
                                                        <button type="button" id="uncheckAll" class="btn btn-sm btn-secondary">Uncheck All</button>
                                                    </div>
                                                </div>
                                                <div class="admin_role_border">
                                                    <div class="row">
                                                        @php $i=1; @endphp
                                                        @foreach ($permission_groups as $groupName => $permissions)
                                                            <div class="py-2 mb-2 col-lg-6 border-bottom">
                                                                <div class="row">
                                                                    <div class="col-12 col-md-5 col-lg-5 col-xl-4">
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input section-checkbox" 
                                                                                   type="checkbox" 
                                                                                   id="section_{{ $i }}" 
                                                                                   data-section="{{ Str::slug($groupName) }}">
                                                                            <label for="section_{{ $i }}" class="form-check-label text-capitalize fw-bold">
                                                                                <i class="fas fa-folder"></i> {{ $groupName }}
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-12 col-md-7 col-lg-7 col-xl-8 permission-group-{{ $i }}">
                                                                        @foreach ($permissions as $permission)
                                                                            <div class="form-check mb-2">
                                                                                <input name="permissions[]" 
                                                                                       class="form-check-input permission-checkbox" 
                                                                                       type="checkbox" 
                                                                                       id="perm_{{ $permission->id }}" 
                                                                                       value="{{ $permission->name }}" 
                                                                                       data-section="{{ Str::slug($groupName) }}">
                                                                                <label for="perm_{{ $permission->id }}" class="form-check-label">
                                                                                    {{-- Convert permission name to readable format --}}
                                                                                    {{ ucfirst(str_replace('.', ' ', $permission->name)) }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @php $i++; @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4" id="submitSection" style="display: none;">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                                <i class="fas fa-save"></i> {{ __('Save Permissions') }}
                                            </button>
                                            <a href="{{ url('/admin/role') }}" class="btn btn-secondary">
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
            // Build section mapping dynamically
            var sectionMapping = {};
            @php $i=1; @endphp
            @foreach ($permission_groups as $groupName => $permissions)
                var sectionKey = '{{ Str::slug($groupName) }}';
                var sectionClass = '.permission-group-{{ $i }}';
                sectionMapping[sectionKey] = sectionClass;
            @php $i++; @endphp
            @endforeach

            // Function to update section checkbox based on individual permissions
            function updateSectionCheckbox(section) {
                const sectionClass = sectionMapping[section];
                if (sectionClass) {
                    const totalPermissions = $(sectionClass + ' .permission-checkbox').length;
                    const checkedPermissions = $(sectionClass + ' .permission-checkbox:checked').length;
                    const sectionCheckbox = $('.section-checkbox[data-section="' + section + '"]');
                    sectionCheckbox.prop('checked', totalPermissions === checkedPermissions && totalPermissions > 0);
                }
            }

            // Handle section checkbox click
            $('.section-checkbox').on('change', function() {
                const section = $(this).data('section');
                const sectionClass = sectionMapping[section];
                const isChecked = $(this).prop('checked');
                
                if (sectionClass) {
                    $(sectionClass + ' .permission-checkbox').prop('checked', isChecked);
                }
            });

            // Handle individual permission checkbox click
            $(document).on('change', '.permission-checkbox', function() {
                const section = $(this).data('section');
                updateSectionCheckbox(section);
            });

            // Check All button functionality
            $('#checkAll').on('click', function() {
                $('.permission-checkbox').prop('checked', true);
                $('.section-checkbox').prop('checked', true);
            });

            // Uncheck All button functionality
            $('#uncheckAll').on('click', function() {
                $('.permission-checkbox').prop('checked', false);
                $('.section-checkbox').prop('checked', false);
            });

            // Load role permissions when role is selected
            $('#role_id').on('change', function() {
                var roleId = $(this).val();
                if (roleId) {
                    $('#permissionsSection, #submitSection').hide();
                    
                    $.ajax({
                        url: '{{ url("/get-role-permissions") }}/' + roleId,
                        type: 'GET',
                        success: function(response) {
                            if (response.success) {
                                $('.permission-checkbox').prop('checked', false);
                                $('.section-checkbox').prop('checked', false);
                                
                                if (response.permissions && response.permissions.length > 0) {
                                    $.each(response.permissions, function(index, permissionName) {
                                        $('.permission-checkbox[value="' + permissionName + '"]').prop('checked', true);
                                    });
                                }
                                
                                setTimeout(function() {
                                    for (const section in sectionMapping) {
                                        updateSectionCheckbox(section);
                                    }
                                }, 100);
                                
                                $('#permissionsSection, #submitSection').fadeIn();
                            } else {
                                toastr.error(response.message || 'Error loading permissions');
                                $('#permissionsSection, #submitSection').hide();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error:', xhr);
                            toastr.error('Error loading role permissions');
                            $('#permissionsSection, #submitSection').hide();
                        }
                    });
                } else {
                    $('#permissionsSection, #submitSection').fadeOut();
                    $('.permission-checkbox').prop('checked', false);
                    $('.section-checkbox').prop('checked', false);
                }
            });

            // Form submission with confirmation
            $('#permissionForm').on('submit', function(e) {
                var selectedRole = $('#role_id option:selected').text();
                var hasPermissions = $('.permission-checkbox:checked').length > 0;
                
                if (!hasPermissions) {
                    if (confirm('Are you sure you want to remove all permissions from this role?')) {
                        return true;
                    } else {
                        e.preventDefault();
                        return false;
                    }
                }
                
                return confirm('Are you sure you want to update permissions for "' + selectedRole + '" role?');
            });
        });
    </script>
@endpush