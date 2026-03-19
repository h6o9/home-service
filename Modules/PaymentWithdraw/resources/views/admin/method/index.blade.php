@extends('admin.master_layout')
@section('title')
    <title>{{ __('Withdraw Method') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Withdraw Method') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Withdraw Method') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1">
                                <form action="{{ route('admin.withdraw-method.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')">
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
                                            <x-admin.form-select class="form-select" id="status" name="status">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option value="active" :selected="request('status') == 'active'"
                                                    text="{{ __('Active') }}" />
                                                <x-admin.select-option value="inactive" :selected="request('status') == 'inactive'"
                                                    text="{{ __('In-Active') }}" />
                                            </x-admin.form-select>
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
                                <x-admin.form-title :text="__('Withdraw Method')" />
                                <div>
                                    <x-admin.add-button :href="route('admin.withdraw-method.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Minimum Amount') }}</th>
                                                <th>{{ __('Maximum Amount') }}</th>
                                                <th>{{ __('Charge') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($methods as $index => $method)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $method->name }}</td>
                                                    <td>
                                                        {{ currency($method->min_amount) }}

                                                    </td>
                                                    <td>
                                                        {{ currency($method->max_amount) }}
                                                    </td>
                                                    <td>{{ $method->withdraw_charge }}%</td>
                                                    <td>
                                                        <input id="status_toggle" data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger" type="checkbox"
                                                            onchange="changeStatus({{ $method->id }})"
                                                            {{ $method->status == 'active' ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <x-admin.edit-button :href="route('admin.withdraw-method.edit', $method->id)" />
                                                        <x-admin.delete-button :id="$method->id" onclick="deleteData" />

                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Method')" route="admin.withdraw-method.create"
                                                    create="yes" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $methods->onEachSide(0)->links() }}
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
            $("#deleteForm").attr("action", '{{ url('admin/withdraw-method/') }}' + "/" + id)
        }

        "use strict"

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/withdraw-method/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error(__('Something went wrong, please try again'));
                    }
                }
            });
        }
    </script>
@endsection
