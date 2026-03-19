@extends('admin.master_layout')
@section('title')
    <title>{{ __('Currency List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Currency List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Currency List') => '#',
            ]" />
            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Currency List')" />
                                @adminCan('currency.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.currency.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Currency') }}</th>
                                                <th>{{ __('Country Code') }}</th>
                                                <th>{{ __('Currency Code') }}</th>
                                                <th>{{ __('Currency Icon') }}</th>
                                                <th>{{ __('Currency Rate') }}</th>
                                                <th>{{ __('Default') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                @if (checkAdminHasPermission('currency.edit') || checkAdminHasPermission('currency.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($currencies as $index => $currency)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $currency->currency_name }}</td>
                                                    <td>{{ $currency->country_code }}</td>
                                                    <td>{{ $currency->currency_code }}</td>
                                                    <td>{{ $currency->currency_icon }}</td>
                                                    <td>{{ $currency->currency_rate }}</td>

                                                    <td>
                                                        @if ($currency->is_default == 'yes')
                                                            <span class="badge bg-success">{{ __('Default') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('No') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($currency->status == 'active')
                                                            <span class="badge bg-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    @if (checkAdminHasPermission('currency.edit') || checkAdminHasPermission('currency.delete'))
                                                        <td>
                                                            @adminCan('currency.edit')
                                                                <x-admin.edit-button :href="route('admin.currency.edit', $currency->id)" />
                                                            @endadminCan
                                                            @adminCan('currency.delete')
                                                                @if ($currency->id != 1)
                                                                    <x-admin.delete-button :id="$currency->id"
                                                                        onclick="deleteData" />
                                                                @endif
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Currency')" route="admin.currency.create"
                                                    create="yes" :message="__('No data found!')" colspan="9" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
    @adminCan('currency.delete')
        <x-admin.delete-modal />
    @endadminCan

@endsection
@adminCan('currency.delete')
    @push('js')
        <script>
            "use strict";

            function deleteData(id) {
                $("#deleteForm").attr("action", '{{ url('admin/currency/') }}' + "/" + id)
            }
        </script>
    @endpush
@endadminCan