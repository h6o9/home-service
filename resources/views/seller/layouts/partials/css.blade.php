<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/fontawesome/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/style.css') }}?v={{ $setting?->version }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-social.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/components.css') }}" rel="stylesheet">
<link href="{{ asset('global/toastr/toastr.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/dev.css') }}?v={{ $setting?->version }}" rel="stylesheet">
@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link href="{{ asset('backend/css/rtl.css') }}?v={{ $setting?->version }}" rel="stylesheet">
    <link href="{{ asset('backend/css/dev_rtl.css') }}?v={{ $setting?->version }}" rel="stylesheet">
@endif
<link href="{{ asset('backend/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/tagify.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}" rel="stylesheet">
<link href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/iziToast.min.css') }}" rel="stylesheet">
