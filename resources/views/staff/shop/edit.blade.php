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
                            <form action="{{ route('staff.shop.update', $shop->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    {{-- Shop Name --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="shop_name">{{ __('Shop Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', $shop->shop_name) }}" class="form-control" placeholder="Enter Shop Name">
                                            @error('shop_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Category --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category">{{ __('Category') }} <span class="text-danger">*</span></label>
                                            <select id="category" name="category" class="form-control">
                                                <option value="">{{ __('Select Category') }}</option>
                                                <option value="electrician" {{ old('category', $shop->category) == 'electrician' ? 'selected' : '' }}>{{ __('Electrician') }}</option>
                                                <option value="wifi_controller" {{ old('category', $shop->category) == 'wifi_controller' ? 'selected' : '' }}>{{ __('Wifi Controller') }}</option>
                                                <option value="solar" {{ old('category', $shop->category) == 'solar' ? 'selected' : '' }}>{{ __('Solar') }}</option>
                                                <option value="plumber" {{ old('category', $shop->category) == 'plumber' ? 'selected' : '' }}>{{ __('Plumber') }}</option>
                                            </select>
                                            @error('category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Owner Name --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="owner_name">{{ __('Owner Name') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="owner_name" name="owner_name" value="{{ old('owner_name', $shop->owner_name) }}" class="form-control" placeholder="Enter Owner Name">
                                            @error('owner_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Phone Number --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_number">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number', $shop->phone_number) }}" class="form-control" placeholder="Enter Phone Number">
                                            @error('phone_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- WhatsApp Number --}}
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="whatsapp_number">{{ __('WhatsApp Number') }} <span class="text-danger">*</span></label>
                                            <input type="text" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $shop->whatsapp_number) }}" class="form-control" placeholder="Enter WhatsApp Number">
                                            @error('whatsapp_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- Address --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="address">{{ __('Address') }} <span class="text-danger">*</span></label>
                                            <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter Shop Address">{{ old('address', $shop->address) }}</textarea>
                                            @error('address')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    {{-- About Shop --}}
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="about_shop">{{ __('About Shop') }} <span class="text-danger">*</span></label>
                                            <textarea id="about_shop" name="about_shop" class="form-control" rows="4" placeholder="Describe the shop services and details">{{ old('about_shop', $shop->about_shop) }}</textarea>
                                            @error('about_shop')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- Existing Photos --}}
                                @if($shop->photos && $shop->photos->count())
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{ __('Existing Photos') }}</label>
                                                <div class="row">
                                                    @foreach($shop->photos as $photo)
                                                        <div class="col-md-3 mb-3">
                                                            <div class="card p-2">
                                                                <img src="{{ asset('storage/'.$photo->photo_path) }}" width="100%" height="120" style="object-fit:cover; border-radius:5px;">
                                                                <form action="{{ route('staff.shop.delete-photo', $photo->id) }}" method="POST" onsubmit="return confirm('Are you sure to delete this photo?')" class="mt-2">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger btn-sm w-100">{{ __('Delete') }}</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- New Photos --}}
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="shop_photos">{{ __('Add New Photos') }}</label>
                                            <input type="file" id="shop_photos" name="shop_photos[]" class="form-control" multiple accept="image/*">
                                            <small class="form-text text-muted">{{ __('You can select multiple photos. Allowed formats: jpg, jpeg, png, gif') }}</small>
                                            @error('shop_photos')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                            @error('shop_photos.*')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">{{ __('Update Shop') }}</button>
                                        <a href="{{ route('staff.shop.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
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
@endsection