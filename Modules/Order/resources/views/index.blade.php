@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    @use('Modules\Order\app\Http\Enums\OrderStatus', 'OrderStatusEnum')
    @use('Modules\Order\app\Http\Enums\PaymentStatus', 'PaymentStatusEnum')

    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="order-filter" action="{{ route($route) }}" method="GET">
                                    <div class="row">
                                        <div
                                            class="col-md-{{ $route == 'admin.orders' ? 3 : 4 }} col-lg-3 col-xl-2 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="select2" id="user" name="user">
                                                <x-admin.select-option value="" text="{{ __('Select user') }}" />
                                                @foreach ($users as $user)
                                                    <x-admin.select-option value="{{ $user->id }}" :selected="$user->id == request('user')"
                                                        text="{{ $user->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        @if ($route == 'admin.orders')
                                            <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Order Status') }}" />
                                                    @foreach (OrderStatusEnum::cases() as $status)
                                                        <x-admin.select-option value="{{ $status->value }}"
                                                            :selected="request('status') == $status->value" text="{{ $status->getLabel() }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="payment_status"
                                                name="payment_status">
                                                <x-admin.select-option value=""
                                                    text="{{ __('Select Payment Status') }}" />
                                                @foreach (PaymentStatusEnum::cases() as $status)
                                                    <x-admin.select-option value="{{ $status->value }}" :selected="request('payment_status') == $status->value"
                                                        text="{{ $status->getLabel() }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
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
                                                    {{ $order->is_guest_order ? $order?->shippingAddress?->name : $order?->user?->name ?? '' }}
                                                    @if ($order->is_guest_order)
                                                        <span class="badge bg-warning">{{ __('Guest') }}</span>
                                                    @endif
                                                </td>
                                                <td>#{{ $order->order_id }}</td>
                                                <td>{{ currency($order->total_amount) }}</td>

                                                <td>
                                                    {{ $order->order_status->getLabel() }}
                                                </td>

                                                <td>
                                                    {{ $order->paymentDetails->payment_status->getLabel() }}
                                                    ({{ $order->payment_method }})
                                                </td>

                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('admin.order', $order->order_id) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>

                                                    <a class="btn btn-success btn-sm"
                                                        href="{{ route('admin.orders.invoice', $order->order_id) }}">
                                                        <i class="fa fa-file-invoice"></i>
                                                    </a>

                                                    <a class="btn btn-danger btn-sm delete" href="#" role="button"
                                                        rel="nofollow" onclick="deleteData({{ $order->id }})">
                                                        <i class="fa fa-trash"></i>
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
                                    <div class="d-flex justify-content-center">
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
        'use strict'

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.order-delete', ':id') }}';
            url = url.replace(':id', id);
            var text =
                "{{ __('Are you sure you want to delete this order? Deleting it may cause calculation errors. We recommend changing the status to Cancelled instead.') }}";
            $("#deleteForm").attr('action', url);
            $("#deleteModalText").text(text);
            $('#deleteModal').modal('show');
        }

        $(function() {
            $('#order-filter').on('change', function() {
                $(this).submit();
            });
        });
    </script>
@endpush
