@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Staff') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <div class="section-header-back">
                    <a href="{{ route('admin.staff.index') }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
                </div>
                <h1>{{ __('Edit Staff') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Edit Staff') }}</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.staff.update', $staff->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="name">{{ __('Name') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="name" name="name" value="{{ $staff->name }}" class="form-control clear-error" placeholder="Enter Name">

                                                @error('name')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email">{{ __('Email') }} <span class="text-danger">*</span></label>
                                                <input type="email" id="email" name="email" value="{{ $staff->email }}" class="form-control clear-error" placeholder="Enter Email">

                                                @error('email')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror       
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="phone">{{ __('Phone') }} <span class="text-danger">*</span></label>
                                                <input type="text" id="phone" name="phone" value="{{ $staff->phone }}" class="form-control clear-error" placeholder="Enter Phone">

                                                @error('phone')
                                                    <span class="text-danger error-message">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
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

<!-- jQuery CDN add karo -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script>
$(document).ready(function() {
    // Jab bhi input field mein click, type ya focus karo
    $('.clear-error').on('click keyup focus', function() {
        // Uske parent form-group mein jo error-message hai use hide karo
        $(this).closest('.form-group').find('.error-message').fadeOut('fast');
    });
    
    // Agar error message par click karo to bhi hide ho
    $('.error-message').on('click', function() {
        $(this).fadeOut('fast');
    });
});
</script>

@endsection