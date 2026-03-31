@extends('staff.master_layout')
@section('title')
    <title>{{ __('Create Shop') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('admin.shop.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
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
                                <form action="{{ route('admin.shop.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_name">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" class="form-control clear-error" placeholder="Enter Shop Name">
                                                @error('shop_name')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="category">{{ __('Category') }} <span class="text-danger">*</span></label>
                                                <select id="category" name="category" class="form-control clear-error">
                                                    <option value="">{{ __('Select Category') }}</option>
                                                    <option value="electrician" {{ old('category') == 'electrician' ? 'selected' : '' }}>{{ __('Electrician') }}</option>
                                                    <option value="wifi_controller" {{ old('category') == 'wifi_controller' ? 'selected' : '' }}>{{ __('Wifi Controller') }}</option>
                                                    <option value="solar" {{ old('category') == 'solar' ? 'selected' : '' }}>{{ __('Solar') }}</option>
                                                    <option value="plumber" {{ old('category') == 'plumber' ? 'selected' : '' }}>{{ __('Plumber') }}</option>
                                                </select>
                                                @error('category')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror   
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="owner_name">{{ __('Owner Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name') }}" class="form-control clear-error" placeholder="Enter Owner Name">
                                                @error('owner_name')
                                                    <span class="text-danger error-message">{{ $message }}</span>
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
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="whatsapp_number">{{ __('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number') }}" class="form-control clear-error" placeholder="Enter WhatsApp Number">
                                                @error('whatsapp_number')
                                                    <span class="text-danger error-message">{{ $message }}</span>
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
                                                    <span class="text-danger error-message">{{ $message }}</span>
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
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="shop_photos">{{ __('Shop Photos') }}</label>
                                                <input type="file" id="shop_photos" name="shop_photos[]" class="form-control" multiple accept="image/*">
                                                <small class="form-text text-muted">{{ __('You can select multiple photos. Allowed formats: jpg, jpeg, png, gif') }}</small>
                                                @error('shop_photos')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                                @error('shop_photos.*')
                                                    <span class="text-danger error-message">{{ $message }}</span>
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

<script>
$(document).ready(function() {
    // Input field mein click ya type karne par error hide ho jaye
    $('.clear-error').on('click keyup focus', function() {
        $(this).closest('.form-group').find('.error-message').fadeOut('fast');
    });
    
    // Agar error message par click karo to bhi hide ho
    $('.error-message').on('click', function() {
        $(this).fadeOut('fast');
    });
});
</script>

@endsection