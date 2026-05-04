@extends('admin.master_layout')
@section('title')
    <title>{{ __('Shop Management') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Shop Management') }}" :list="[]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Shop List')" />
                                <div>
                                    <button type="button" class="btn btn-primary" id="bulkAssignBtn" disabled>
                                        <i class="fas fa-user-plus"></i> {{ __('Bulk Assign Jobs') }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="district_filter">{{ __('Filter by District') }}</label>
                                            <select id="district_filter" class="form-control">
                                                <option value="">{{ __('All Districts') }}</option>
                                                @if(isset($districts))
                                                    @foreach($districts as $district)
                                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="shopsTable">
                                        <thead>
                                            <tr>
                                                <th style="width:40px;">
                                                    <input type="checkbox" id="selectAllShops" class="form-check-input">
                                                </th>
                                                <th>#</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Owner Name') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('District') }}</th>
                                                <th>{{ __('Created By') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($shops as $index => $shop)
                                                <tr data-district-id="{{ $shop->district_id ?? '' }}">
                                                    {{-- col 0: Checkbox --}}
                                                    <td>
                                                        <input type="checkbox"
                                                               class="shop-checkbox form-check-input"
                                                               value="{{ $shop->id }}"
                                                               data-shop-name="{{ $shop->name ?? $shop->shop_name ?? 'N/A' }}"
                                                               data-district-id="{{ $shop->district_id ?? '' }}">
                                                    </td>
                                                    {{-- col 1: # --}}
                                                    <td>{{ ++$index }}</td>
                                                    {{-- col 2: Shop Name --}}
                                                    <td>{{ $shop->name ?? $shop->shop_name ?? 'N/A' }}</td>
                                                    {{-- col 3: Category --}}
                                                    <td>{{ $shop->category ?? 'N/A' }}</td>
                                                    {{-- col 4: Owner Name --}}
                                                    <td>{{ $shop->owner_name ?? 'N/A' }}</td>
                                                    {{-- col 5: Phone --}}
                                                    <td>{{ $shop->phone ?? $shop->phone_number ?? 'N/A' }}</td>
                                                    {{-- col 6: District --}}
                                                    <td>
                                                        @if($shop->district)
                                                            <span class="badge badge-info">{{ $shop->district->name }}</span>
                                                        @else
                                                            <span class="text-muted">{{ __('N/A') }}</span>
                                                        @endif
                                                    </td>
                                                    {{-- col 7: Created By / Staff --}}
                                                    <td>
                                                        @if($shop->staff && $shop->staff->name)
                                                            <span class="badge badge-info">{{ $shop->staff->name }}</span>
                                                        @else
                                                            <span class="text-muted">{{ __('Unassigned') }}</span>
                                                        @endif
                                                    </td>
                                                    {{-- col 8: Action --}}
                                                    <td>
                                                        @if(auth('admin')->user()->hasPermissionTo('shop.edit'))
                                                            <button type="button"
                                                                    class="btn btn-sm btn-primary"
                                                                    onclick="openAssignModal({{ $shop->id }})"
                                                                    id="assignBtn-{{ $shop->id }}">
                                                                <i class="fas fa-user-plus"></i>
                                                                <span class="btn-text">{{ __('Assign') }}</span>
                                                            </button>
                                                        @else
                                                            <span class="text-muted">{{ __('No permission') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center">
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="assigned_to">{{ __('Assign To') }} <span class="text-danger">*</span></label>
                                    <select id="assigned_to" name="assigned_to" class="form-control" required>
                                        <option value="">{{ __('Select Agent') }}</option>
                                    </select>
                                    <div class="invalid-feedback">{{ __('Please select an agent') }}</div>
                                </div>
                            </div>
                        </div>
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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description">{{ __('Description') }}
                                        <span class="text-muted">({{ __('Optional') }})</span>
                                    </label>
                                    <textarea id="description" name="description" class="form-control"
                                              rows="4" placeholder="{{ __('Enter job description...') }}"
                                              style="height:120px;"></textarea>
                                    <div class="invalid-feedback">{{ __('Please enter a job description') }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes">{{ __('Additional Notes') }}
                                        <span class="text-muted">({{ __('Optional') }})</span>
                                    </label>
                                    <textarea id="notes" name="notes" class="form-control"
                                              rows="3" placeholder="{{ __('Enter additional notes...') }}"
                                              style="height:100px;"></textarea>
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

    <!-- Bulk Assign Job Modal -->
    <div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Bulk Assign Jobs') }}</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulkAssignForm" action="{{ route('admin.shop-management.bulk-assign') }}" method="POST">
                    @csrf
                    <!-- Shops Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label>{{ __('Select Shops') }}</label>
                                    <div>
                                        <small class="text-muted">
                                            <input type="checkbox" id="modalSelectAllShops" class="form-check-input mr-1">
                                            <label for="modalSelectAllShops" class="form-check-label mb-0">
                                                {{ __('Select All') }}
                                            </label>
                                        </small>
                                    </div>
                                </div>
                                <div id="selectedShopsList" class="border rounded p-2"
                                     style="max-height:200px; overflow-y:auto;">
                                    <p class="text-muted mb-0">{{ __('Loading shops...') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Assign To - Required -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bulk_assigned_to">{{ __('Assign To') }} <span class="text-danger">*</span></label>
                                <select id="bulk_assigned_to" name="assigned_to" class="form-control" required>
                                    <option value="">{{ __('Select Agent') }}</option>
                                    @foreach($allStaff as $staff)
                                        <option value="{{ $staff->id }}"
                                                data-district-id="{{ $staff->district_id ?? '' }}">
                                            {{ $staff->email }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">{{ __('Please select an agent') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Scheduled Date and Time - Both Required -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulk_scheduled_date">{{ __('Scheduled Date') }} <span class="text-danger">*</span></label>
                                <input type="date" id="bulk_scheduled_date" name="scheduled_date" class="form-control">
                                <div class="invalid-feedback">{{ __('Please select a scheduled date') }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bulk_scheduled_time">{{ __('Scheduled Time') }} <span class="text-danger">*</span></label>
                                <input type="time" id="bulk_scheduled_time" name="scheduled_time" class="form-control">
                                <div class="invalid-feedback">{{ __('Please select a scheduled time') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description - Required -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bulk_description">{{ __('Job Description') }} <span class="text-danger">*</span></label>
                                <textarea id="bulk_description" name="description" class="form-control"
                                          rows="3" placeholder="{{ __('Enter job description') }}"></textarea>
                                <div class="invalid-feedback">{{ __('Please enter a job description') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Notes - Optional -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="bulk_notes">{{ __('Notes') }}
                                    <span class="text-muted">({{ __('Optional') }})</span>
                                </label>
                                <textarea id="bulk_notes" name="notes" class="form-control"
                                          rows="2" placeholder="{{ __('Enter any additional notes (optional)') }}"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary" id="submitBulkAssignBtn">
                            {{ __('Bulk Assign Jobs') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
// ============================================================
// Staff data: PHP → JavaScript (global scope)
// ============================================================
var allStaffData = [];
@foreach($allStaff as $staff)
allStaffData.push({
    id: {{ $staff->id }},
    email: '{{ addslashes($staff->email) }}',
    district_id: {{ $staff->district_id ?? 'null' }}
});
@endforeach

// ============================================================
// openAssignModal — MUST be global (used in onclick="")
// ============================================================
function openAssignModal(shopId) {
    var $btn = $('#assignBtn-' + shopId);
    $btn.prop('disabled', true);
    $btn.find('.btn-text').text('{{ __("Loading...") }}');

    // Set form action & reset
    $('#shop_id').val(shopId);
    $('#assignJobForm').attr('action',
        '{{ route("admin.shop-management.assign", ":id") }}'.replace(':id', shopId)
    );
    $('#assignJobForm')[0].reset();
    $('#assignJobForm .is-invalid').removeClass('is-invalid');
    $('#assignJobForm .invalid-feedback').hide();

    var $staffSelect = $('#assigned_to');
    $staffSelect.html('<option value="">{{ __("Loading agents...") }}</option>');

    // Fetch shop district then populate agents
    $.ajax({
        url: '{{ route("admin.shop-management.get-shop-district", ":id") }}'.replace(':id', shopId),
        type: 'GET',
        success: function(response) {
            var shopDistrictId = response.district_id;
            $staffSelect.html('<option value="">{{ __("Select Agent") }}</option>');

            var filtered = allStaffData.filter(function(s) {
                return !shopDistrictId || s.district_id == shopDistrictId;
            });

            if (filtered.length > 0) {
                filtered.forEach(function(s) {
                    $staffSelect.append(
                        '<option value="' + s.id + '">' + s.email + '</option>'
                    );
                });
            } else {
                // Fallback: show all with note
                allStaffData.forEach(function(s) {
                    $staffSelect.append(
                        '<option value="' + s.id + '">' + s.email +
                        ' ({{ __("Different District") }})</option>'
                    );
                });
            }
        },
        error: function() {
            $staffSelect.html('<option value="">{{ __("Select Agent") }}</option>');
            allStaffData.forEach(function(s) {
                $staffSelect.append('<option value="' + s.id + '">' + s.email + '</option>');
            });
        },
        complete: function() {
            $btn.prop('disabled', false);
            $btn.find('.btn-text').text('{{ __("Assign") }}');
        }
    });

    $('#assignJobModal').modal('show');

    // Cleanup on close
    $('#assignJobModal').off('hidden.bs.modal').on('hidden.bs.modal', function() {
        $btn.prop('disabled', false);
        $btn.find('.btn-text').text('{{ __("Assign") }}');
        $('#assignJobForm .is-invalid').removeClass('is-invalid');
        $('#assignJobForm .invalid-feedback').hide();
        $('#submitAssignBtn').prop('disabled', false).text('{{ __("Assign Job") }}');
    });
}

// ============================================================
// Document Ready
// ============================================================
$(document).ready(function() {

    // ----------------------------------------------------------
    // District Filter
    // Key fix: filter using data-district-id on <tr> directly
    // ----------------------------------------------------------
    $('#district_filter').on('change', function() {
        var selected = $(this).val();

        $('#shopsTable tbody tr').each(function() {
            if (selected === '') {
                $(this).show();
            } else {
                var rowDistrict = String($(this).data('district-id') ?? '');
                $(this).toggle(rowDistrict === String(selected));
            }
        });

        // Reset select-all after filtering
        syncSelectAllState();
    });

    // ----------------------------------------------------------
    // Select All Checkbox (only visible rows)
    // ----------------------------------------------------------
    $('#selectAllShops').on('change', function() {
        var checked = $(this).prop('checked');
        $('#shopsTable tbody tr:visible .shop-checkbox').prop('checked', checked);
        updateBulkAssignButton();
        updateSelectedShopsList();
    });

    // ----------------------------------------------------------
    // Individual Checkbox
    // ----------------------------------------------------------
    $(document).on('change', '.shop-checkbox', function() {
        syncSelectAllState();
        updateBulkAssignButton();
        updateSelectedShopsList();
    });

    // ----------------------------------------------------------
    // Bulk Assign Button → open modal (no validation needed)
    // ----------------------------------------------------------
    $('#bulkAssignBtn').on('click', function() {
        updateSelectedShopsList();
        $('#bulkAssignModal').modal('show');
    });

    // ----------------------------------------------------------
    // Assign Job Form Submit (single shop)
    // ----------------------------------------------------------
    $('#assignJobForm').on('submit', function(e) {
        e.preventDefault();

        if (!validateAssignForm()) {
            toastr.error('{{ __("Please fill all required fields correctly") }}');
            return;
        }

        var $btn = $('#submitAssignBtn');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> {{ __("Assigning...") }}');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            success: function(response) {
                $('#assignJobModal').modal('hide');
                toastr.success(response.message || '{{ __("Job assigned successfully!") }}');
                setTimeout(function() {
                    window.location.href = '{{ route("admin.shop-management.index") }}';
                }, 2000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('{{ __("Assign Job") }}');
                handleAjaxErrors(xhr);
            }
        });
    });

    // ----------------------------------------------------------
    // Bulk Assign Form Submit
    // ----------------------------------------------------------
    $('#bulkAssignForm').on('submit', function(e) {
        e.preventDefault();

        if (!validateBulkAssignForm()) {
            toastr.error('{{ __("Please fill all required fields correctly") }}');
            return;
        }

        var formData = new FormData(this);
        formData.delete('shop_ids');
        $('.shop-checkbox:checked').each(function() {
            formData.append('shop_ids[]', $(this).val());
        });

        var $btn = $('#submitBulkAssignBtn');
        $btn.prop('disabled', true)
            .html('<i class="fas fa-spinner fa-spin"></i> {{ __("Bulk Assigning...") }}');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#bulkAssignModal').modal('hide');
                toastr.success(response.message || '{{ __("Jobs assigned successfully!") }}');
                // Reset all
                $('.shop-checkbox, #selectAllShops').prop('checked', false);
                $('#bulkAssignForm')[0].reset();
                updateBulkAssignButton();
                updateSelectedShopsList();
                setTimeout(function() {
                    window.location.href = '{{ route("admin.shop-management.index") }}';
                }, 2000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html('{{ __("Bulk Assign Jobs") }}');
                handleAjaxErrors(xhr);
            }
        });
    });

    // ----------------------------------------------------------
    // Live validation removal on input
    // ----------------------------------------------------------
    $(document).on('change input keyup',
        '#assigned_to, #scheduled_date, #scheduled_time, #description, ' +
        '#bulk_assigned_to, #bulk_scheduled_date, #bulk_scheduled_time, #bulk_description',
        function() {
            $(this).removeClass('is-invalid');
            $(this).siblings('.invalid-feedback').hide();
        }
    );

    // ----------------------------------------------------------
    // Modal shop checkboxes
    // ----------------------------------------------------------
    $(document).on('change', '.modal-shop-checkbox', function() {
        var shopId = $(this).val();
        var isChecked = $(this).prop('checked');
        
        // Sync with main table checkbox
        $('.shop-checkbox[value="' + shopId + '"]').prop('checked', isChecked);
        
        // Update UI
        if (isChecked) {
            $(this).closest('.border').addClass('bg-light');
        } else {
            $(this).closest('.border').removeClass('bg-light');
        }
        
        // Update states
        syncSelectAllState();
        updateBulkAssignButton();
        updateModalSelectAllState();
    });

    // Modal select all checkbox
    $('#modalSelectAllShops').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.modal-shop-checkbox').prop('checked', isChecked);
        
        // Sync with main table
        $('.shop-checkbox').prop('checked', isChecked);
        
        // Update UI
        if (isChecked) {
            $('.modal-shop-checkbox').closest('.border').addClass('bg-light');
        } else {
            $('.modal-shop-checkbox').closest('.border').removeClass('bg-light');
        }
        
        // Update states
        updateBulkAssignButton();
    });

    // ============================================================
    // HELPERS
    // ============================================================

    function syncSelectAllState() {
        var $visible  = $('#shopsTable tbody tr:visible .shop-checkbox');
        var $checked  = $('#shopsTable tbody tr:visible .shop-checkbox:checked');
        $('#selectAllShops').prop(
            'checked',
            $visible.length > 0 && $visible.length === $checked.length
        );
    }

    function updateBulkAssignButton() {
        var count = $('.shop-checkbox:checked').length;
        // Always enable bulk assign button
        $('#bulkAssignBtn').prop('disabled', false);
        if (count > 0) {
            $('#bulkAssignBtn').html(
                '<i class="fas fa-user-plus"></i> {{ __("Bulk Assign Jobs") }} (' + count + ')'
            );
        } else {
            $('#bulkAssignBtn').html(
                '<i class="fas fa-user-plus"></i> {{ __("Bulk Assign Jobs") }}'
            );
        }
    }

    function updateSelectedShopsList() {
        var allShops = [];
        $('#shopsTable tbody tr').each(function() {
            var $checkbox = $(this).find('.shop-checkbox');
            allShops.push({
                id       : $checkbox.val(),
                name     : $checkbox.data('shop-name'),
                checked  : $checkbox.prop('checked'),
                district : $(this).data('district-id')
            });
        });

        var $list = $('#selectedShopsList');
        if (allShops.length > 0) {
            var html = '<div class="shop-list">';
            allShops.forEach(function(shop) {
                var checkedAttr = shop.checked ? 'checked' : '';
                var districtName = shop.district ? getDistrictName(shop.district) : 'N/A';
                html += '<div class="mb-2 p-2 border rounded ' + (shop.checked ? 'bg-light' : '') + '">';
                html += '<div class="d-flex align-items-center">';
                html += '<input type="checkbox" class="modal-shop-checkbox form-check-input mr-2" ' + 
                        'value="' + shop.id + '" data-shop-name="' + shop.name + '" ' + checkedAttr + '>';
                html += '<div class="flex-grow-1">';
                html += '<div class="font-weight-medium">' + shop.name + '</div>';
                html += '<small class="text-muted">District: ' + districtName + '</small>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';
            $list.html(html);
            
            // Update modal select all checkbox state
            updateModalSelectAllState();
        } else {
            $list.html('<p class="text-muted mb-0">{{ __("No shops available") }}</p>');
        }
    }

    function validateAssignForm() {
        var valid = true;
        [
            { id: '#assigned_to' },
            { id: '#scheduled_date' },
            { id: '#scheduled_time' }
        ].forEach(function(f) {
            var $el = $(f.id);
            if (!$el.val() || $el.val().trim() === '') {
                $el.addClass('is-invalid');
                $el.siblings('.invalid-feedback').show();
                valid = false;
            } else {
                $el.removeClass('is-invalid');
                $el.siblings('.invalid-feedback').hide();
            }
        });
        return valid;
    }

    function validateBulkAssignForm() {
        var valid = true;

        ['#bulk_assigned_to','#bulk_scheduled_date','#bulk_scheduled_time','#bulk_description']
        .forEach(function(id) {
            var $el = $(id);
            var val = $el.val();
            if (!val || val.trim() === '') {
                $el.addClass('is-invalid');
                $el.siblings('.invalid-feedback').show();
                valid = false;
            } else if (id === '#bulk_description' && val.trim().length < 5) {
                $el.addClass('is-invalid');
                $el.siblings('.invalid-feedback')
                   .text('{{ __("Description must be at least 5 characters") }}').show();
                valid = false;
            } else {
                $el.removeClass('is-invalid');
                $el.siblings('.invalid-feedback').hide();
            }
        });

        if ($('.shop-checkbox:checked').length === 0) {
            toastr.error('{{ __("Please select at least one shop") }}');
            valid = false;
        }
        return valid;
    }

    function handleAjaxErrors(xhr) {
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
            $.each(xhr.responseJSON.errors, function(key, msgs) {
                toastr.error(msgs[0]);
                $('[name="' + key + '"]')
                    .addClass('is-invalid')
                    .siblings('.invalid-feedback').text(msgs[0]).show();
            });
        } else if (xhr.responseJSON && xhr.responseJSON.message) {
            toastr.error(xhr.responseJSON.message);
        } else {
            toastr.error('{{ __("Something went wrong. Please try again.") }}');
        }
    }

    function updateModalSelectAllState() {
        var $modalCheckboxes = $('.modal-shop-checkbox');
        var $checked = $('.modal-shop-checkbox:checked');
        $('#modalSelectAllShops').prop(
            'checked',
            $modalCheckboxes.length > 0 && $modalCheckboxes.length === $checked.length
        );
    }

    function getDistrictName(districtId) {
        // This is a simplified version - you might want to enhance this
        var districtNames = {
            '1': 'Punjab',
            '2': 'Sindh', 
            '3': 'Balochistan',
            '4': 'KPK'
            // Add more as needed
        };
        return districtNames[districtId] || 'Unknown';
    }

}); // END document.ready
</script>
@endpush