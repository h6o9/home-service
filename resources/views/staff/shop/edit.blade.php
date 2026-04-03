@extends('staff.master_layout')
@section('title')
    <title>{{ __('Edit Shop') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('staff.shop.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Edit Shop') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Edit Shop') }}</h4>
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

                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        {{ session('error') }}
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif

                                <form action="{{ route('staff.shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_name">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}" class="form-control clear-error" placeholder="Enter Shop Name">
                                                @error('shop_name')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category">{{ __('Category') }} <span class="text-danger">*</span></label>
                                                <select id="category" name="category" class="form-control clear-error">
                                                    <option value="">{{ __('Select Category') }}</option>
                                                    <option value="electrician" {{ old('category', $shop->category) == 'electrician' ? 'selected' : '' }}>{{ __('Electrician') }}</option>
                                                    <option value="wifi_controller" {{ old('category', $shop->category) == 'wifi_controller' ? 'selected' : '' }}>{{ __('Wifi Controller') }}</option>
                                                    <option value="solar" {{ old('category', $shop->category) == 'solar' ? 'selected' : '' }}>{{ __('Solar') }}</option>
                                                    <option value="plumber" {{ old('category', $shop->category) == 'plumber' ? 'selected' : '' }}>{{ __('Plumber') }}</option>
                                                </select>
                                                @error('category')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror   
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="owner_name">{{ __('Owner Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $shop->owner_name) }}" class="form-control clear-error" placeholder="Enter Owner Name">
                                                @error('owner_name')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone_number">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $shop->phone_number) }}" class="form-control clear-error" placeholder="Enter Phone Number">
                                                @error('phone_number')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="whatsapp_number">{{ __('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $shop->whatsapp_number) }}" class="form-control clear-error" placeholder="Enter WhatsApp Number">
                                                @error('whatsapp_number')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                                                <textarea id="address" name="address" class="form-control clear-error" rows="3" placeholder="Enter Shop Address">{{ old('address', $shop->address) }}</textarea>
                                                @error('address')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="about_shop">{{ __('About Shop') }} <span class="text-danger">*</span></label>
                                                <textarea id="about_shop" name="about_shop" class="form-control clear-error" rows="4" placeholder="Describe the shop services and details">{{ old('about_shop', $shop->about_shop) }}</textarea>
                                                @error('about_shop')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Existing Shop Photos Section -->
                                    @if($shop->photos && count($shop->photos) > 0)
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __('Current Shop Photos') }}</label>
                                                <div class="row">
                                                    @foreach($shop->photos as $photo)
                                                        <div class="col-md-3 mb-3">
                                                            <div class="position-relative">
                                                                <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Shop Photo" class="img-fluid rounded" style="height: 150px; width: 100%; object-fit: cover;">
                                                                @if($photo->is_primary)
                                                                    <span class="badge badge-primary" style="position: absolute; top: 5px; left: 5px;">Primary</span>
                                                                @endif
                                                                <form action="{{ route('staff.shop.delete-photo', $photo->id) }}" method="POST" class="delete-photo-form" style="position: absolute; top: 5px; right: 5px;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this photo?')">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <small class="form-text text-muted">{{ __('Click on delete button to remove photos. The first photo will automatically become primary if you delete the current primary photo.') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Add New Photos Section -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_photos">{{ __('Add New Shop Photos') }}</label>
                                                <input type="file" id="shop_photos" name="shop_photos[]" class="form-control clear-error" multiple accept="image/*">
                                                <small class="form-text text-muted">{{ __('You can select multiple photos. Allowed formats: jpg, jpeg, png, gif') }}</small>
                                                @error('shop_photos')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                                @error('shop_photos.*')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                                            <a href="{{ route('staff.shop.index') }}" class="btn btn-danger">{{ __('Cancel') }}</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- jQuery CDN -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
        
        // Show success/error messages with Toastr
        @if(session('success'))
            if(typeof toastr !== 'undefined') {
                toastr.success('{{ session('success') }}', 'Success!');
            }
        @endif
        
        @if(session('error'))
            if(typeof toastr !== 'undefined') {
                toastr.error('{{ session('error') }}', 'Error!');
            }
        @endif
        
        // Hide error message when user interacts with the input field
        $('.clear-error').on('click keyup focus', function() {
            $(this).closest('.form-group').find('.error-message').fadeOut('fast');
        });
        
        // Hide error message when clicking on it
        $('.error-message').on('click', function() {
            $(this).fadeOut('fast');
        });
        
        // Optional: Add AJAX form submission for delete to prevent page refresh
        $('.delete-photo-form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            
            if(confirm('Are you sure you want to delete this photo?')) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        if(response.success) {
                            toastr.success('Photo deleted successfully!', 'Success!');
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error('Failed to delete photo!', 'Error!');
                        }
                    },
                    error: function() {
                        toastr.error('An error occurred!', 'Error!');
                    }
                });
            }
        });
    });
    </script>
@endsection