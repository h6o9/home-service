@extends('admin.master_layout')

@section('title')
    <title>{{ __('Admin Activity Logs') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Admin Activity Logs') }}</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></div>
                    <div class="breadcrumb-item">{{ __('Admin Activity Logs') }}</div>
                </div>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Activity Logs') }}</h4>
                            </div>
                            <div class="card-body">
                                <!-- Filters -->
                                <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="mb-4">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="admin_id">{{ __('Admin') }}</label>
                                            <select name="admin_id" id="admin_id" class="form-control">
                                                <option value="">{{ __('All Admins') }}</option>
                                                @foreach($admins as $admin)
                                                    <option value="{{ $admin->id }}" {{ request('admin_id') == $admin->id ? 'selected' : '' }}>
                                                        {{ $admin->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="action">{{ __('Action') }}</label>
                                            <select name="action" id="action" class="form-control">
                                                <option value="">{{ __('All Actions') }}</option>
                                                @foreach($actions as $action)
                                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                                        {{ ucfirst($action) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="start_date">{{ __('Start Date') }}</label>
                                            <input type="date" name="start_date" id="start_date" class="form-control" 
                                                   value="{{ request('start_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label for="end_date">{{ __('End Date') }}</label>
                                            <input type="date" name="end_date" id="end_date" class="form-control" 
                                                   value="{{ request('end_date') }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label>&nbsp;</label><br>
                                            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                                            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                                        </div>
                                    </div>
                                </form>

                                <!-- Activity Logs Table -->
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('ID') }}</th>
                                                <th>{{ __('Admin') }}</th>
                                                <th>{{ __('Action') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('IP Address') }}</th>
                                                <th>{{ __('Date & Time') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($activities as $activity)
                                                <tr>
                                                    <td>{{ $activity->id }}</td>
                                                    <td>
                                                        <strong>{{ $activity->admin->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $activity->admin->email }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $activity->getActionBadgeClass() }}">
                                                            {{ ucfirst($activity->action) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $activity->description ?? '-' }}</td>
                                                    <td>{{ $activity->ip_address ?? '-' }}</td>
                                                    <td>{{ $activity->created_at->format('M d, Y H:i A') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.activity-logs.show', $activity->id) }}" 
                                                           class="btn btn-info btn-sm" title="{{ __('View Details') }}">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">{{ __('No activity logs found') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center">
                                    {{ $activities->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('css')
    <style>
        .badge-created { background-color: #28a745; }
        .badge-updated { background-color: #007bff; }
        .badge-deleted { background-color: #dc3545; }
        .badge-login { background-color: #17a2b8; }
        .badge-logout { background-color: #6c757d; }
        .badge-unknown { background-color: #ffc107; color: #212529; }
    </style>
@endpush

@push('js')
    <script>
        // Auto-refresh every 30 seconds
        setInterval(function() {
            if (!document.hidden) {
                window.location.reload();
            }
        }, 30000);
    </script>
@endpush
