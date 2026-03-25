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
                    
                    <!-- Add Shop Card (Functional) -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card quick-action-card" data-action="add-shop" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: rgb(93, 120, 238); width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-plus" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Add Shop</h5>
                                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">Register a new shop</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shop List Card (Static - No Functionality) -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card static-card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: #cbd5e0; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-list" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Shop List</h5>
                                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">View all shops</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Navigation Card (Static - No Functionality) -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card static-card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: #cbd5e0; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-map-marker-alt" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Navigation</h5>
                                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">Find nearby shops</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Visit History Card (Static - No Functionality) -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card static-card" style="border: 1px solid #e3e3e3; border-radius: 12px; padding: 20px; cursor: pointer; transition: all 0.3s;">
                            <div style="display: flex; align-items: center;">
                                <div style="background: #cbd5e0; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <i class="fas fa-history" style="color: white; font-size: 20px;"></i>
                                </div>
                                <div>
                                    <h5 style="margin: 0; font-weight: 600; color: #34395e;">Visit History</h5>
                                    <p style="margin: 5px 0 0; color: #7a7e9a; font-size: 13px;">View visit records</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Shop Modal -->
    <div class="modal fade" id="addShopModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Shop</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Progress Steps -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="step-indicator clickable-step" data-step="1" style="cursor: pointer;">
                                <div class="step-circle" id="step1Circle" style="width: 40px; height: 40px; border-radius: 50%; background: #5d78ee; color: white; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">1</div>
                                <div class="step-label mt-2">Details</div>
                                <small id="step1Status" class="text-success" style="display: block;"></small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-indicator clickable-step" data-step="2" style="cursor: pointer;">
                                <div class="step-circle" id="step2Circle" style="width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #4a5568; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">2</div>
                                <div class="step-label mt-2">Location</div>
                                <small id="step2Status" class="text-success" style="display: block;"></small>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="step-indicator clickable-step" data-step="3" style="cursor: pointer;">
                                <div class="step-circle" id="step3Circle" style="width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #4a5568; display: inline-flex; align-items: center; justify-content: center; font-weight: bold;">3</div>
                                <div class="step-label mt-2">Photo</div>
                                <small id="step3Status" class="text-success" style="display: block;"></small>
                            </div>
                        </div>
                    </div>
                    
                    <form id="addShopForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Step 1: Details -->
                        <div id="step1" class="form-step">
                            <h5 class="mb-3">Shop Information</h5>
                            <div class="form-group">
                                <label>Shop Name <span class="text-danger">*</span></label>
                                <input type="text" name="shop_name" id="shop_name" class="form-control" placeholder="Enter shop name">
                            </div>
                            <div class="form-group">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <option value="Electrician">🔌 Electrician</option>
                                    <option value="Wifi Installer">📶 Wifi Installer</option>
                                    <option value="Solar">☀️ Solar</option>
                                    <option value="Plumber">🚰 Plumber</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Owner Name <span class="text-danger">*</span></label>
                                <input type="text" name="owner_name" id="owner_name" class="form-control" placeholder="Enter owner name">
                            </div>
                            <div class="form-group">
                                <label>Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Enter phone number">
                            </div>
                            <div class="form-group">
                                <label>WhatsApp Number</label>
                                <input type="tel" name="whatsapp_number" id="whatsapp_number" class="form-control" placeholder="Enter WhatsApp number (optional)">
                            </div>
                            <div class="form-group">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea name="address" id="address" class="form-control" rows="3" placeholder="Enter full address"></textarea>
                            </div>
                            <div class="form-group">
                                <label>About Shop</label>
                                <textarea name="about_shop" id="about_shop" class="form-control" rows="3" placeholder="Tell something about the shop (optional)"></textarea>
                            </div>
                        </div>
                        
                        <!-- Step 2: Location -->
                        <div id="step2" class="form-step" style="display: none;">
                            <h5 class="mb-3">Shop Location</h5>
                            <div class="form-group">
                                <label>Select Location on Map <span class="text-danger">*</span></label>
                                <div id="map" style="height: 400px; width: 100%; margin-bottom: 15px; border-radius: 8px;"></div>
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-arrow"></i> Use Current Location
                                </button>
                                <div class="mt-2 alert alert-info" style="font-size: 12px;">
                                    <i class="fas fa-info-circle"></i> Click on map or drag marker to set shop location
                                    <br>
                                    <strong>Latitude:</strong> <span id="latDisplay">Not selected</span> | 
                                    <strong>Longitude:</strong> <span id="lonDisplay">Not selected</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 3: Photo -->
                        <div id="step3" class="form-step" style="display: none;">
                            <h5 class="mb-3">Shop Photo</h5>
                            <div class="form-group">
                                <label>Shop Photos</label>
                                <input type="file" name="photos[]" id="shop_photos" class="form-control" multiple accept="image/*">
                                <small class="text-muted">You can select multiple photos. Max size 5MB each.</small>
                            </div>
                            <div id="photoPreview" class="row mt-3"></div>
                            <div class="mt-3 text-center">
                                <span id="photoCount" class="badge badge-info">0 photos taken</span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-secondary" id="backBtn" onclick="prevStep()" style="display: none;">Back</button>
                    <button type="button" class="btn btn-info" id="saveBtn" onclick="saveStep1()">Save</button>
                    <button type="button" class="btn btn-primary" id="nextBtn" onclick="nextStep()">Next</button>
                    <button type="button" class="btn btn-success" id="submitBtn" onclick="submitShop()" style="display: none;">Save Shop</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        let map;
        let marker;
        let currentStep = 1;
        let totalSteps = 3;
        let step1Completed = false;
        let step2Completed = false;
        
        const DEFAULT_LAT = 31.5204;
        const DEFAULT_LNG = 74.3587;
        
        // Load Google Maps API
        function loadGoogleMapsAPI() {
            return new Promise((resolve, reject) => {
                if (typeof google !== 'undefined') {
                    resolve();
                    return;
                }
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMapCallback';
                script.async = true;
                script.defer = true;
                script.onerror = () => reject(new Error('Google Maps failed to load'));
                window.initMapCallback = () => resolve();
                document.head.appendChild(script);
            });
        }
        
        $(document).ready(function() {
            // Load draft data immediately when modal opens
            $('#addShopModal').on('show.bs.modal', function() {
                loadDraftData();
            });
            
            // Add Shop button click
            $('.quick-action-card').click(function() {
                const action = $(this).data('action');
                if(action === 'add-shop') {
                    $('#addShopModal').modal('show');
                }
            });
            
            // Static cards - show coming soon message
            $('.static-card').click(function() {
                Toast.fire({
                    icon: 'info',
                    title: 'Coming Soon!',
                    text: 'This feature will be available soon.'
                });
            });
            
            // Clickable steps
            $('.clickable-step').click(function() {
                const targetStep = parseInt($(this).data('step'));    
                currentStep = targetStep;
                showStep(currentStep);
                updateStepIndicators();
            });
            
            // Photo preview
            $('#shop_photos').change(function() {
                const files = this.files;
                $('#photoCount').text(files.length + ' photos taken');
                $('#photoPreview').empty();
                for(let i = 0; i < files.length; i++) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#photoPreview').append(`<div class="col-md-3 mb-2"><img src="${e.target.result}" class="img-fluid rounded" style="height: 100px; object-fit: cover;"></div>`);
                    }
                    reader.readAsDataURL(files[i]);
                }
            });
        });
        
        // Load draft data from backend
        function loadDraftData() {
            $('#saveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
            
            $.ajax({
                url: '{{ route("staff.shops.get-draft") }}',
                type: 'GET',
                async: true,
                cache: false,
                success: function(response) {
                    if(response.has_draft && response.draft) {
                        // Step 1 data
                        if(response.step1_completed && response.draft) {
                            $('#shop_name').val(response.draft.shop_name || '');
                            $('#category').val(response.draft.category || '');
                            $('#owner_name').val(response.draft.owner_name || '');
                            $('#phone_number').val(response.draft.phone_number || '');
                            $('#whatsapp_number').val(response.draft.whatsapp_number || '');
                            $('#address').val(response.draft.address || '');
                            $('#about_shop').val(response.draft.about_shop || '');
                            step1Completed = true;
                            $('#step1Status').html('✓ Completed').css('color', 'green');
                        } else {
                            resetStep1();
                            step1Completed = false;
                            $('#step1Status').html('');
                        }
                        
                        // Step 2 data
                        if(response.step2_completed && response.draft) {
                            $('#latitude').val(response.draft.latitude || DEFAULT_LAT);
                            $('#longitude').val(response.draft.longitude || DEFAULT_LNG);
                            $('#latDisplay').text(response.draft.latitude || DEFAULT_LAT);
                            $('#lonDisplay').text(response.draft.longitude || DEFAULT_LNG);
                            step2Completed = true;
                            $('#step2Status').html('✓ Completed').css('color', 'green');
                        } else {
                            $('#latitude').val('');
                            $('#longitude').val('');
                            $('#latDisplay').text('Not selected');
                            $('#lonDisplay').text('Not selected');
                            step2Completed = false;
                            $('#step2Status').html('');
                        }
                        
                        if(step1Completed || step2Completed) {
                            Toast.fire({ icon: 'info', title: 'Saved data loaded', timer: 2000 });
                        }
                    } else {
                        resetStep1();
                        $('#latitude').val('');
                        $('#longitude').val('');
                        $('#latDisplay').text('Not selected');
                        $('#lonDisplay').text('Not selected');
                        step1Completed = false;
                        step2Completed = false;
                        $('#step1Status').html('');
                        $('#step2Status').html('');
                    }
                    
                    currentStep = 1;
                    showStep(1);
                    updateStepIndicators();
                    $('#saveBtn').prop('disabled', false).html('Save');
                },
                error: function() {
                    resetStep1();
                    step1Completed = false;
                    step2Completed = false;
                    currentStep = 1;
                    showStep(1);
                    updateStepIndicators();
                    $('#saveBtn').prop('disabled', false).html('Save');
                }
            });
        }
        
        function resetStep1() {
            $('#shop_name').val('');
            $('#category').val('');
            $('#owner_name').val('');
            $('#phone_number').val('');
            $('#whatsapp_number').val('');
            $('#address').val('');
            $('#about_shop').val('');
            $('#shop_photos').val('');
            $('#photoPreview').empty();
            $('#photoCount').text('0 photos taken');
        }
        
        // Save only Step 1 data
        function saveStep1() {
            if(!$('#shop_name').val()) {
                Toast.fire({ icon: 'error', title: 'Shop name is required' });
                $('#shop_name').focus();
                return;
            }
            if(!$('#category').val()) {
                Toast.fire({ icon: 'error', title: 'Category is required' });
                $('#category').focus();
                return;
            }
            if(!$('#owner_name').val()) {
                Toast.fire({ icon: 'error', title: 'Owner name is required' });
                $('#owner_name').focus();
                return;
            }
            if(!$('#phone_number').val()) {
                Toast.fire({ icon: 'error', title: 'Phone number is required' });
                $('#phone_number').focus();
                return;
            }
            if(!$('#address').val()) {
                Toast.fire({ icon: 'error', title: 'Address is required' });
                $('#address').focus();
                return;
            }
            
            let formData = new FormData();
            formData.append('step', 1);
            formData.append('shop_name', $('#shop_name').val());
            formData.append('category', $('#category').val());
            formData.append('owner_name', $('#owner_name').val());
            formData.append('phone_number', $('#phone_number').val());
            formData.append('whatsapp_number', $('#whatsapp_number').val());
            formData.append('address', $('#address').val());
            formData.append('about_shop', $('#about_shop').val());
            
            const saveBtn = $('#saveBtn');
            const originalHtml = saveBtn.html();
            saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
            
            $.ajax({
                url: '{{ route("staff.shops.save-draft") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        Toast.fire({ icon: 'success', title: 'Step 1 saved successfully!' });
                        step1Completed = true;
                        $('#step1Status').html('✓ Completed').css('color', 'green');
                        updateStepIndicators();
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        for(let key in errors) msg += errors[key][0] + '\n';
                        Toast.fire({ icon: 'error', title: msg });
                    } else {
                        Toast.fire({ icon: 'error', title: 'Failed to save' });
                    }
                },
                complete: function() {
                    saveBtn.prop('disabled', false).html(originalHtml);
                }
            });
        }
        
        function nextStep() {
            // if(currentStep === 1 && !step1Completed) {
            //     Toast.fire({ icon: 'warning', title: 'Please save Step 1 first' });
            //     return;
            // }
            // if(currentStep === 2 && !step2Completed) {
            //     Toast.fire({ icon: 'warning', title: 'Please select location on map' });
            //     return;
            // }
            
            if(currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
                updateStepIndicators();
            }
        }
        
        function prevStep() {
            if(currentStep > 1) {
                currentStep--;
                showStep(currentStep);
                updateStepIndicators();
            }
        }
        
        async function showStep(step) {
            $('#step1, #step2, #step3').hide();
            $('#step' + step).show();
            
            if(step === 1) {
                $('#backBtn').hide();
                $('#nextBtn').show();
                $('#saveBtn').show();
                $('#submitBtn').hide();
            } else if(step === 2) {
                $('#backBtn').show();
                $('#nextBtn').show();
                $('#saveBtn').hide();
                $('#submitBtn').hide();
                try {
                    await loadGoogleMapsAPI();
                    initMap();
                } catch(error) {
                    $('#map').html('<div class="alert alert-danger">Failed to load map</div>');
                }
            } else if(step === 3) {
                $('#backBtn').show();
                $('#nextBtn').hide();
                $('#saveBtn').hide();
                $('#submitBtn').show();
            }
        }
        
        function updateStepIndicators() {
            if(step1Completed) {
                $('#step1Circle').css('background', '#28a745').css('color', 'white');
            } else {
                $('#step1Circle').css('background', '#5d78ee').css('color', 'white');
            }
            
            if(step2Completed) {
                $('#step2Circle').css('background', '#28a745').css('color', 'white');
            } else {
                $('#step2Circle').css('background', '#e2e8f0').css('color', '#4a5568');
            }
            
            $('#step3Circle').css('background', '#e2e8f0').css('color', '#4a5568');
        }
        
        function submitShop() {
            // if(!$('#latitude').val() || !$('#longitude').val()) {
            //     Toast.fire({ icon: 'error', title: 'Please select location on map' });
            //     return;
            // }
            
            let formData = new FormData();
            formData.append('step', 2);
            formData.append('latitude', $('#latitude').val());
            formData.append('longitude', $('#longitude').val());
            
            const submitBtn = $('#submitBtn');
            const originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving location...');
            
            $.ajax({
                url: '{{ route("staff.shops.save-draft") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        step2Completed = true;
                        $('#step2Status').html('✓ Completed').css('color', 'green');
                        updateStepIndicators();
                        finalizeShop(submitBtn, originalHtml);
                    }
                },
                error: function() {
                    submitBtn.prop('disabled', false).html(originalHtml);
                    Toast.fire({ icon: 'error', title: 'Failed to save location' });
                }
            });
        }
        
        function finalizeShop(submitBtn, originalHtml) {
            let formData = new FormData();
            formData.append('step', 3);
            formData.append('shop_name', $('#shop_name').val());
            formData.append('category', $('#category').val());
            formData.append('owner_name', $('#owner_name').val());
            formData.append('phone_number', $('#phone_number').val());
            formData.append('whatsapp_number', $('#whatsapp_number').val());
            formData.append('address', $('#address').val());
            formData.append('about_shop', $('#about_shop').val());
            formData.append('latitude', $('#latitude').val());
            formData.append('longitude', $('#longitude').val());
            
            const photos = $('#shop_photos')[0].files;
            for(let i = 0; i < photos.length; i++) {
                formData.append('photos[]', photos[i]);
            }
            
            submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creating Shop...');
            
            $.ajax({
                url: '{{ route("staff.shops.save-draft") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.success) {
                        Toast.fire({ icon: 'success', title: 'Shop created successfully!' });
                        $('#addShopModal').modal('hide');
                        setTimeout(() => location.reload(), 1500);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let msg = '';
                        for(let key in errors) msg += errors[key][0] + '\n';
                        Toast.fire({ icon: 'error', title: msg });
                    } else {
                        Toast.fire({ icon: 'error', title: 'Failed to create shop' });
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalHtml);
                }
            });
        }
        
        function initMap() {
            const lat = $('#latitude').val() ? parseFloat($('#latitude').val()) : DEFAULT_LAT;
            const lng = $('#longitude').val() ? parseFloat($('#longitude').val()) : DEFAULT_LNG;
            
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 13,
                center: { lat: lat, lng: lng }
            });
            
            marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                draggable: true
            });
            
            marker.addListener('dragend', function() {
                const pos = marker.getPosition();
                updateCoordinates(pos.lat(), pos.lng());
            });
            
            map.addListener('click', function(e) {
                marker.setPosition(e.latLng);
                updateCoordinates(e.latLng.lat(), e.latLng.lng());
            });
            
            updateCoordinates(lat, lng);
        }
        
        function getCurrentLocation() {
            if(navigator.geolocation) {
                Toast.fire({ icon: 'info', title: 'Getting location...', timer: 2000 });
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    map.setCenter({ lat: lat, lng: lng });
                    marker.setPosition({ lat: lat, lng: lng });
                    updateCoordinates(lat, lng);
                    Toast.fire({ icon: 'success', title: 'Location found!' });
                }, function() {
                    Toast.fire({ icon: 'error', title: 'Could not get location' });
                });
            }
        }
        
        function updateCoordinates(lat, lng) {
            $('#latitude').val(lat);
            $('#longitude').val(lng);
            $('#latDisplay').text(lat.toFixed(6));
            $('#lonDisplay').text(lng.toFixed(6));
        }
        
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
@endpush