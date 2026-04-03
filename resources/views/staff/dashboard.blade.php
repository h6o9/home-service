@extends('staff.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('staff-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Dashboard') }}</h1>
            </div>

            <div class="section-body">
                <!-- Staff Dashboard Cards -->
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px;">
                            <div style="display: flex; flex-direction: column;">
                                <span style="color: rgba(255,255,255,0.8); font-size: 14px;">Total Shops</span>
                                <span style="color: white; font-size: 42px; font-weight: 700;">{{ $totalShops ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px;">
                            <div style="display: flex; flex-direction: column;">
                                <span style="color: rgba(255,255,255,0.8); font-size: 14px;">Visited Today</span>
                                <span style="color: white; font-size: 42px; font-weight: 700;">{{ $visitedToday ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="card" style="background: rgb(93, 120, 238); border-radius: 15px; padding: 20px;">
                            <div style="display: flex; flex-direction: column;">
                                <span style="color: rgba(255,255,255,0.8); font-size: 14px;">Pending Visits</span>
                                <span style="color: white; font-size: 42px; font-weight: 700;">{{ $pendingVisits ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h4 style="margin-bottom: 20px; font-weight: 600; color: #34395e;">Quick Actions</h4>
                    </div>
                    
                    @if(auth('staff')->user()->hasPermission('shop_management', 'can_create'))
                    <!-- Shop Management Card -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card quick-action-card" data-action="add-shop" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-plus" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Add Shop</h5>
                                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">Manage shops</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if(auth('staff')->user()->hasPermission('shop_management', 'can_view'))
                    <!-- Shop List Card -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <a href="{{ route('staff.shop.index') }}" class="text-decoration-none">
                            <div class="card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                                <div style="display: flex; align-items: center;">
                                    <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                        <i class="fas fa-list" style="color: white; font-size: 20px;"></i>
                                    </div>
                                    <div>
                                        <h5 style="margin: 0; font-weight: 600; color: #34395e;">Shop List</h5>
                                        <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">View all shops</p>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </section>
    </div>

    @if(auth('staff')->user()->hasPermission('shop_management', 'can_create'))
    <!-- Add Shop Modal (Single Step Form) -->
    <div class="modal fade" id="addShopModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Shop</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addShopForm" action="{{ route('staff.shop.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="shop_name">Shop Name <span class="text-danger">*</span></label>
                                    <input type="text" id="shop_name" name="shop_name" class="form-control clear-error" placeholder="Enter Shop Name">
                                    <span class="text-danger error-message" id="error-shop_name" style="display: none;"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="category">Category <span class="text-danger">*</span></label>
                                    <select id="category" name="category" class="form-control clear-error">
                                        <option value="">Select Category</option>
                                        <option value="electrician">🔌 Electrician</option>
                                        <option value="wifi_controller">📶 Wifi Controller</option>
                                        <option value="solar">☀️ Solar</option>
                                        <option value="plumber">🚰 Plumber</option>
                                    </select>
                                    <span class="text-danger error-message" id="error-category" style="display: none;"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="owner_name">Owner Name <span class="text-danger">*</span></label>
                                    <input type="text" id="owner_name" name="owner_name" class="form-control clear-error" placeholder="Enter Owner Name">
                                    <span class="text-danger error-message" id="error-owner_name" style="display: none;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" id="phone_number" name="phone_number" class="form-control clear-error" placeholder="Enter Phone Number">
                                    <span class="text-danger error-message" id="error-phone_number" style="display: none;"></span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp_number">WhatsApp Number <span class="text-danger">*</span></label>
                                    <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control clear-error" placeholder="Enter WhatsApp Number">
                                    <span class="text-danger error-message" id="error-whatsapp_number" style="display: none;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Address <span class="text-danger">*</span></label>
                                    <textarea id="address" name="address" class="form-control clear-error" rows="3" placeholder="Enter Shop Address"></textarea>
                                    <span class="text-danger error-message" id="error-address" style="display: none;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="about_shop">About Shop <span class="text-danger">*</span></label>
                                    <textarea id="about_shop" name="about_shop" class="form-control clear-error" rows="4" placeholder="Describe the shop services and details"></textarea>
                                    <span class="text-danger error-message" id="error-about_shop" style="display: none;"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="shop_photos">Shop Photos</label>
                                    <input type="file" id="shop_photos" name="shop_photos[]" class="form-control clear-error" multiple accept="image/*">
                                    <small class="form-text text-muted">You can select multiple photos. Allowed formats: jpg, jpeg, png, gif (Max 2MB each)</small>
                                    <span class="text-danger error-message" id="error-shop_photos" style="display: none;"></span>
                                </div>
                                <div id="photoPreview" class="row mt-2"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Save Shop</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection

@push('css')
    <style>
        /* Responsive Design for Mobile */
        @media (max-width: 768px) {
            .quick-action-card {
                margin-bottom: 15px !important;
            }
            
            .quick-action-card .card {
                padding: 15px !important;
            }
            
            .quick-action-card .card h5 {
                font-size: 16px !important;
                margin-bottom: 5px !important;
            }
            
            .quick-action-card .card p {
                font-size: 12px !important;
                margin: 0 !important;
            }
            
            .quick-action-card .card .d-flex {
                align-items: center !important;
            }
            
            .quick-action-card .card .d-flex > div:first-child {
                width: 40px !important;
                height: 40px !important;
                margin-right: 10px !important;
            }
            
            .quick-action-card .card .d-flex > div:first-child i {
                font-size: 16px !important;
            }
            
            .section-header h1 {
                font-size: 24px !important;
            }
            
            .modal-dialog {
                margin: 10px !important;
                max-width: calc(100% - 20px) !important;
            }
            
            .form-group {
                margin-bottom: 15px !important;
            }
            
            .form-group label {
                font-size: 14px !important;
                margin-bottom: 5px !important;
            }
            
            .form-control {
                font-size: 14px !important;
                padding: 8px 12px !important;
            }
            
            .btn {
                font-size: 14px !important;
                padding: 8px 16px !important;
            }
            
            .modal-footer {
                flex-wrap: wrap !important;
                gap: 5px !important;
            }
            
            .modal-footer .btn {
                flex: 1 !important;
                min-width: 80px !important;
            }
        }
        
        @media (max-width: 576px) {
            .quick-action-card .col-lg-3,
            .quick-action-card .col-md-6 {
                flex: 0 0 100% !important;
                max-width: 100% !important;
            }
            
            .section-header h1 {
                font-size: 20px !important;
            }
            
            .modal-body {
                padding: 15px !important;
            }
        }
        
        /* Touch-friendly for mobile */
        @media (hover: none) and (pointer: coarse) {
            .quick-action-card:active {
                transform: scale(0.98) !important;
            }
            
            .btn {
                min-height: 44px !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            
            .form-control {
                min-height: 44px !important;
            }
        }
        
        /* Photo preview styling */
        #photoPreview img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 8px;
            margin: 5px;
            border: 1px solid #ddd;
        }
    </style>
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <script>
        $(document).ready(function() {
            // Configure Toastr
            if(typeof toastr !== 'undefined') {
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };
            }
            
            // Add Shop button click - Open modal
            $('.quick-action-card').click(function() {
                const action = $(this).data('action');
                if(action === 'add-shop') {
                    resetForm();
                    $('#addShopModal').modal('show');
                }
            });
            
            // Clear error on input focus
            $(document).on('focus keyup click', '.clear-error', function() {
                const fieldName = $(this).attr('name');
                if(fieldName) {
                    const errorId = '#error-' + fieldName.replace('[]', '');
                    $(errorId).hide().empty();
                }
                $(this).removeClass('is-invalid');
            });
            
            // Photo preview
            $('#shop_photos').on('change', function() {
                const files = this.files;
                $('#photoPreview').empty();
                
                for(let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photoPreview').append(`<img src="${e.target.result}" class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover; margin: 5px;">`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });
            
            // Form submit with AJAX
            $('#addShopForm').on('submit', function(e) {
                e.preventDefault();
                
                // Clear all previous errors
                $('.error-message').hide().empty();
                $('.clear-error').removeClass('is-invalid');
                
                const submitBtn = $('#submitBtn');
                const originalHtml = submitBtn.html();
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Creating Shop...');
                
                const formData = new FormData(this);
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            // Show success toast
                            if(typeof toastr !== 'undefined') {
                                toastr.success('Shop created successfully!', 'Success');
                            } else {
                                // Fallback to SweetAlert
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Shop created successfully!',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                            
                            $('#addShopModal').modal('hide');
                            resetForm();
                            
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        if(xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            
                            // Display each error
                            for(let field in errors) {
                                const errorMsg = errors[field][0];
                                const errorId = '#error-' + field.replace('[]', '');
                                $(errorId).text(errorMsg).show();
                                $(`[name="${field}"]`).addClass('is-invalid');
                            }
                            
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please check the form for errors.',
                                timer: 3000,
                                showConfirmButton: true
                            });
                        } else if(xhr.status === 403) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Permission Denied',
                                text: xhr.responseJSON.message || 'You do not have permission to create shops.',
                                timer: 3000,
                                showConfirmButton: true
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong. Please try again.',
                                timer: 3000,
                                showConfirmButton: true
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                });
            });
        });
        
        function resetForm() {
            $('#addShopForm')[0].reset();
            $('.error-message').hide().empty();
            $('.clear-error').removeClass('is-invalid');
            $('#photoPreview').empty();
        }
    </script>
@endpush