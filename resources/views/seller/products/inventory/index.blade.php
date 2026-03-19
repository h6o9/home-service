@extends('seller.layouts.master')

@section('title')
    <title>{{ __('Product Price List') }}</title>
@endsection

@section('seller-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product Price List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Product Price List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form id="search-form" action="" method="GET">
                                    <div class="row">
                                        <div class="col-xxl-6 col-md-12">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}..." autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" id="par-page" name="par-page">
                                                    <option value="">{{ __('Per Page') }}</option>
                                                    <option value="5" @selected(request('par-page') == '5')>
                                                        {{ __('5') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="10" @selected(request('par-page') == '10')>
                                                        {{ __('10') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="50" @selected(request('par-page') == '50')>
                                                        {{ __('50') }} {{ __('Products') }}
                                                    </option>
                                                    <option value="100" @selected(request('par-page') == '100')>
                                                        {{ __('100') }} {{ __('Products') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-3 col-md-4">
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
                                        <div class="col-xxl-3 col-md-4">
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
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Product Price List')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Sku') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Manage Stock') }}</th>
                                                <th>{{ __('Stock Quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($products as $index => $product)
                                                @if ($product->has_variant)
                                                    <tr class="text-muted">
                                                        <td>
                                                            <b>{{ $product->sku }}</b>
                                                        </td>
                                                        <td>
                                                            <b>{{ $product->name }}</b>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <input class="form-check-input mt-0 update-stock"
                                                                        id="manage_stock-{{ $product->id }}"
                                                                        data-sku="{{ $product->sku }}"
                                                                        data-field="manage_stock"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="checkbox" value="1"
                                                                        @checked(optional($product)->manage_stock == 1)>
                                                                </div>

                                                                <select
                                                                    class="form-select update-stock"
                                                                    id="stock_status-{{ $product->id }}"
                                                                    data-field="stock_status"
                                                                    data-sku="{{ $product->sku }}"
                                                                    data-product-id="{{ $product->id }}"
                                                                    @disabled(optional($product)->manage_stock !== 1)>
                                                                    <option value="">{{ __('Select') }}
                                                                    </option>
                                                                    <option value="in_stock" @selected($product->stock_status == 'in_stock')>
                                                                        {{ __('In Stock') }}
                                                                    </option>
                                                                    <option value="out_of_stock"
                                                                        @selected($product->stock_status == 'out_of_stock')>
                                                                        {{ __('Out of Stock') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            ---
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $product = $product->loadMissing([
                                                            'variants.product.unit',
                                                            'variants.optionValues.translation',
                                                            'variants.optionValues.attribute.translation',
                                                        ]);
                                                    @endphp
                                                    @foreach ($product->variants as $variant)
                                                        <tr>
                                                            <td>{{ $variant->sku }}</td>
                                                            <td>
                                                                @if ($variant->optionValues)
                                                                    @foreach ($variant->optionValues as $option)
                                                                        <span class="badge badge-info">
                                                                            {{ $option->attribute->name }}:
                                                                            {{ $option->name }}
                                                                        </span>
                                                                    @endforeach
                                                                @endif
                                                            </td>
                                                            <td>
                                                                ------
                                                            </td>
                                                            <td>
                                                                <div class="input-group">
                                                                    <input
                                                                        class="form-control update-stock stock-product-variant-{{ $product->id }}"
                                                                        id="stock_qty-{{ $variant->id }}" name="stock_qty"
                                                                        data-sku="{{ $variant->sku }}"
                                                                        data-is-variant="true" data-field="stock_qty"
                                                                        data-product-variant-id="{{ $variant->id }}"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="number"
                                                                        value="{{ old('stock_qty', $variant->stock_qty) }}"
                                                                        placeholder="{{ __('Stock Quantity') }}"
                                                                        @disabled(optional($product)->manage_stock !== 1 || optional($product)->stock_status == 'out_of_stock')>

                                                                    <span
                                                                        class="input-group-text">{{ optional($variant->product->unit)->name }}</span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td>
                                                            {{ $product->sku }}
                                                        </td>
                                                        <td>
                                                            {{ $product->name }}
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <div class="input-group-text">
                                                                    <input class="form-check-input mt-0 update-stock"
                                                                        id="manage_stock-{{ $product->id }}"
                                                                        data-sku="{{ $product->sku }}"
                                                                        data-field="manage_stock"
                                                                        data-product-id="{{ $product->id }}"
                                                                        type="checkbox" value="1"
                                                                        @checked(optional($product)->manage_stock == 1)>
                                                                </div>

                                                                <select
                                                                    class="form-select update-stock"
                                                                    id="stock_status-{{ $product->id }}"
                                                                    data-field="stock_status"
                                                                    data-sku="{{ $product->sku }}"
                                                                    data-product-id="{{ $product->id }}"
                                                                    @disabled(optional($product)->manage_stock !== 1)>
                                                                    <option value="">{{ __('Select') }}
                                                                    </option>
                                                                    <option value="in_stock" @selected($product->stock_status == 'in_stock')>
                                                                        {{ __('In Stock') }}
                                                                    </option>
                                                                    <option value="out_of_stock"
                                                                        @selected($product->stock_status == 'out_of_stock')>
                                                                        {{ __('Out of Stock') }}
                                                                    </option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="input-group">
                                                                <input
                                                                    class="form-control update-stock"
                                                                    id="stock_qty-{{ $product->id }}"data-field="stock_qty"
                                                                    data-product-id="{{ $product->id }}"
                                                                    data-sku="{{ $product->sku }}" type="number"
                                                                    value="{{ old('stock_qty', $product->stock_qty) }}"
                                                                    placeholder="{{ __('Stock Quantity') }}"
                                                                    @disabled(optional($product)->manage_stock !== 1 || optional($product)->stock_status == 'out_of_stock')>
                                                                <span
                                                                    class="input-group-text">{{ optional($product->unit)->name }}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endif
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
@endsection

@push('js')
    <script>
        "use strict";

        $(document).ready(function() {
            $('.update-stock').on('change', function() {
                var field = $(this).data('field');
                var product_id = $(this).data('product-id');
                var product_variant_id = $(this).data('product-variant-id');
                var is_variant = $(this).data('is-variant');
                var sku = $(this).data('sku');
                if (field == 'manage_stock') {
                    var value = $(this).is(':checked') ? 1 : 0;
                } else {
                    var value = $(this).val();
                }

                if (field !== 'manage_stock' && (value == null || value == '')) {
                    toastr.error("{{ __('Please select a value') }}");
                    return;
                }

                let dataForm = {
                    field: field,
                    value: value,
                    sku: sku,
                    product_id: product_id,
                    _token: '{{ csrf_token() }}',
                };

                if (is_variant) {
                    dataForm.product_variant_id = product_variant_id;
                }

                $.ajax({
                    url: "{{ route('seller.products.product-inventory.store') }}",
                    type: 'POST',
                    data: dataForm,
                    headers: {
                        "Accept": "application/json",
                    },
                    beforeSend: function() {
                        $('.update-stock').attr('disabled', true);
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }

                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    }
                });
            });
        });

        $('#search-form').on('change', function() {
            this.submit();
        });
    </script>
@endpush
