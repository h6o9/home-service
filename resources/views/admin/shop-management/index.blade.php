@extends('admin.master_layout')
@section('title')
    <title>Shop Management</title>
@endsection
@section('admin-content')
@can('shop.view')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Shop Management</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Shops & Staff Activity</h4>
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

                                <div class="table-responsive">
                                    <table class="table table-striped" id="shopsTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Owner') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                                <th>{{ __('Jobs Assigned') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shops as $index => $shop)
                                                <tr>
                                                    <td>{{ $shops->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ $shop->shop_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $shop->address }}</small>
                                                    </td>
                                                    <td>{{ $shop->owner_name }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $shop->category == 'electrician' ? 'primary' : ($shop->category == 'wifi_controller' ? 'info' : ($shop->category == 'solar' ? 'warning' : 'success')) }}">
                                                            {{ $shop->category_label }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($shop->staff)
                                                            <span class="badge badge-info">{{ $shop->staff->name }}</span>
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $shop->jobs->count() > 0 ? 'success' : 'secondary' }}">
                                                            {{ $shop->jobs->count() }} jobs
                                                        </span>
                                                        @if($shop->jobs->count() > 0)
                                                            <br>
                                                            <small class="text-muted">
                                                                @foreach($shop->jobs->take(2) as $job)
                                                                    {{ $job->assignedTo->name ?? 'Unassigned' }} ({{ $job->job_type }})
                                                                    @if(!$loop->last), @endif
                                                                @endforeach
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($shop->jobs->where('status', 'pending')->count() > 0)
                                                            <span class="badge badge-warning">{{ $shop->jobs->where('status', 'pending')->count() }} pending</span>
                                                        @endif
                                                        @if($shop->jobs->where('status', 'in_progress')->count() > 0)
                                                            <span class="badge badge-info">{{ $shop->jobs->where('status', 'in_progress')->count() }} in progress</span>
                                                        @endif
                                                        @if($shop->jobs->where('status', 'completed')->count() > 0)
                                                            <span class="badge badge-success">{{ $shop->jobs->where('status', 'completed')->count() }} completed</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <!-- <a href="{{ route('admin.shop-management.show', $shop->id) }}" class="btn btn-info btn-sm">
                                                            <i class="fa fa-eye"></i> View
                                                        </a> -->
                                                        <button class="btn btn-primary btn-sm" onclick="openAssignModal({{ $shop->id }})">
                                                            <i class="fa fa-user-plus"></i> Assign
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                {{ $shops->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Assign Job Modal -->
    <div class="modal fade" id="assignJobModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Jobs</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="assignJobForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="shop_id" id="modalShopId">
                        
                        <div class="form-group">
                            <label>Select Staff Member <span class="text-danger">*</span></label>
                            <select name="assigned_to" id="assignedTo" class="form-control" required>
                                <option value="">Select Staff Member</option>
                                @foreach($allStaff ?? [] as $staff)
                                    <option value="{{ $staff->id }}">{{ $staff->name }} ({{ $staff->email }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Job description..."></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Scheduled Date</label>
                                    <input type="date" name="scheduled_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Scheduled Time</label>
                                    <input type="time" name="scheduled_time" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" rows="2" placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Assign Jobs</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@endcan
@push('js')
    <script>
        "use strict";

        function openAssignModal(shopId) {
            $('#modalShopId').val(shopId);
            $('#assignJobForm').attr('action', '{{ route("admin.shop-management.assign", ":id") }}'.replace(':id', shopId));
            $('#assignJobModal').modal('show');
        }
    </script>
@endpush
