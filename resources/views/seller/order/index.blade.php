@extends('seller.layouts.master')

@section('title')
    <title>{{ $title }}</title>
@endsection
@section('seller-content')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order List') }}" :list="[
                __('Dashboard') => route('seller.dashboard'),
                __('Order List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form class="card-body" id="order-filter" action="{{ route($route) }}" method="GET">
                                    <div class="row">
                                        <div class="col-md-{{ $route == 'admin.orders' ? 4 : 6 }} form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        @if ($route == 'seller.orders.index')
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Status') }}" />
                                                    @foreach (OrderStatusEnum::cases() as $status)
                                                        <x-admin.select-option value="{{ $status->value }}"
                                                            :selected="request('status') == $status->value" text="{{ $status->getLabel() }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select class="form-select" id="par-page" name="par-page">
                                                <x-admin.select-option value="" text="{{ __('Per Page') }}" />
                                                <x-admin.select-option value="5" :selected="request('par-page') == '5'"
                                                    text="{{ __('5') }}" />
                                                <x-admin.select-option value="10" :selected="request('par-page') == '10'"
                                                    text="{{ __('10') }}" />
                                                <x-admin.select-option value="25" :selected="request('par-page') == '25'"
                                                    text="{{ __('25') }}" />
                                                <x-admin.select-option value="50" :selected="request('par-page') == '50'"
                                                    text="{{ __('50') }}" />
                                                <x-admin.select-option value="100" :selected="request('par-page') == '100'"
                                                    text="{{ __('100') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Order ID') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Payment') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>

                                        @forelse ($orders as $index => $order)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    {{ $order->is_guest_order ? $order?->shippingAddress?->name : $order?->user?->name }}
                                                    @if ($order->is_guest_order)
                                                        <span class="badge bg-warning">{{ __('Guest') }}</span>
                                                    @endif
                                                </td>
                                                <td>#{{ $order->order_id }}</td>
                                                <td>{{ $order->payable_amount }} {{ $order->payable_currency }}</td>

                                                <td>
                                                    {{ $order->order_status->getLabel() }}
                                                </td>

                                                <td>
                                                    {{ $order->paymentDetails->payment_status->getLabel() }}
                                                    ({{ getPaymentMethodLabel($order->payment_method) }})
                                                </td>

                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('seller.orders.show', $order->order_id) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('seller.orders.invoice', $order->order_id) }}">
                                                        <i class="fa fa-file-invoice"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Orders')" route="" create="no"
                                                :message="__('No data found!')" colspan="7">
                                            </x-empty-table>
                                        @endforelse
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $orders->onEachSide(0)->links() }}
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
            $('#order-filter').on('change', function() {
                $(this).submit();
            });
        });
    </script>
@endpush
