@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product List') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="product_filter_form" action="" method="GET">
                                    <div class="row">
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
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
                                        <div class="col-xxl-2 col-md-3">
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
                                        <div class="col-xxl-2 col-md-3">
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
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="brand_id" name="brand_id">
                                                    <option value="" selected disabled>{{ __('Brand') }}
                                                    </option>
                                                    @foreach ($brands as $brand)
                                                        <option value="{{ $brand->id }}" @selected($brand->id == request('brand_id'))>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-2 col-md-3">
                                            <div class="form-group">
                                                <select class="form-control select2" id="categories" name="category_id">
                                                    <option value="" selected disabled>{{ __('Categories') }}
                                                    </option>
                                                    @foreach ($categories as $cat)
                                                        <option value="{{ $cat->id }}" @selected($cat->id == request('category_id'))>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card mt-5">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Product List')" />

                                <div>
                                    <x-admin.add-button :href="route('seller.product.create')" />
                                </div>

                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table product_list_table min-height-600" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Photo') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Stock') }}</th>
                                                <th>{{ __('Original Price') }}</th>
                                                <th>{{ __('After Disc. P.') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Approved') }}</th>
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
                                                    </td>
                                                    <td>
                                                        @if ($product->manage_stock && $product->stock_status == 'in_stock')
                                                            {{ $product->stock_qty }} {{ $product->unit?->name ?? '' }}
                                                        @else
                                                            {{ __('Out of Stock') }}
                                                        @endif
                                                    </td>
                                                    <td>{{ currency($product->current_price) }}</td>
                                                    <td>
                                                        @if ($product->discounted_price->is_discounted)
                                                            {{ currency($product->discounted_price->discounted_price) }}
                                                            ({{ $product->discounted_price->discount_percent }}%)
                                                        @else
                                                            {{ __('No Discount') }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input id="status_toggle" data-toggle="toggle"
                                                            data-onlabel="{{ __('Published') }}"
                                                            data-offlabel="{{ __('Hidden') }}" data-onstyle="success"
                                                            data-offstyle="danger" type="checkbox"
                                                            onchange="status({{ $product->id }})"
                                                            {{ $product->status ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge {{ $product->is_approved ? 'badge-success' : 'badge-danger' }}">
                                                            {{ $product->is_approved ? __('Approved') : __('Pending') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-primary btn-sm dropdown-toggle"
                                                                id="dropdownMenuButton{{ $product->id }}"
                                                                data-bs-toggle="dropdown" type="button"
                                                                aria-haspopup="true" aria-expanded="false">
                                                                {{ __('Action') }}
                                                            </button>

                                                            <div class="dropdown-menu"
                                                                style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -131px, 0px);"
                                                                x-placement="top-start">
                                                                {{-- <a class="dropdown-item productView"
                                                                    data-id="{{ $product->id }}" href="javascript:;">
                                                                    {{ __('View') }}</a> --}}

                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.product.show', ['product' => $product->id]) }}"></i>
                                                                    {{ __('Details') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.product.product-variant', ['id' => $product->id]) }}"></i>
                                                                    {{ __('Product Variant') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.product-gallery', ['id' => $product->id]) }}">
                                                                    {{ __('Gallery') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.product.edit', ['product' => $product->id, 'code' => getSessionLanguage()]) }}">

                                                                    {{ __('Edit') }}</a>

                                                                <a class="dropdown-item"
                                                                    href="{{ route('seller.product-review', ['product' => $product->slug]) }}">

                                                                    {{ __('Reviews') }}</a>

                                                                <a class="dropdown-item" href="javascript:;"
                                                                    @if ($product->orders->count() > 0) data-bs-target="#canNotDeleteModal"
                                                                        @else onclick="deleteData({{ $product->id }})" @endif>{{ __('Delete') }}</a>

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

    <div class="modal fade" id="productView" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
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
                let url = '{{ route('seller.product.view', ':id') }}';
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
            var url = '{{ route('seller.product.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }

        function status(id) {
            handleStatus("{{ route('seller.product.status', ':id') }}".replace(':id', id));

            let status = $('[data-status=' + id + ']').text()
            // remove whitespaces using regex
            status = status.replaceAll(/\s/g, '');
            $('[data-status=' + id + ']').text(status != 'Hidden' ? 'Hidden' : 'Published')
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
