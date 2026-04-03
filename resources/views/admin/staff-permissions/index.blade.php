@extends('admin.master_layout')
@section('title')
    <title>Staff Permissions</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Staff Permissions</h1>
            </div>

            <div class="section-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4>Staff Members & Permissions</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="staffTable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Staff Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Permissions') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staff as $member)
                                        <tr>
                                            <td>
                                                <strong>{{ $member->name }}</strong>
                                                @if(!$member->is_active)
                                                    <span class="badge badge-danger ml-2">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{ $member->email }}</td>
                                            <td>
                                                <a href="{{ route('admin.staff-permissions.show', $member->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                            </td>
                                            <td>
                                                @if($member->is_active)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.staff-permissions.edit', $member->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
            $('#staffTable').DataTable();
        });
    </script>
@endpush
