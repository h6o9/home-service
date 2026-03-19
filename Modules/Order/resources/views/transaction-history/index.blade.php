@extends('admin.master_layout')

@section('title')
    <title>{{ __('Transaction History') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Transaction History') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Transaction History') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form id="filter" action="" method="GET">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="method" name="method">
                                                <x-admin.select-option value="" text="{{ __('All Methods') }}" />
                                                @foreach ($transactionMethods as $transactionMethod)
                                                    <x-admin.select-option value="{{ $transactionMethod }}"
                                                        :selected="request('method') == $transactionMethod"
                                                        text="{{ str($transactionMethod)->replace('_', ' ')->title() }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 form-group mb-3">
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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Transaction History')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table min-height-600" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Order ID') }}</th>
                                                <th>{{ __('Transaction ID') }}</th>
                                                <th>{{ __('Payment Method') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Customer') }}</th>
                                                <th>{{ __('Paid At') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @forelse($transactions as $key => $transaction)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        @if ($transaction->order)
                                                            <a class="text-decoration-none"
                                                                href="{{ route('seller.orders.show', $transaction->order->order_id) }}">
                                                                #{{ $transaction->order->order_id }}
                                                            </a>
                                                        @else
                                                            <span class="text-danger">{{ __('N/A') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ $transaction->transaction_id }}
                                                    </td>
                                                    <td>
                                                        {{ str($transaction->payment_method)->replace('_', ' ')->title() }}
                                                    </td>
                                                    <td>
                                                        {{ $transaction->amount }}
                                                        {{ str($transaction->currency)->replace('_', ' ')->upper() }}
                                                    </td>
                                                    <td>
                                                        {{ $transaction->user->name ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ formattedDateTime($transaction->created_at) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td class="text-center" colspan="6">
                                                        <h3>{{ __('No transaction history found') }}</h3>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right mt-5">
                                        {{ $transactions->onEachSide(0)->links() }}
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
            $('#filter').on('change', function() {
                $(this).submit();
            });
        });
    </script>
@endpush
