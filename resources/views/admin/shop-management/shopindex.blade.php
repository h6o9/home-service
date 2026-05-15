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
                                    <button type="button" class="btn btn-primary" id="bulkAssignBtn">
                                        <i class="fas fa-user-plus"></i> {{ __('Bulk Assign Jobs') }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <form method="GET" action="{{ url()->current() }}" class="mb-3"id="filterForm">
                                        <div class="row align-items-end">
                                            <div class="col-md-3">
                                                <div class="form-group mb-0">
                                                    <label for="district_filter">{{ __('Filter by District') }}</label>
                                                    <select id="district_filter" name="district_id" class="form-control select2">
                                                        <option value="">{{ __('All Districts') }}</option>
                                                        @if(isset($districts))
                                                            @foreach($districts as $district)
                                                                <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                                                    {{ $district->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group mb-0">
                                                    <label for="category_filter">{{ __('Filter by Category') }}</label>
                                                    <select id="category_filter" name="category" class="form-control select2">
                                                        <option value="">{{ __('All Categories') }}</option>
                                                        @if(isset($categories))
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->name }}" {{ request('category') == $category->name ? 'selected' : '' }}>
                                                                    {{ __($category->name) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i>
                                                    {{ __('Filter') }}</button>
                                                <a href="{{ url()->current() }}" class="btn btn-danger"><i
                                                        class="fas fa-times"></i> {{ __('Reset') }}</a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-striped" id="shopsTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('Shop Name') }}</th>
                                                    <th>{{ __('Category') }}</th>
                                                    <th>{{ __('Owner Name') }}</th>
                                                    <th>{{ __('Location') }}</th>
                                                    <th>{{ __('District') }}</th>
                                                    <th>{{ __('Created By') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($shops as $index => $shop)
                                                    <tr data-shop-id="{{ $shop->id }}"
                                                        data-shop-name="{{ $shop->name ?? $shop->shop_name ?? 'N/A' }}">
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $shop->name ?? $shop->shop_name ?? 'N/A' }}</td>
                                                        <td>{{ $shop->category ?? 'N/A' }}</td>
                                                        <td>{{ $shop->owner_name ?? 'N/A' }}</td>
                                                        <td>{{ $shop->location ?? $shop->location ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($shop->district)
                                                                <span class="badge badge-info">{{ $shop->district->name }}</span>
                                                            @else
                                                                <span class="text-muted">{{ __('N/A') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($shop->staff && $shop->staff->name)
                                                                <span class="badge badge-info">{{ $shop->staff->name }}</span>
                                                            @else
                                                                <span class="text-muted">{{ __('Unassigned') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if(auth('admin')->user()->hasPermissionTo('shop.edit'))
                                                                <button type="button" class="btn btn-sm btn-primary"
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
                                                        <td colspan="8" class="text-center">
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

        {{-- ================================================================
        Single Assign Job Modal
        ================================================================ --}}
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
                                        <label for="assigned_to">{{ __('Assign To') }} <span
                                                class="text-danger">*</span></label>
                                        <select id="assigned_to" name="assigned_to" class="form-control select2-modal" required>
                                            <option value="">{{ __('Select Agent') }}</option>
                                            @foreach($allStaff as $staff)
                                                <option value="{{ $staff->id }}">
                                                    {{ $staff->name }} — {{ $staff->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ __('Please select an agent') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="scheduled_date">{{ __('Scheduled Date') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="date" id="scheduled_date" name="scheduled_date" class="form-control">
                                        <div class="invalid-feedback">{{ __('Please select a scheduled date') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="scheduled_time">{{ __('Scheduled Time') }} <span
                                                class="text-danger">*</span></label>
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
                                        <textarea id="description" name="description" class="form-control" rows="4"
                                            placeholder="{{ __('Enter job description...') }}" style="height:120px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="notes">{{ __('Additional Notes') }}
                                            <span class="text-muted">({{ __('Optional') }})</span>
                                        </label>
                                        <textarea id="notes" name="notes" class="form-control" rows="3"
                                            placeholder="{{ __('Enter additional notes...') }}"
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

        {{-- ================================================================
        Bulk Assign Job Modal
        ================================================================ --}}
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
                        <div class="modal-body">

                            <!-- Shop Selection -->
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <label class="mb-0 font-weight-bold">
                                                {{ __('Select Shops') }} <span class="text-danger">*</span>
                                            </label>
                                            <div class="d-flex align-items-center">
                                                <span id="selectedCountBadge" class="badge badge-primary mr-2">
                                                    0 {{ __('selected') }}
                                                </span>
                                                <input type="checkbox" id="modalSelectAllShops" class="form-check-input mr-1"
                                                    style="margin-top:0;">
                                                <label for="modalSelectAllShops" class="form-check-label mb-0 small">
                                                    {{ __('Select All') }}
                                                </label>
                                            </div>
                                        </div>
                                        <input type="text" id="modalShopSearch" class="form-control form-control-sm mb-2"
                                            placeholder="{{ __('Search shops...') }}">
                                        <div id="selectedShopsList" class="border rounded p-2"
                                            style="max-height:220px; overflow-y:auto;">
                                            <p class="text-muted mb-0">{{ __('Loading shops...') }}</p>
                                        </div>
                                        <small class="text-danger d-none" id="shopSelectionError">
                                            {{ __('Please select at least one shop') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Assign To -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bulk_assigned_to">
                                            {{ __('Assign To') }} <span class="text-danger">*</span>
                                        </label>
                                        <select id="bulk_assigned_to" name="assigned_to" class="form-control select2-modal" required>
                                            <option value="">{{ __('Select Agent') }}</option>
                                            @foreach($allStaff as $staff)
                                                <option value="{{ $staff->id }}">
                                                    {{ $staff->name }} — {{ $staff->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">{{ __('Please select an agent') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bulk_scheduled_date">
                                            {{ __('Scheduled Date') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" id="bulk_scheduled_date" name="scheduled_date" class="form-control">
                                        <div class="invalid-feedback">{{ __('Please select a scheduled date') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bulk_scheduled_time">
                                            {{ __('Scheduled Time') }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="time" id="bulk_scheduled_time" name="scheduled_time" class="form-control">
                                        <div class="invalid-feedback">{{ __('Please select a scheduled time') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="bulk_description">
                                            {{ __('Job Description') }}
                                            <span class="text-muted">({{ __('Optional') }})</span>
                                        </label>
                                        <textarea id="bulk_description" name="description" class="form-control" rows="3"
                                            placeholder="{{ __('Enter job description') }}"></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label for="bulk_notes">
                                            {{ __('Notes') }}
                                            <span class="text-muted">({{ __('Optional') }})</span>
                                        </label>
                                        <textarea id="bulk_notes" name="notes" class="form-control" rows="2"
                                            placeholder="{{ __('Enter any additional notes (optional)') }}"></textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                {{ __('Cancel') }}
                            </button>
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
    <!-- Select2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for district filter
            $('#district_filter').select2({
                placeholder: "{{ __('Search district...') }}",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for category filter
            $('#category_filter').select2({
                placeholder: "{{ __('Search category...') }}",
                allowClear: true,
                width: '100%'
            });

            // Initialize Select2 for modal dropdowns (agents)
            $('.select2-modal').select2({
                placeholder: "{{ __('Search agent...') }}",
                allowClear: true,
                width: '100%',
                dropdownParent: function() {
                    return $(this).closest('.modal');
                }
            });

            // Auto-submit form when select2 value changes
            $('#district_filter, #category_filter').on('change', function() {
                $('#filterForm').submit();
            });

            // ============================================================
            // All shops data collected from table rows
            // ============================================================
            var allShopsData = [];
            $('#shopsTable tbody tr').each(function () {
                var shopId = $(this).data('shop-id');
                var shopName = $(this).data('shop-name');
                if (shopId) {
                    allShopsData.push({ id: shopId, name: shopName || 'N/A' });
                }
            });

            // ============================================================
            // Single Assign Modal
            // ============================================================
            function openAssignModal(shopId) {
                $('#shop_id').val(shopId);
                $('#assignJobForm').attr('action',
                    '{{ route("admin.shop-management.assign", ":id") }}'.replace(':id', shopId)
                );
                $('#assignJobForm')[0].reset();
                clearFormErrors('#assignJobForm');
                $('#assignJobModal').modal('show');
                // Reinitialize select2 after modal is shown
                setTimeout(function() {
                    $('#assigned_to').select2({
                        dropdownParent: $('#assignJobModal'),
                        width: '100%',
                        placeholder: "{{ __('Search agent...') }}"
                    });
                }, 100);
            }
            window.openAssignModal = openAssignModal;

            // ============================================================
            // Bulk Assign Button
            // ============================================================
            $('#bulkAssignBtn').on('click', function () {
                $('#bulkAssignForm')[0].reset();
                clearFormErrors('#bulkAssignForm');
                $('#shopSelectionError').addClass('d-none');
                $('#modalShopSearch').val('');
                renderModalShopList('');
                $('#bulkAssignModal').modal('show');
                // Reinitialize select2 after modal is shown
                setTimeout(function() {
                    $('#bulk_assigned_to').select2({
                        dropdownParent: $('#bulkAssignModal'),
                        width: '100%',
                        placeholder: "{{ __('Search agent...') }}"
                    });
                }, 100);
            });

            // ============================================================
            // Modal Shop Search
            // ============================================================
            $('#modalShopSearch').on('input', function () {
                renderModalShopList($(this).val().toLowerCase().trim());
            });

            // ============================================================
            // Modal Select All
            // ============================================================
            $('#modalSelectAllShops').on('change', function () {
                var isChecked = $(this).prop('checked');
                $('#selectedShopsList .modal-shop-checkbox').prop('checked', isChecked);
                updateSelectedCount();
                if (isChecked) $('#shopSelectionError').addClass('d-none');
            });

            // Individual checkbox
            $(document).on('change', '.modal-shop-checkbox', function () {
                updateModalSelectAllState();
                updateSelectedCount();
                if ($('.modal-shop-checkbox:checked').length > 0) {
                    $('#shopSelectionError').addClass('d-none');
                }
            });

            // ============================================================
            // Single Assign Form Submit
            // ============================================================
            $('#assignJobForm').on('submit', function (e) {
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
                    success: function (response) {
                        $('#assignJobModal').modal('hide');
                        toastr.success(response.message || '{{ __("Job assigned successfully!") }}');
                        setTimeout(function () {
                            window.location.href = '{{ route("admin.shop-management.index") }}';
                        }, 2000);
                    },
                    error: function (xhr) {
                        $btn.prop('disabled', false).html('{{ __("Assign Job") }}');
                        handleAjaxErrors(xhr);
                    }
                });
            });

            // ============================================================
            // Bulk Assign Form Submit
            // ============================================================
            $('#bulkAssignForm').on('submit', function (e) {
                e.preventDefault();
                if (!validateBulkAssignForm()) {
                    toastr.error('{{ __("Please fill all required fields correctly") }}');
                    return;
                }

                var formData = new FormData(this);
                formData.delete('shop_ids[]');
                $('.modal-shop-checkbox:checked').each(function () {
                    formData.append('shop_ids[]', $(this).val());
                });

                var $btn = $('#submitBulkAssignBtn');
                $btn.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> {{ __("Bulk Assigning...") }}');

                var ajaxTimeout = setTimeout(function () {
                    $btn.prop('disabled', false).html('{{ __("Bulk Assign Jobs") }}');
                    toastr.error('{{ __("Request timed out. Please try again.") }}');
                }, 30000);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 25000,
                    success: function (response) {
                        clearTimeout(ajaxTimeout);
                        $('#bulkAssignModal').modal('hide');
                        toastr.success(response.message || '{{ __("Jobs assigned successfully!") }}');
                        setTimeout(function () {
                            window.location.href = '{{ route("admin.shop-management.index") }}';
                        }, 1500);
                    },
                    error: function (xhr) {
                        clearTimeout(ajaxTimeout);
                        $btn.prop('disabled', false).html('{{ __("Bulk Assign Jobs") }}');
                        handleAjaxErrors(xhr);
                    }
                });
            });

            // Live validation removal on input
            $(document).on('change input keyup',
                '#assigned_to, #scheduled_date, #scheduled_time, ' +
                '#bulk_assigned_to, #bulk_scheduled_date, #bulk_scheduled_time',
                function () {
                    $(this).removeClass('is-invalid');
                    $(this).siblings('.invalid-feedback').hide();
                }
            );

            // ============================================================
            // HELPERS
            // ============================================================

            function clearFormErrors(formSelector) {
                $(formSelector + ' .is-invalid').removeClass('is-invalid');
                $(formSelector + ' .invalid-feedback').hide();
            }

            function renderModalShopList(searchTerm) {
                var $list = $('#selectedShopsList');

                if (allShopsData.length === 0) {
                    $list.html('<p class="text-muted mb-0 text-center py-2">{{ __("No shops available") }}</p>');
                    updateSelectedCount();
                    return;
                }

                var filtered = searchTerm
                    ? allShopsData.filter(function (s) {
                        return s.name.toLowerCase().indexOf(searchTerm) !== -1;
                    })
                    : allShopsData;

                if (filtered.length === 0) {
                    $list.html('<p class="text-muted mb-0 text-center py-2">{{ __("No shops match your search") }}</p>');
                    updateSelectedCount();
                    return;
                }

                // Preserve checked state across re-renders
                var checkedIds = [];
                $('.modal-shop-checkbox:checked').each(function () {
                    checkedIds.push(String($(this).val()));
                });

                var html = '';
                filtered.forEach(function (shop) {
                    var isChecked = checkedIds.indexOf(String(shop.id)) !== -1;
                    var checkedAttr = isChecked ? 'checked' : '';
                    var highlightCls = isChecked ? 'bg-light' : '';

                    html += '<div class="mb-1 px-2 py-1 border rounded d-flex align-items-center ' + highlightCls + '">';
                    html += '<input type="checkbox" class="modal-shop-checkbox form-check-input mr-2 mt-0" '
                        + 'value="' + shop.id + '" ' + checkedAttr + ' style="cursor:pointer;">';
                    html += '<span style="font-size:0.92rem;">' + shop.name + '</span>';
                    html += '</div>';
                });

                $list.html(html);
                updateModalSelectAllState();
                updateSelectedCount();
            }

            function updateSelectedCount() {
                var count = $('.modal-shop-checkbox:checked').length;
                $('#selectedCountBadge').text(count + ' {{ __("selected") }}');
            }

            function updateModalSelectAllState() {
                var $all = $('.modal-shop-checkbox');
                var $checked = $('.modal-shop-checkbox:checked');
                $('#modalSelectAllShops').prop(
                    'checked',
                    $all.length > 0 && $all.length === $checked.length
                );
            }

            function validateAssignForm() {
                var valid = true;
                $('#assigned_to, #scheduled_date, #scheduled_time').each(function () {
                    var $el = $(this);
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
                $('#bulk_assigned_to, #bulk_scheduled_date, #bulk_scheduled_time').each(function () {
                    var $el = $(this);
                    var val = $el.val();
                    if (!val || val.trim() === '') {
                        $el.addClass('is-invalid');
                        $el.siblings('.invalid-feedback').show();
                        valid = false;
                    } else {
                        $el.removeClass('is-invalid');
                        $el.siblings('.invalid-feedback').hide();
                    }
                });

                if ($('.modal-shop-checkbox:checked').length === 0) {
                    $('#shopSelectionError').removeClass('d-none');
                    valid = false;
                } else {
                    $('#shopSelectionError').addClass('d-none');
                }
                return valid;
            }

            function handleAjaxErrors(xhr) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    $.each(xhr.responseJSON.errors, function (key, msgs) {
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

        }); // END document.ready
    </script>
@endpush