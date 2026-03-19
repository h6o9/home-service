@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form action="{{ url()->current() }}" method="GET" onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div
                                            class="{{ Route::is('admin.withdraw-list') ? 'col-md-4' : 'col-lg-3' }} form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        @if (Route::is('admin.withdraw-list'))
                                            <div class="col-md-4 col-lg-3 form-group mb-3">
                                                <x-admin.form-select class="form-select" id="status" name="status">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Status') }}" />
                                                    <x-admin.select-option value="pending" :selected="request('status') == 'pending'"
                                                        text="{{ __('Pending') }}" />
                                                    <x-admin.select-option value="approved" :selected="request('status') == 'approved'"
                                                        text="{{ __('Approved') }}" />
                                                    <x-admin.select-option value="rejected" :selected="request('status') == 'rejected'"
                                                        text="{{ __('Rejected') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif

                                        <div class="col-md-4 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="select2" id="user" name="user">
                                                <x-admin.select-option value="" text="{{ __('Select user') }}" />
                                                @foreach ($users as $user)
                                                    <x-admin.select-option value="{{ $user->id }}" :selected="$user->id == request('user')"
                                                        text="{{ $user->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-3 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-4 col-lg-3 form-group mb-3">
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
                                                <th>{{ __('User') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Charge') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                                <th>{{ __('Withdraw Amount') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($withdraws as $index => $withdraw)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td><a
                                                            href="{{ route('admin.customer-show', $withdraw->user_id) }}">{{ $withdraw?->user?->name }}</a>
                                                    </td>

                                                    <td>{{ $withdraw->method }}</td>
                                                    <td>
                                                        {{ defaultCurrency($withdraw->total_amount - $withdraw->withdraw_amount) }}
                                                    </td>
                                                    <td>
                                                        {{ defaultCurrency($withdraw->total_amount) }}
                                                    </td>
                                                    <td>
                                                        {{ defaultCurrency($withdraw->withdraw_amount) }}
                                                    </td>
                                                    <td>
                                                        @if ($withdraw->status == 'approved')
                                                            <span class="badge bg-success">{{ __('Approved') }}</span>
                                                        @elseif ($withdraw->status == 'rejected')
                                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>

                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.show-withdraw', $withdraw->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>

                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" href="javascript:;"
                                                            onclick="deleteData({{ $withdraw->id }})"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></a>
                                                    </td>

                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="8"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $withdraws->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <x-admin.delete-modal />
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/delete-withdraw/') }}' + "/" + id)
        }
    </script>
@endsection
