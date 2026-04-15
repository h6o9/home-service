@extends('admin.master_layout')
@section('title')
    <title>{{ __('Assigned Jobs') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Assigned Jobs') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Assigned Jobs')" />
                                <div>
                                    <a href="{{ route('admin.shop-management.shop-list') }}" class="btn btn-primary">
                                        <i class="fas fa-store"></i> {{ __('Shop List') }}
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Assigned To') }}</th>
                                                <th>{{ __('Assigned By') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Scheduled Date') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Additional Notes') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($jobs as $index => $job)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $job->shop->shop_name ?? 'N/A' }}</td>
                                                    <td>{{ $job->assignedTo->name ?? 'N/A' }}</td>
                                                    <td>{{ $job->assignedBy->name ?? 'N/A' }}</td>
                                                    <td>
                                                        {{ $job->description ?? 'N/A' }}
                                                        <br>
                                                        <button type="button" class="btn btn-sm btn-info mt-1" onclick="viewJobDetails({{ $job->id }})">
                                                            <i class="fas fa-eye"></i> {{ __('View Details') }}
                                                        </button>
                                                    </td>
                                                    <td>
                                                        @if($job->scheduled_date)
                                                            {{ $job->scheduled_date }}
                                                            @if($job->scheduled_time)
                                                                <br><small>{{ $job->scheduled_time }}</small>
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
                                                        @if($job->notes)
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="viewNotes({{ $job->id }})">
                                                                <i class="fas fa-sticky-note"></i> {{ __('View Notes') }}
                                                            </button>
                                                        @else
                                                            <span class="text-muted">{{ __('No notes') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle"></i> 
                                                            {{ __('No jobs assigned yet!') }}
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

    <!-- Job Details Modal -->
    <div class="modal fade" id="jobDetailsModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Job Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="jobDetailsContent">
                    <div class="loading text-center">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Modal -->
    <div class="modal fade" id="notesModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Additional Notes') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notesContent">
                    <div class="loading text-center">
                        <i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function viewJobDetails(jobId) {
            $('#jobDetailsContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}</div>');
            $('#jobDetailsModal').modal('show');
            
            $.ajax({
                url: '{{ route("admin.shop-management.job-details") }}',
                type: 'GET',
                data: { id: jobId },
                success: function(response) {
                    $('#jobDetailsContent').html(response.html);
                },
                error: function(xhr) {
                    $('#jobDetailsContent').html('<div class="alert alert-danger">{{ __('Error loading job details. Please try again.') }}</div>');
                    toastr.error('{{ __('Error loading job details') }}');
                }
            });
        }

        function viewNotes(jobId) {
            $('#notesContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> {{ __('Loading...') }}</div>');
            $('#notesModal').modal('show');
            
            $.ajax({
                url: '{{ route("admin.shop-management.job-notes") }}',
                type: 'GET',
                data: { id: jobId },
                success: function(response) {
                    $('#notesContent').html(response.html);
                },
                error: function(xhr) {
                    $('#notesContent').html('<div class="alert alert-danger">{{ __('Error loading notes. Please try again.') }}</div>');
                    toastr.error('{{ __('Error loading notes') }}');
                }
            });
        }
    </script>
@endpush