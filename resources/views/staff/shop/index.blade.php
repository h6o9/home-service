@extends('staff.master_layout')
@section('title')
    <title>{{ __('Shop List') }}</title>
@endsection
@section('staff-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Shop List') }}</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Shop List') }}</h4>
                                @if(auth('staff')->user()->hasPermission('shop_management', 'can_create'))
                                <div>
                                    <a class="btn btn-primary" href="{{ route('staff.shop.create') }}">
                                        <i class="fa fa-plus"></i> {{ __('Add New Shop') }}
                                    </a>
                                </div>
                                @endif
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

                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="shopTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Shop Name') }}</th>
                                                <th>{{ __('Owner Name') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('WhatsApp') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($shops as $index => $shop)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $shop->shop_name }}</td>
                                                <td>{{ $shop->owner_name }}</td>
                                                <td>
                                                    @php
                                                        $colors = [
                                                            'electrician' => 'primary',
                                                            'wifi_controller' => 'info',
                                                            'solar' => 'warning',
                                                            'plumber' => 'success',
                                                        ];
                                                    @endphp
                                                    <span class="badge badge-{{ $colors[$shop->category] ?? 'secondary' }}">
                                                        {{ ucfirst(str_replace('_',' ', $shop->category)) }}
                                                    </span>
                                                </td>
                                                <td>{{ $shop->phone_number }}</td>
                                                <td>{{ $shop->whatsapp_number }}</td>
                                                <td>
                                                    <a href="{{ route('staff.shop.show', $shop->id) }}" class="btn btn-info btn-sm" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    @if(auth('staff')->user()->hasPermission('shop_management', 'can_edit'))
                                                    <a href="{{ route('staff.shop.edit', $shop->id) }}" class="btn btn-primary btn-sm" title="Edit">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    @endif

                                                    @if(auth('staff')->user()->hasPermission('shop_management', 'can_delete'))
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $shop->id }}, '{{ $shop->shop_name }}')" title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    @endif

                                                    <form id="delete-form-{{ $shop->id }}" action="{{ route('staff.shop.destroy', $shop->id) }}" method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="7" class="text-center">No Shops Found</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                {{ $shops->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
    <!-- SweetAlert2 CSS and JS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Toastr CSS and JS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        "use strict";
        
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
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-hide alerts after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });

        // SweetAlert Delete Confirmation
        function confirmDelete(shopId, shopName) {
            Swal.fire({
                title: 'Are you sure?',
                html: `Are you sure, you want to delete this shop,if you will delete.it will be delete permanently.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return new Promise((resolve) => {
                        // Submit the form
                        document.getElementById(`delete-form-${shopId}`).submit();
                        resolve();
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            });
        }
        
        // Photo Modal Function
        function openPhotoModal(photoSrc) {
            Swal.fire({
                imageUrl: photoSrc,
                imageAlt: 'Shop Photo',
                showCloseButton: true,
                showConfirmButton: false,
                width: '80%',
                imageWidth: '100%',
                imageHeight: 'auto',
                padding: '1rem'
            });
        }
        
        // Search functionality
        function searchShops() {
            let input = document.getElementById("searchInput");
            let filter = input.value.toUpperCase();
            let table = document.getElementById("shopTable");
            let tr = table.getElementsByTagName("tr");
            
            for (let i = 0; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName("td");
                let found = false;
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        let textValue = td[j].textContent || td[j].innerText;
                        if (textValue.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                if (found) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
        
        // Success and Error messages with Toastr
        @if(session('success'))
            if(typeof toastr !== 'undefined') {
                toastr.success('{{ session('success') }}', 'Success!');
            } else {
                // Fallback to SweetAlert
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        @endif
        
        @if(session('error'))
            if(typeof toastr !== 'undefined') {
                toastr.error('{{ session('error') }}', 'Error!');
            } else {
                // Fallback to SweetAlert
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        @endif
    </script>
@endpush

@push('css')
    <style>
        .img-thumbnail {
            transition: transform 0.2s;
            cursor: pointer;
        }
        .img-thumbnail:hover {
            transform: scale(1.5);
            z-index: 10;
            position: relative;
        }
        .badge {
            font-size: 12px;
            padding: 5px 8px;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
@endpush