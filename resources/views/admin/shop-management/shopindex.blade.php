@extends('admin.master_layout')
@section('title')
    <title>{{ __('Shop Management') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Shop Management') }}" :list="[
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Shop List')" />
                                <div>
                                    <a href="{{ route('admin.shop-management.index') }}" class="btn btn-info">
                                        <i class="fas fa-tasks"></i> {{ __('Assigned Jobs') }}
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
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Owner Name') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($shops as $index => $shop)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $shop->name ?? $shop->shop_name ?? 'N/A' }}</td>
                                                    <td>{{ $shop->category ?? 'N/A' }}</td>
                                                    <td>{{ $shop->owner_name ?? 'N/A' }}</td>
                                                    <td>{{ $shop->phone ?? $shop->phone_number ?? 'N/A' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-primary" onclick="openAssignModal({{ $shop->id }})" id="assignBtn-{{ $shop->id }}">
                                                            <i class="fas fa-user-plus"></i> <span class="btn-text">{{ __('Assign') }}</span>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">
                                                        <div class="alert alert-info mb-0">
                                                            <i class="fas fa-info-circle"></i> 
                                                            {{ __('No shops found!') }}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    
                                    @if($shops->isNotEmpty())
                                        <div class="float-right">
                                            {{ $shops->links() }}
                                        </div>
                                    @endif
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
                <h5 class="modal-title">{{ __('Assign Job') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="assignJobForm" action="" method="POST">
                @csrf
                <input type="hidden" id="shop_id" name="shop_id">
                <div class="modal-body">
                    <!-- Assign To - Full width -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="assigned_to">{{ __('Assign To') }} <span class="text-danger">*</span></label>
                                <select id="assigned_to" name="assigned_to" class="form-control" required>
                                    <option value="">{{ __('Select Staff') }}</option>
                                    @foreach($allStaff as $staff)
                                        <option value="{{ $staff->id }}">{{ $staff->name ?? $staff->email }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduled Date and Time - Side by side -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduled_date">{{ __('Scheduled Date') }}</label>
                                <input type="date" id="scheduled_date" name="scheduled_date" class="form-control" placeholder="mm/dd/yyyy">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="scheduled_time">{{ __('Scheduled Time') }}</label>
                                <input type="time" id="scheduled_time" name="scheduled_time" class="form-control" placeholder="--:--">
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">{{ __('Description') }}</label>
                                <textarea id="description" name="description" class="form-control" rows="5" placeholder="{{ __('Enter job description...') }}"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes">{{ __('Additional Notes') }}</label>
                                <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="{{ __('Enter additional notes...') }}"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Assign Job') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        function openAssignModal(shopId) {
            // Add loading state to button
            var assignBtn = $('#assignBtn-' + shopId);
            assignBtn.prop('disabled', true);
            assignBtn.html('<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">{{ __('Loading...') }}</span>');
            
            $('#shop_id').val(shopId);
            $('#assignJobForm').attr('action', '{{ route("admin.shop-management.assign", ":id") }}'.replace(':id', shopId));
            $('#assignJobModal').modal('show');
            
            // Reset button when modal is hidden
            $('#assignJobModal').on('hidden.bs.modal', function () {
                assignBtn.prop('disabled', false);
                assignBtn.html('<i class="fas fa-user-plus"></i> <span class="btn-text">{{ __('Assign') }}</span>');
            });
        }

        $('#assignJobForm').on('submit', function(e) {
            e.preventDefault();
            
            var form = $(this);
            var url = form.attr('action');
            var formData = new FormData(form[0]);
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#assignJobModal').modal('hide');
                    toastr.success('Job assigned successfully!');
                    
                    // Redirect to assigned jobs page after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.shop-management.index") }}';
                    }, 2000);
                },
                error: function(xhr) {
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong. Please try again.');
                    }
                }
            });
        });
    </script>
@endpush