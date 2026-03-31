@extends('staff.master_layout')
@section('title')
    <title>{{ __('Shop List') }}</title>
@endsection
@section('admin-content')
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
                                <div>
                                    <a class="btn btn-primary" href="{{ route('staff.shop.create') }}">
                                        <i class="fa fa-plus"></i> {{ __('Add New Shop') }}
                                    </a>
                                </div>
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
                                                <th>{{ __('Photos') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shops as $index => $shop)
                                                <tr>
                                                    <td>{{ $shops->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ $shop->shop_name }}</strong>
                                                        @if($shop->primaryPhoto)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-image"></i> 
                                                                {{ $shop->photos->count() }} {{ __('photo(s)') }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $shop->owner_name }}</td>
                                                    <td>
                                                        @php
                                                            $categoryColors = [
                                                                'electrician' => 'primary',
                                                                'wifi_controller' => 'info',
                                                                'solar' => 'warning',
                                                                'plumber' => 'success',
                                                            ];
                                                            $color = $categoryColors[$shop->category] ?? 'secondary';
                                                        @endphp
                                                        <span class="badge badge-{{ $color }}">
                                                            {{ $shop->category_label }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $shop->phone_number }}</td>
                                                    <td>{{ $shop->whatsapp_number }}</td>
                                                    <td>
                                                        @if($shop->photos && $shop->photos->count() > 0)
                                                            <div class="d-flex">
                                                                @foreach($shop->photos->take(3) as $photo)
                                                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                                                         alt="Shop Photo" 
                                                                         style="width: 40px; height: 40px; object-fit: cover; border-radius: 5px; margin-right: 5px;"
                                                                         class="img-thumbnail"
                                                                         data-toggle="tooltip"
                                                                         title="{{ $photo->is_primary ? 'Primary Photo' : 'Shop Photo' }}">
                                                                @endforeach
                                                                @if($shop->photos->count() > 3)
                                                                    <span class="badge badge-secondary align-self-center">
                                                                        +{{ $shop->photos->count() - 3 }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-muted">{{ __('No photos') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a class="btn btn-info btn-sm" 
                                                               href="{{ route('staff.shop.show', $shop->id) }}"
                                                               data-toggle="tooltip" 
                                                               title="{{ __('View Shop') }}">
                                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                                            </a>
                                                            <a class="btn btn-primary btn-sm" 
                                                               href="{{ route('staff.shop.edit', $shop->id) }}"
                                                               data-toggle="tooltip" 
                                                               title="{{ __('Edit Shop') }}">
                                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                            <a class="btn btn-danger btn-sm" 
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#deleteModal" 
                                                               href="javascript:;"
                                                               onclick="deleteData({{ $shop->id }})"
                                                               data-toggle="tooltip" 
                                                               title="{{ __('Delete Shop') }}">
                                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
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

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('Delete Shop') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to delete this shop?') }}</p>
                    <p class="text-danger">{{ __('This action cannot be undone! All shop photos will also be deleted.') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <form id="deleteForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        "use strict";
        
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Auto-hide alerts after 3 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        });

        function deleteData(id) {
            let url = '{{ route('staff.shop.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
        }
        
        // Optional: Search functionality
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