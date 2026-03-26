@extends('admin.master_layout')
@section('title')
    <title>{{ __('Staff List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Staff List') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Staff List') }} <small class="font-weight-bold text-danger"> (The default password for all staff members is 12345678. This password is automatically generated when a new staff member is created.)</small></h4>
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.staff.create') }}"><i
                                            class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="staffTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Phone') }}</th>
												<th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($staffs as $index => $staff)
                                                <tr>
                                                    <td>{{ $staffs->firstItem() + $index }}</td>
                                                    <td>{{ $staff->name }}</td>
                                                    <td>{{ $staff->email }}</td>
                                                    <td>{{ $staff->phone }}</td>
													<td>
														<input onchange="changeStaffStatus({{ $staff->id }})"
    id="status_toggle_{{ $staff->id }}" type="checkbox"
    {{ $staff->status == 1 ? 'checked' : '' }}
    data-toggle="toggle" data-onlabel="{{ __('Active') }}"
    data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
    data-offstyle="danger">
                                                    </td>

                                                    <td>
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.staff.edit', $staff->id) }}"><i
                                                                class="fa fa-edit" aria-hidden="true"></i></a>
                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" href="javascript:;"
                                                            onclick="deleteData({{ $staff->id }})"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                {{ $staffs->links() }}
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
        "use strict";

        function deleteData(id) {
            let url = '{{ route('admin.staff.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }

        function changeStaffStatus(staffId) {
    let status = $('#status_toggle_' + staffId).prop('checked') ? 1 : 0;
    
    $.ajax({
        url: '{{ route('staff.change.status', ':id') }}'.replace(':id', staffId),
        type: 'POST',
        data: {
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if(response.success) {
                toastr.success('Staff status updated successfully');
            } else {
                toastr.error('Something went wrong');
            }
        },
        error: function() {
            toastr.error('Error updating status');
        }
    });
}
    </script>
@endpush
