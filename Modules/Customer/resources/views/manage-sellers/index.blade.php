@extends('admin.master_layout')
@section('title')
    <title>
        {{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('All Sellers') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('All Sellers') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form action="" method="GET" onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div class="col-md-4 col-lg-4 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="verified" name="verified">
                                                <x-admin.select-option value="" text="{{ __('Select Verified') }}" />
                                                <x-admin.select-option value="1" :selected="request('verified') == '1'"
                                                    text="{{ __('Verified (KYC)') }}" />
                                                <x-admin.select-option value="0" :selected="request('verified') == '0'"
                                                    text="{{ __('Non-verified (KYC)') }}" />
                                                <x-admin.select-option value="2" :selected="request('verified') == '2'"
                                                    text="{{ __('User Email Verified') }}" />
                                                <x-admin.select-option value="3" :selected="request('verified') == '3'"
                                                    text="{{ __('Seller Email Non-Verified') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="banned" name="banned">
                                                <x-admin.select-option value="" text="{{ __('Select Banned') }}" />
                                                <x-admin.select-option value="1" :selected="request('banned') == '1'"
                                                    text="{{ __('Banned Account') }}" />
                                                <x-admin.select-option value="0" :selected="request('banned') == '0'"
                                                    text="{{ __('Non-banned Account') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-2 form-group mb-3">
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
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('KYC') }}</th>
                                                <th>{{ __('Statistic') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $index => $user)
                                                <tr>
                                                    <td>
                                                        @php
                                                            if (request('par-page') !== 'all') {
                                                                $orderBy = request('order_by', '1');
                                                                $rowNumber =
                                                                    $orderBy == '1'
                                                                        ? $users->firstItem() + $index
                                                                        : $users->total() -
                                                                            ($users->firstItem() + $index) +
                                                                            1;
                                                            } else {
                                                                $rowNumber = $index + 1;
                                                            }
                                                        @endphp
                                                        {{ $rowNumber }}
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="m-2">
                                                                <img class="rounded-circle"
                                                                    src="{{ asset($user->seller->logo_image ?? $setting->default_avatar) }}"
                                                                    alt="avatar" width="50">
                                                            </div>
                                                            <div>
                                                                <div>
                                                                    <span
                                                                        class="{{ $user->email_verified_at !== null ? 'text-success' : 'text-warning' }}">
                                                                        {{ html_decode($user->name) }}
                                                                        @if ($user->email_verified_at !== null)
                                                                            <i class="fas fa-check-circle"></i>
                                                                        @else
                                                                            <i class="fas fa-times-circle text-danger"></i>
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    {{ __('Shop') }}:
                                                                    <span
                                                                        class="{{ $user->seller->status ? 'text-success' : 'text-warning' }}">
                                                                        @if ($user->seller->status ?? false)
                                                                            <i class="fas fa-check-circle"></i>
                                                                        @else
                                                                            <i class="fas fa-times-circle text-danger"></i>
                                                                        @endif <a
                                                                            href="{{ route('website.shop', ['slug' => $user->seller->shop_slug ?? '404']) }}"
                                                                            target="_blank"
                                                                            rel="noopener noreferrer">{{ html_decode($user->seller->shop_name) }}</a>
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    {{ __('Joined:') }}
                                                                    {{ $user->seller->created_at->diffForHumans() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="{{ $user->seller->verification_token == null ? 'text-success' : 'text-warning' }}">
                                                            @if ($user->seller->verification_token == null)
                                                                <i class="fas fa-check-circle"></i>
                                                            @else
                                                                <i class="fas fa-times-circle text-danger"></i>
                                                            @endif
                                                            {{ html_decode($user->seller->email) }}
                                                        </span>
                                                        <br>
                                                        {{ __('Phone') }}: {{ $user->seller->phone ?? __('N/A') }}
                                                        <br>
                                                        {{ __('Address') }}:
                                                        {{ str($user->seller->address ?? __('N/A'))->limit(20) }}
                                                    </td>
                                                    <td>
                                                        @if ($user->seller->kyc ?? false)
                                                            <a href="{{ route('admin.kyc-list.show', ['id' => $user->seller->kyc->id ?? 404]) }}"
                                                                target="_blank" rel="noopener noreferrer">
                                                                @if (optional($user?->seller?->kyc)->status->value == 1)
                                                                    <span class="badge bg-success mb-1"><i
                                                                            class="fas fa-check-circle"></i>
                                                                        {{ __('Approved') }}</span>
                                                                @else
                                                                    <span class="badge bg-warning"><i
                                                                            class="fas fa-hourglass-half"></i>
                                                                        {{ __('Pending') }}</span>
                                                                @endif
                                                            </a>
                                                            <br>
                                                            {{ __('At') }}:
                                                            {{ formattedDate($user->seller->kyc->verified_at ?? ($user->seller->kyc->created_at ?? now())) }}
                                                        @else
                                                            <span class="badge bg-danger"><i
                                                                    class="fas fa-times-circle"></i>
                                                                {{ __('Unavailable') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{-- Products --}}
                                                        <span class="text-primary">
                                                            <a
                                                                href="{{ route('admin.seller.products.index', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                                                    class="fas fa-boxes me-1 text-primary"></i>
                                                                {{ __('Products') }}:</a>
                                                            <span
                                                                class="text-dark">{{ $user->seller->products_count ?? 0 }}</span>
                                                        </span>
                                                        <br>

                                                        {{-- Orders --}}
                                                        <span class="text-success">
                                                            <a
                                                                href="{{ route('admin.orders', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                                                    class="fas fa-shopping-cart me-1 text-success"></i>
                                                                {{ __('Orders') }}:</a>
                                                            <span
                                                                class="text-dark">{{ $user->seller->orders_count ?? 0 }}</span>
                                                        </span>
                                                        <br>

                                                        {{-- Total Sales --}}
                                                        <span class="text-warning">
                                                            <a
                                                                href="{{ route('admin.wallet-history', ['vendor_id' => $user->seller->id ?? 404]) }}"><i
                                                                    class="fas fa-dollar-sign me-1 text-warning"></i>
                                                                {{ __('Total Sales') }}:</a>
                                                            <span
                                                                class="text-dark">{{ currency($user->seller->wallet_requests_sum ?? 0) }}</span>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if (optional($user->seller)->status == 1)
                                                            <span class="badge bg-success">{{ __('Published') }}</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ __('Hidden') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('admin.manage-seller.profile', $user->id) }}"><i
                                                                class="fas fa-eye"></i></a>
                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('admin.manage-seller.shop.dashboard', $user->id) }}"><i
                                                                class="fas fa-chart-line"></i></a>
                                                        @adminCan('sellers.delete')
                                                            <x-admin.delete-button :id="$user->id" onclick="deleteData" />
                                                        @endadminCan
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Customer')" route="" create="no"
                                                    :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-4">
                                        {{ $users->onEachSide(0)->links() }}
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

        @adminCan('sellers.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/sellers/delete-seller/') }}" + "/" + id)
        }
        @endadminCan
    </script>
@endpush
