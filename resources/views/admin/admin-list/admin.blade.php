@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Sub Admin') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Sub Admins') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Manage Sub Admins')" /><small class="font-weight-bold text-danger">(The default password for all sub-admins is 12345678. This password is automatically generated when a new sub-admin is created. The Admin Panel will only be accessible to a sub-admin once a role has been assigned to them.)</small>
                                <div>
                                    @adminCan('admin.create')
                                        <x-admin.add-button :href="route('admin.admin.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                            
                                                    <th>{{ __('Status') }}</th>
                                               
                                                @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($admins as $index => $admin)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $admin->name }}</td>
                                                    <td>{{ $admin->email }}</td>
                                                 
                                                        <td>
                                                            @adminCan('admin.edit')
                                                            <input onchange="changeAdminStatus({{ $admin->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $admin->status == 'active' ? 'checked' : '' }}
                                                                data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                                @endadminCan
                                                        </td>
                                              
                                                    @if (checkAdminHasPermission('admin.edit') || checkAdminHasPermission('admin.delete'))
                                                        <td>
                                                            @adminCan('admin.edit')
                                                                <x-admin.edit-button :href="route('admin.admin.edit', $admin->id)" />
                                                            @endadminCan
                                                            @adminCan('admin.delete')
                                                                <x-admin.delete-button :id="$admin->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Admin')" route="admin.admin.create" create="yes"
                                                    :message="__('No data found!')" colspan="6" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="float-right">
                                        {{ $admins->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('admin.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('admin.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('admin/admin/') }}" + "/" + id)
        }
        @endadminCan
        @adminCan('admin.update')

        function changeAdminStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error('This Is Demo Version. You Can Not Change Anything');
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                url: "{{ url('/admin/admin-status/') }}" + "/" + id,
                success: function(response) {
                    toastr.success(response.message)
                },
                error: function(err) {
                    console.log(err);
                }
            })
        }
        @endadminCan
    </script>
@endpush
