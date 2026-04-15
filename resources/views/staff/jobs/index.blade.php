@extends('staff.master_layout')
@section('title')
    <title>{{ __('My Jobs') }}</title>
@endsection
@section('staff-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('My Jobs') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('My Assigned Jobs')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Assigned By') }}</th>
                                                <th>{{ __('Scheduled Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($jobs as $index => $job)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $job->shop->shop_name ?? 'N/A' }}</td>
                                                    <td>{{ $job->description ?? 'N/A' }}</td>
                                                    <td>{{ $job->assignedBy->name ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($job->scheduled_date)
                                                            {{ $job->scheduled_date }}
                                                            @if($job->scheduled_time)
                                                                {{ $job->scheduled_time }}
                                                            @endif
                                                        @else
                                                            {{ __('Not scheduled') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($job->status == 'pending')
                                                            <span class="badge badge-warning">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge badge-success">{{ __('Done') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('staff.jobs.show', $job->id) }}" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> {{ __('View') }}
                                                        </a>
                                                        @if($job->status == 'pending')
                                                            <button type="button" class="btn btn-sm btn-success" onclick="markAsDone({{ $job->id }})">
                                                                <i class="fas fa-check"></i> {{ __('Done') }}
                                                            </button>
                                                        @else
                                                            <button type="button" class="btn btn-sm btn-warning" onclick="markAsUndone({{ $job->id }})">
                                                                <i class="fas fa-undo"></i> {{ __('Undo') }}
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle"></i> 
                                                            {{ __('No jobs assigned to you yet!') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    
                                    @if($jobs->isNotEmpty())
                                        <div class="float-right">
                                            {{ $jobs->links() }}
                                        </div>
                                    @endif
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
        function markAsDone(jobId) {
            if(!confirm('Are you sure you want to mark this job as done?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route("staff.jobs.mark-done", ":id") }}'.replace(':id', jobId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        }

        function markAsUndone(jobId) {
            if(!confirm('Are you sure you want to mark this job as pending?')) {
                return;
            }
            
            $.ajax({
                url: '{{ route("staff.jobs.mark-undone", ":id") }}'.replace(':id', jobId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        }
    </script>
@endpush