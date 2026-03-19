@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="row">
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Earning') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalCreditAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Send') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalDebitAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Total Pending') }}</h4>
                            </div>
                            <div class="card-body">
                                {{ currency($totalPendingCreditAmount) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="far fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>{{ __('Auto Status Approve') }}</h4>
                            </div>
                            <div class="card-body">
                                <input data-toggle="toggle" data-onlabel="{{ __('Yes') }}"
                                    data-offlabel="{{ __('No') }}" data-onstyle="success" data-offstyle="danger"
                                    type="checkbox" onchange="autoApproveUpdate('wallet_amount_auto_approve')"
                                    @checked(getSettingStatus('wallet_amount_auto_approve', 'int'))>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body pb-1">
                            <form id="filter_form" action="" method="GET">
                                <div class="row">
                                    <div class="col-lg-3 col-xl-4 col-md-6">
                                        <div class="form-group search-wrapper">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}"
                                                placeholder="{{ __('Search') }}..." autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xl-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" id="order_by" name="order_by">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="asc"
                                                    {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                    {{ __('Ascending') }}
                                                </option>
                                                <option value="desc"
                                                    {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                    {{ __('Descending') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-xl-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control" id="par-page" name="par-page">
                                                <option value="">{{ __('Per Page') }}</option>
                                                <option value="10" {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('10') }}
                                                </option>
                                                <option value="50" {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('50') }}
                                                </option>
                                                <option value="100"
                                                    {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                    {{ __('100') }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    @if (isRoute('admin.wallet-history'))
                                        <div class="col-lg-3 col-xl-2 col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="status" name="status">
                                                    <option value="">{{ __('Status') }}</option>
                                                    <option value="completed" @selected(request('status') == 'completed')>
                                                        {{ __('Completed') }}
                                                    </option>
                                                    <option value="pending" @selected(request('status') == 'pending')>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="rejected" @selected(request('status') == 'rejected')>
                                                        {{ __('Rejected') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-lg-3 col-xl-2 col-md-6">
                                        <div class="form-group">
                                            <select class="form-control select2" id="vendor_id" name="vendor_id">
                                                <option value="" selected disabled>{{ __('Select Seller') }}
                                                </option>
                                                @foreach ($sellers as $vendor)
                                                    <option value="{{ $vendor->id }}" @selected(request('vendor_id') == $vendor->id)>
                                                        {{ $vendor->shop_name }} ({{ $vendor->user->name ?? '' }})
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
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Order') }}</th>
                                            <th>{{ __('For') }}</th>
                                            <th>{{ __('Gateway') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Deposit At') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </thead>

                                        @forelse ($wallet_histories as $index => $wallet_history)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>
                                                    {{-- TODO: update seller profile link --}}
                                                    <a
                                                        href="{{ route('admin.customer-show', $wallet_history->user_id) }}">{{ $wallet_history?->user?->name }}</a>
                                                </td>

                                                <td>
                                                    @if ($wallet_history->transaction_type == 'credit' && $wallet_history?->order)
                                                        <a href="{{ route('admin.order', $wallet_history->order->order_id) }}"
                                                            target="_blank">#{{ $wallet_history->order?->order_id }}</a>
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($wallet_history->transaction_type == 'credit' && $wallet_history->orderDetails)
                                                        <a href="{{ route('admin.product.show', ['product' => $wallet_history->orderDetails?->product_id]) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            {{ $wallet_history->orderDetails?->product_sku }}
                                                        </a>
                                                    @elseif ($wallet_history->transaction_type == 'debit' && $wallet_history->withdrawRequest)
                                                        <a href="{{ route('admin.show-withdraw', $wallet_history->withdrawRequest->id) }}"
                                                            target="_blank" rel="noopener noreferrer">
                                                            {{ __('Show Withdraw') }}</a>
                                                        </a>
                                                    @else
                                                        {{ __('N/A') }}
                                                    @endif
                                                </td>

                                                <td>{{ __($wallet_history->payment_gateway) }}</td>

                                                <td>{{ currency($wallet_history->amount) }}</td>
                                                <td>
                                                    @if ($wallet_history->transaction_type == 'credit')
                                                        <span class="badge bg-success"><i class="fas fa-plus"></i>
                                                            {{ __('Credit') }}</span>
                                                    @else
                                                        <span class="badge bg-danger"><i class="fas fa-minus"></i>
                                                            {{ __('Debit') }}</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($wallet_history->payment_status == 'completed')
                                                        <div class="badge bg-success">{{ __('Completed') }}</div>
                                                    @elseif ($wallet_history->payment_status == 'rejected')
                                                        <div class="badge bg-danger">{{ __('Rejected') }}</div>
                                                    @else
                                                        <div class="badge bg-warning">{{ __('Pending') }}</div>
                                                    @endif
                                                </td>

                                                <td>{{ formattedDateTime($wallet_history->created_at) }}</td>

                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ route('admin.show-wallet-history', $wallet_history->id) }}"><i
                                                            class="fa fa-eye"></i></a>

                                                    <a class="btn btn-danger btn-sm delete"
                                                        data-url="{{ route('admin.delete-wallet-history', $wallet_history->id) }}"
                                                        href=""><i class="fa fa-trash"></i></a>

                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('')" route="" create="no"
                                                :message="__('No data found!')" colspan="10"></x-empty-table>
                                        @endforelse

                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-whitesmoke d-flex justify-content-center align-items-center">
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $wallet_histories->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="delete" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete refund request') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">{{ __('Are You Sure to Delete this refund ?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            $(function() {
                'use strict'

                $('.delete').on('click', function(e) {
                    e.preventDefault();
                    const modal = $('#delete');
                    modal.find('form').attr('action', $(this).data('url'));
                    modal.modal('show');
                });
                $('#filter_form').on('change', function(e) {
                    $(this).submit();
                });
            });

            function autoApproveUpdate(id) {
                handleStatus("{{ route('admin.wallet-auto-approve-status', ':id') }}".replace(':id', id));
            }
        </script>
    @endpush
@endsection
