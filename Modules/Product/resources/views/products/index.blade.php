@extends('admin.master_layout')
@section('title')
    <title>{{ __('Product List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-2 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="product_filter_form" action="" method="GET">
                                    <div class="row">
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('Name - ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('Name - DESC') }}
                                                    </option>
                                                    <option value="id_asc"
                                                        {{ request('order_by') == 'id_asc' ? 'selected' : '' }}>
                                                        {{ __('Created At - ASC') }}
                                                    </option>
                                                    <option value="id_desc"
                                                        {{ request('order_by') == 'id_desc' ? 'selected' : '' }}>
                                                        {{ __('Created At - DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="par-page" name="par-page">
                                                    <option value="">{{ __('Per Page') }}</option>
                                                    <option value="10"
                                                        {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('10') }}
                                                    </option>
                                                    <option value="50"
                                                        {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('50') }}
                                                    </option>
                                                    <option value="100"
                                                        {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('100') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">{{ __('Status') }}</option>
                                                    <option value="published" @selected(request('status') == 'published')>
                                                        {{ __('Published') }}
                                                    </option>
                                                    <option value="hidden" @selected(request('status') == 'hidden')>
                                                        {{ __('Hidden') }}
                                                    </option>
                                                    <option value="approved" @selected(request('status') == 'approved')>
                                                        {{ __('Approved') }}
                                                    </option>
                                                    <option value="pending" @selected(request('status') == 'pending')>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="flash_deal" @selected(request('status') == 'flash_deal')>
                                                        {{ __('Flash Deal') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" id="brand_id" name="brand_id">
                                                    <option value="" selected disabled>{{ __('Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @selected(request('brand_id') == $brand->id)>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control select2" id="categories" name="category_id">
                                                    <option value="" selected disabled>{{ __('Categories') }}
                                                    </option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        @if (isRoute('admin.seller.products.index'))
                                            <div class="col-xl-2 col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control select2" id="vendor_id" name="vendor_id">
                                                        <option value="" selected disabled>{{ __('Select Seller') }}
                                                        </option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor->id }}"
                                                                @selected(request('vendor_id') == $vendor->id)>
                                                                {{ $vendor->shop_name }} ({{ $vendor->user->name ?? '' }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-2 col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control" id="seller_status" name="seller_status">
                                                        <option value="">{{ __('Seller Status') }}</option>
                                                        <option value="verified" @selected(request('seller_status') == 'verified')>
                                                            {{ __('KYC Verified') }}
                                                        </option>
                                                        <option value="pending" @selected(request('seller_status') == 'pending')>
                                                            {{ __('KYC Non-Verified') }}
                                                        </option>
                                                        <option value="published" @selected(request('status') == 'published')>
                                                            {{ __('Shop Published') }}
                                                        </option>
                                                        <option value="hidden" @selected(request('status') == 'hidden')>
                                                            {{ __('Shop Hidden') }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mt-1">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Product List')" />
                                @adminCan('product.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.product.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table product_list_table min-height-600" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Photo') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                @if (isRoute('admin.seller.products.index'))
                                                    <th>
                                                        {{ __('Seller') }}
                                                    </th>
                                                @endif
                                                <th>{{ __('Stock') }}</th>
                                                <th>{{ __('Original Price') }}</th>
                                                <th>{{ __('After Disc. P.') }}</th>
                                                @adminCan('product.status')
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Approved') }}</th>
                                                @endadminCan
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($products as $index => $product)
                                                <tr>
                                                    <td>{{ 'all' == request('par-page') ? $index + 1 : $products->firstItem() + $index }}
                                                    </td>
                                                    <td> <img class="rounded-circle m-1"
                                                            src="{{ asset($product->thumbnail_image) }}">
                                                    </td>
                                                    <td>
                                                        <a
                                                            href="{{ route('website.product', ['product' => $product->slug]) }}">{{ $product->name }}</a>
                                                        - {{ $product->brand->name }} <br>
                                                        {{ __('SKU') }}: {{ $product->sku }} @if ($product->is_flash_deal)
                                                            <span
                                                                class="badge bg-success p-1">{{ __('Flash Deal') }}</span>
                                                        @endif
                                                        @if ($product->has_variant)
                                                            <br>
                                                            {{ $product->variants->count() ?? 0 }} {{ __('Variants') }}
                                                        @endif
                                                    </td>
                                                    @if (isRoute('admin.seller.products.index'))
                                                        <td>
                                                            @if ($product->vendor)
                                                                <span>
                                                                    <a href="">
                                                                        {{ $product?->vendor?->shop_name ?? __('N/A') }}
                                                                        @if ($product?->vendor?->is_verified ?? false)
                                                                            <i class="fas fa-check-circle text-success"
                                                                                title="{{ __('Verified') }}">
                                                                            </i>
                                                                        @else
                                                                            <i class="fas fa-times-circle text-danger"
                                                                                title="{{ __('Not Verified') }}">
                                                                            </i>
                                                                        @endif
                                                                    </a>
                                                                </span>
                                                            @else
                                                                {{ __('N/A') }}
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if ($product->manage_stock && $product->stock_status == 'in_stock')
                                                            {{ $product->stock_qty }} {{ $product->unit?->name ?? '' }}
                                                        @elseif(!$product->manage_stock)
                                                            {{ __('Unmanaged') }}
                                                        @else
                                                            {{ __('Out of Stock') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ currency($product->price) }}</td>
                                                    <td>
                                                        @if ($product->discounted_price->is_discounted)
                                                            {{ currency($product->discounted_price->discounted_price) }}
                                                            ({{ $product->discounted_price->discount_percent }}%)
                                                        @else
                                                            {{ __('No Discount') }}
                                                        @endif
                                                    </td>
                                                    @adminCan('product.status')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Published') }}"
                                                                data-offlabel="{{ __('Hidden') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="status({{ $product->id }})"
                                                                {{ $product->status ? 'checked' : '' }}>
                                                        </td>
                                                        <td>
                                                            <input id="is_approved_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Approved') }}"
                                                                data-offlabel="{{ __('Pending') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="approveProduct({{ $product->id }})"
                                                                {{ $product->is_approved ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-primary dropdown-toggle"
                                                                id="dropdownMenuButton{{ $product->id }}"
                                                                data-bs-toggle="dropdown" type="button"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                {{ __('Action') }}
                                                            </button>

                                                            <div class="dropdown-menu"
                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);"
                                                                x-placement="top-start">
                                                                @if (!$product->is_flash_deal)
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.product.product-variant', ['id' => $product->id]) }}"></i>
                                                                        {{ __('Product Variant') }}
                                                                        ({{ $product->variants->count() ?? 0 }})</a>
                                                                @endif

                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.product.show', ['product' => $product->id]) }}"></i>
                                                                    {{ __('Details') }}</a>

                                                                @adminCan('product.edit')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.product-gallery', ['id' => $product->id]) }}">
                                                                        {{ __('Gallery') }}
                                                                        ({{ $product->gallery->count() ?? 0 }})</a>
                                                                @endadminCan

                                                                @adminCan('product.edit')
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('admin.product.edit', ['product' => $product->id, 'code' => getSessionLanguage()]) }}">
                                                                        {{ __('Edit') }}
                                                                        ({{ $product->updated_at
                                                                            ? $product->updated_at->diffForHumans([
                                                                                'parts' => 1,
                                                                                'short' => true,
                                                                            ])
                                                                            : $product->created_at->diffForHumans([
                                                                                'parts' => 1,
                                                                                'short' => true,
                                                                            ]) }})</a>
                                                                @endadminCan

                                                                <a class="dropdown-item"
                                                                    href="{{ route('admin.product-review', ['product' => $product->slug]) }}">

                                                                    {{ __('Reviews') }} <span
                                                                        class="text-warning">({{ $product->pending_reviews_count ?? 0 }})</span></a>

                                                                @adminCan('product.delete')
                                                                    <a class="dropdown-item" href="javascript:;"
                                                                        @if ($product->orders->count() > 0) data-bs-target="#canNotDeleteModal"
                                                                        @else onclick="deleteData({{ $product->id }})" @endif>{{ __('Delete') }}</a>
                                                                @endadminCan
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-5">
                                        {{ $products->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{ __('You can not delete this product. Because there are one or more order has been created in this product.') }}
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            'use strict';
            $('.productView').on('click', function() {
                var id = $(this).data('id');
                let url = '{{ route('admin.product.view', ':id') }}';
                url = url.replace(':id', id);
                $.ajax({
                    type: "GET",
                    url,
                    success: function(response) {
                        $('#productView .modal-content').html(response.product);
                        $('#productView').modal('show');
                    }
                });
            })

            $('input[name="select"]').on('click', function() {
                var total = $('input[name="select"]').length;
                var number = $('input[name="select"]:checked').length;
                if (total == number) {
                    $('#checkbox-all').prop('checked', true);
                } else {
                    $('#checkbox-all').prop('checked', false);
                }
                $('.number').text(number);

                if (number > 0) {
                    $('.delete-section').removeClass('d-none');
                    $('.delete-section').addClass('d-flex');
                } else {
                    $('.delete-section').addClass('d-none');
                    $('.delete-section').removeClass('d-flex');
                }
            });

            // delete all selected
            $('.delete-button').on('click', function() {
                var ids = [];
                $('input[name="select"]:checked').each(function() {
                    ids.push($(this).attr('id').split('-')[1]);
                });
            });

            $('#product_filter_form').on('change', function() {
                $('#product_filter_form').submit();
            });
        });

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.product.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }

        function status(id) {
            handleStatus("{{ route('admin.product.status', ':id') }}".replace(':id', id));

            let status = $('[data-status=' + id + ']').text()
            // remove whitespaces using regex
            status = status.replaceAll(/\s/g, '');
            $('[data-status=' + id + ']').text(status != 'Hidden' ? 'Hidden' : 'Published');
        }

        function approveProduct(id) {
            handleStatus("{{ route('admin.product.approve', ':id') }}".replace(':id', id));
        }
    </script>
@endpush

@push('css')
    <style>
        .min-height-600 {
            min-height: 600px !important;
        }
    </style>
@endpush
