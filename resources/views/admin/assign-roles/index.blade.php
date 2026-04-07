@extends('admin.master_layout')

@section('title')
    <title>{{ __('Assign Roles') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Assign Roles') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Assign Roles') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Assign Roles to Admins') }}</h4>
                            </div>
                            <div class="card-body">
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.assign-roles.assign') }}">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="admin_id">{{ __('Select Admin') }}</label>
                                                <select name="admin_id" id="admin_id" class="form-control" required>
                                                    <option value="">{{ __('Select Admin') }}</option>
                                                    @foreach($admins as $admin)
                                                        <option value="{{ $admin->id }}">{{ $admin->name }} ({{ $admin->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('Select Roles') }}</label>
                                                <div class="row">
                                                    @foreach($roles as $role)
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}">
                                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                                    {{ ucfirst($role->name) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">{{ __('Assign Roles') }}</button>
                                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">{{ __('Back') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Admin Roles -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Current Admin Roles') }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Admin Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Current Roles') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($admins as $admin)
                                                <tr>
                                                    <td>{{ $admin->name }}</td>
                                                    <td>{{ $admin->email }}</td>
                                                    <td>
                                                        @foreach($admin->roles as $role)
                                                            <span class="badge badge-primary">{{ ucfirst($role->name) }}</span>
                                                        @endforeach
                                                        @if($admin->roles->isEmpty())
                                                            <span class="text-muted">{{ __('No roles assigned') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info edit-admin-roles" 
                                                                data-admin-id="{{ $admin->id }}" 
                                                                data-admin-name="{{ $admin->name }}">
                                                            <i class="fas fa-edit"></i> {{ __('Edit Roles') }}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        $(document).ready(function() {
            // Edit admin roles
            $('.edit-admin-roles').on('click', function() {
                var adminId = $(this).data('admin-id');
                var adminName = $(this).data('admin-name');
                
                // Set the admin in dropdown
                $('#admin_id').val(adminId);
                
                // Clear all role checkboxes
                $('input[name="roles[]"]').prop('checked', false);
                
                // Get admin's current roles via AJAX
                $.get('{{ route("admin.assign-roles.get", ":adminId") }}'.replace(':adminId', adminId))
                    .done(function(roles) {
                        // Check the roles that admin currently has
                        roles.forEach(function(roleId) {
                            $('#role_' + roleId).prop('checked', true);
                        });
                        
                        // Scroll to form
                        $('html, body').animate({
                            scrollTop: $("#admin_id").offset().top - 100
                        }, 500);
                    })
                    .fail(function() {
                        alert('Error loading admin roles');
                    });
            });
        });
    </script>
@endpush
