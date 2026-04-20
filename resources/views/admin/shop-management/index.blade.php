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
                                    @if(auth('admin')->user()->hasPermissionTo('shop.view'))
                                    <a href="{{ route('admin.shop-management.shop-list') }}" class="btn btn-primary">
                                        <i class="fas fa-store"></i> {{ __('Shop List') }}
                                    </a>
                                    @endif
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
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($jobs as $index => $job)
                                                <tr>
                                                    <td>{{ $jobs->firstItem() + $index }}</td>
                                                    <td>{{ $job->shop->shop_name ?? 'N/A' }}</td>
                                                    <td>{{ $job->assignedTo->email ?? 'N/A' }}</td>
                                                    <td>{{ $job->assignedBy->name ?? 'N/A' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" onclick="viewDescription({{ $job->id }}, '{{ addslashes($job->description) }}')">
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
                                                            <button type="button" class="btn btn-sm btn-secondary" onclick="viewNotes({{ $job->id }}, '{{ addslashes($job->notes) }}')">
                                                                <i class="fas fa-sticky-note"></i> {{ __('View Notes') }}
                                                            </button>
                                                        @else
                                                            <span class="text-muted">{{ __('No notes') }}</span>
                                                        @endif
                                                    </td>
                                                    <!-- Action Column with Delete Button -->
                                                    <td>
                                                        @can('assign.job.delete')
                                                            <x-admin.delete-button :id="$job->id" onclick="deleteData" />
                                                        @else
                                                            <span class="text-muted">No Action</span>
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
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

    <!-- Description Modal -->
    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Job Description') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="descriptionContent"></div>
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
                <div class="modal-body" id="notesContent"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    @can('assign.job.delete')
        <x-admin.delete-modal />
    @endcan
@endsection

@push('js')
    <script>
        function viewDescription(jobId, description) {
            // Escape HTML special characters to prevent XSS
            const escapedDescription = description ? description.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            }).replace(/[\r\n]+/g, '<br>') : '';
            
            // Display the description in modal
            $('#descriptionContent').html(`
                <div class="job-description">
                    <hr>
                    <div class="description-text">
                        ${escapedDescription || '<em>{{ __('No description provided.') }}</em>'}
                    </div>
                </div>
            `);
            $('#descriptionModal').modal('show');
        }

        function viewNotes(jobId, notes) {
            // Escape HTML special characters to prevent XSS
            const escapedNotes = notes ? notes.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            }).replace(/[\r\n]+/g, '<br>') : '';
            
            // Display the notes in modal
            $('#notesContent').html(`
                <div class="job-notes">
                    <hr>
                    <div class="notes-text">
                        ${escapedNotes || '<em>{{ __('No notes provided.') }}</em>'}
                    </div>
                </div>
            `);
            $('#notesModal').modal('show');
        }

        // Delete function for assigned jobs
        @if(auth('admin')->user()->hasPermissionTo('assign.job.delete'))
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ route('admin.assigned-jobs.destroy', ':id') }}".replace(':id', id));
        }
        @endif
    </script>
@endpush