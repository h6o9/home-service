@extends('auth.layout')

@section('title', __('Login'))

@section('content')
    @php
        $loginSection = getSection('login_page', false);
    @endphp

    <section class="wsus__login">
        <div class="row align-items-center">
            <div class="col-lg-5 col-xl-5">
                <div class="wsus__login_img">
                    <img class="img-fluid w-100"
                        src="{{ asset(sectionData(sections: $loginSection, sectionName: 'login_page', propertyName: 'login_image')) }}"
                        alt="login">
                    <a class="logo" href="{{ route('website.home') }}">
                        <img class="img-fluid w-100"
                            src="{{ asset(sectionData(sections: $loginSection, sectionName: 'login_page', propertyName: 'logo_image')) }}"
                            alt="logo">
                    </a>
                </div>
            </div>

            @if ($loginSection->login_page->status)
                <div class="col-lg-7 col-xl-7 wow fadeInUp">
                    @include('components::login-form', ['loginSection' => $loginSection])
                </div>
            @endif
        </div>
        <a class="back_home" href="{{ route('website.home') }}">{{ __('Back to Home') }}</a>
    </section>
@endsection
