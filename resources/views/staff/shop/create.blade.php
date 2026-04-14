@extends('staff.master_layout')
@section('title')
    <title>{{ __('Create Shop') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('staff.shop.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Create Shop') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Create Shop') }}</h4>
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

                                <form action="{{ route('staff.shop.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_name">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" class="form-control clear-error" placeholder="Enter Shop Name">
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
    @foreach($shopCategories as $category)
        <option value="{{ $category->name }}" {{ old('category') == $category->name ? 'selected' : '' }}>
            {{ __($category->name) }}
        </option>
    @endforeach
</select>
                                                @error('category')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror   
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="owner_name">{{ __('Owner Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" class="form-control clear-error" placeholder="Enter Owner Name">
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
                                                <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" class="form-control clear-error" placeholder="Enter Phone Number">
                                                @error('phone_number')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="whatsapp_number">{{ __('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" class="form-control clear-error" placeholder="Enter WhatsApp Number">
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
                                                <textarea id="address" name="address" class="form-control clear-error" rows="3" placeholder="Enter Shop Address">{{ old('address') }}</textarea>
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
                                                <textarea id="about_shop" name="about_shop" class="form-control clear-error" rows="4" placeholder="Describe the shop services and details">{{ old('about_shop') }}</textarea>
                                                @error('about_shop')
                                                    <span class="text-danger error-message" style="display: inline-block;">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_photos">{{ __('Shop Photos') }}</label>
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
                                            <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
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
});
</script>

@endsection