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
                                                        @if(auth('admin')->user()->hasPermissionTo('assign shop-management'))
                                                        <button type="button" class="btn btn-sm btn-primary" onclick="openAssignModal({{ $shop->id }})" id="assignBtn-{{ $shop->id }}">
                                                            <i class="fas fa-user-plus"></i> <span class="btn-text">{{ __('Assign') }}</span>
                                                        </button>
                                                        @else
                                                        <span class="text-muted">{{ __('No permission') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center">
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
                        <!-- Assign To - Required -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="assigned_to">{{ __('Assign To') }} <span class="text-danger">*</span></label>
                                    <select id="assigned_to" name="assigned_to" class="form-control" required>
                                        <option value="">{{ __('Select Staff') }}</option>
                                        @foreach($allStaff as $staff)
                                            <option value="{{ $staff->id }}">{{ $staff->email ?? $staff->email }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">{{ __('Please select a staff member') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Scheduled Date and Time - Both Required -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_date">{{ __('Scheduled Date') }} <span class="text-danger">*</span></label>
                                    <input type="date" id="scheduled_date" name="scheduled_date" class="form-control">
                                    <div class="invalid-feedback">{{ __('Please select a scheduled date') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="scheduled_time">{{ __('Scheduled Time') }} <span class="text-danger">*</span></label>
                                    <input type="time" id="scheduled_time" name="scheduled_time" class="form-control">
                                    <div class="invalid-feedback">{{ __('Please select a scheduled time') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Description - Required -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }} <span class="text-danger">*</span></label>
                                    <textarea id="description" name="description" class="form-control" rows="10" placeholder="{{ __('Enter job description...') }}" style="height: 120px;"></textarea>
                                    <div class="invalid-feedback">{{ __('Please enter a job description') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes - Optional -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">{{ __('Additional Notes') }} <span class="text-muted">({{ __('Optional') }})</span></label>
                                    <textarea id="notes" name="notes" class="form-control" rows="4" placeholder="{{ __('Enter additional notes...') }}" style="height: 120px;"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="submitAssignBtn">{{ __('Assign Job') }}</button>
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
            
            // Reset form and remove validation errors
            $('#assignJobForm')[0].reset();
            $('#assignJobForm').find('.is-invalid').removeClass('is-invalid');
            $('#assignJobForm').find('.invalid-feedback').hide();
            
            $('#assignJobModal').modal('show');
            
            // Reset button when modal is hidden
            $('#assignJobModal').off('hidden.bs.modal').on('hidden.bs.modal', function () {
                assignBtn.prop('disabled', false);
                assignBtn.html('<i class="fas fa-user-plus"></i> <span class="btn-text">{{ __('Assign') }}</span>');
                $('#assignJobForm').find('.is-invalid').removeClass('is-invalid');
                $('#assignJobForm').find('.invalid-feedback').hide();
                $('#submitAssignBtn').prop('disabled', false);
                $('#submitAssignBtn').html('{{ __("Assign Job") }}');
            });
        }

        // Remove validation error on input change
        $(document).on('change input keyup', '#assigned_to, #scheduled_date, #scheduled_time, #description', function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').hide();
        });

        // Frontend validation function
        function validateAssignForm() {
            let isValid = true;
            
            // Validate assigned_to
            const assignedTo = $('#assigned_to').val();
            if (!assignedTo || assignedTo === '') {
                $('#assigned_to').addClass('is-invalid');
                $('#assigned_to').siblings('.invalid-feedback').show();
                isValid = false;
            } else {
                $('#assigned_to').removeClass('is-invalid');
                $('#assigned_to').siblings('.invalid-feedback').hide();
            }
            
            // Validate scheduled_date
            const scheduledDate = $('#scheduled_date').val();
            if (!scheduledDate || scheduledDate.trim() === '') {
                $('#scheduled_date').addClass('is-invalid');
                $('#scheduled_date').siblings('.invalid-feedback').show();
                isValid = false;
            } else {
                $('#scheduled_date').removeClass('is-invalid');
                $('#scheduled_date').siblings('.invalid-feedback').hide();
            }
            
            // Validate scheduled_time
            const scheduledTime = $('#scheduled_time').val();
            if (!scheduledTime || scheduledTime.trim() === '') {
                $('#scheduled_time').addClass('is-invalid');
                $('#scheduled_time').siblings('.invalid-feedback').show();
                isValid = false;
            } else {
                $('#scheduled_time').removeClass('is-invalid');
                $('#scheduled_time').siblings('.invalid-feedback').hide();
            }
            
            // Validate description
            const description = $('#description').val();
            if (!description || description.trim() === '') {
                $('#description').addClass('is-invalid');
                $('#description').siblings('.invalid-feedback').show();
                isValid = false;
            } else if (description.trim().length < 5) {
                $('#description').addClass('is-invalid');
                $('#description').siblings('.invalid-feedback').text('{{ __("Description must be at least 5 characters") }}').show();
                isValid = false;
            } else {
                $('#description').removeClass('is-invalid');
                $('#description').siblings('.invalid-feedback').hide();
            }
            
            return isValid;
        }

        $('#assignJobForm').on('submit', function(e) {
            e.preventDefault();
            
            // Frontend validation
            if (!validateAssignForm()) {
                toastr.error('{{ __("Please fill all required fields correctly") }}');
                return false;
            }
            
            var form = $(this);
            var url = form.attr('action');
            var formData = new FormData(form[0]);
            
            // Disable submit button to prevent double submission
            $('#submitAssignBtn').prop('disabled', true);
            $('#submitAssignBtn').html('<i class="fas fa-spinner fa-spin"></i> {{ __("Assigning...") }}');
            
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#assignJobModal').modal('hide');
                    toastr.success(response.message || '{{ __("Job assigned successfully!") }}');
                    
                    // Redirect to shop-management index page after 2 seconds
                    setTimeout(function() {
                        window.location.href = '{{ route("admin.shop-management.index") }}';
                    }, 2000);
                },
                error: function(xhr) {
                    // Re-enable submit button
                    $('#submitAssignBtn').prop('disabled', false);
                    $('#submitAssignBtn').html('{{ __("Assign Job") }}');
                    
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors from backend
                        const errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                            $(`[name="${key}"]`).addClass('is-invalid');
                            if (key === 'description') {
                                $(`[name="${key}"]`).siblings('.invalid-feedback').text(value[0]).show();
                            } else if (key === 'assigned_to') {
                                $('#assigned_to').siblings('.invalid-feedback').text(value[0]).show();
                            } else if (key === 'scheduled_date') {
                                $('#scheduled_date').siblings('.invalid-feedback').text(value[0]).show();
                            } else if (key === 'scheduled_time') {
                                $('#scheduled_time').siblings('.invalid-feedback').text(value[0]).show();
                            }
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('{{ __("Something went wrong. Please try again.") }}');
                    }
                }
            });
        });
    </script>
@endpush