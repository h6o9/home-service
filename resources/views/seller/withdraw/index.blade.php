@extends('seller.layouts.master')

@section('title')
    <title>{{ __('My withdraw') }}</title>
@endsection

@section('seller-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('My withdraw') }}" :list="[
                'Dashboard' => route('seller.dashboard'),
                'My withdraw' => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Wallet Balance') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($currentWalletAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-plus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Credit') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($totalWalletCreditAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-minus"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Debit') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ defaultCurrency($totalWalletDebitAmount) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Rejected Withdraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ $totalRejectedRequest }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form class="form_padding" action="{{ url()->current() }}" method="GET"
                                    onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div class="col-xl-6 col-md-4 form-group">
                                            <input class="form-control" name="keyword" type="text"
                                                value="{{ request()->get('keyword') }}" placeholder="{{ __('Search') }}">
                                        </div>
                                        @if (Route::is('admin.withdraw-list'))
                                            <div class="col-xl-2 col-md-4 form-group">
                                                <select class="form-select" id="status" name="status">
                                                    <option value="">{{ __('Select Status') }}</option>
                                                    <option value="pending"
                                                        {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                        {{ __('Pending') }}
                                                    </option>
                                                    <option value="approved"
                                                        {{ request('status') == 'approved' ? 'selected' : '' }}>
                                                        {{ __('Approved') }}
                                                    </option>
                                                    <option value="rejected"
                                                        {{ request('status') == 'rejected' ? 'selected' : '' }}>
                                                        {{ __('Rejected') }}
                                                    </option>
                                                </select>
                                            </div>
                                        @endif
                                        <div class="col-xl-2 col-md-4 form-group">
                                            <select class="form-select" id="order_by" name="order_by">
                                                <option value="">{{ __('Order By') }}</option>
                                                <option value="1" {{ request('order_by') == '1' ? 'selected' : '' }}>
                                                    {{ __('ASC') }}
                                                </option>
                                                <option value="0" {{ request('order_by') == '0' ? 'selected' : '' }}>
                                                    {{ __('DESC') }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-xl-3 col-md-4 form-group">
                                            <select class="form-select" id="par-page" name="par-page">
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
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4>{{ __('My withdraw') }}</h4>

                                <a class="btn btn-primary" href="{{ route('seller.my-withdraw.create') }}"><i
                                        class="fas fa-plus"></i>
                                    {{ __('New withdraw') }}</a>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Charge') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                                <th>{{ __('Withdraw Amount') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($withdraws as $index => $withdraw)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $withdraw->method }}</td>
                                                    <td>{{ defaultCurrency($withdraw->total_amount - $withdraw->withdraw_amount) }}
                                                    </td>
                                                    <td>{{ defaultCurrency($withdraw->total_amount) }}</td>
                                                    <td>{{ defaultCurrency($withdraw->withdraw_amount) }}</td>
                                                    <td>
                                                        @if ($withdraw->status == 'approved')
                                                            <span class="badge bg-success">{{ __('Approved') }}</span>
                                                        @elseif ($withdraw->status == 'rejected')
                                                            <span
                                                                class="badge bg-danger">{{ __('Rejected') }}</span>
                                                        @else
                                                            <span
                                                                class="badge bg-warning">{{ __('Pending') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('seller.my-withdraw.show', $withdraw->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                @if (request()->get('par-page') !== 'all')
                                    {{ $withdraws->links() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
@endsection
