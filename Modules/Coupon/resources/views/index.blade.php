@extends('admin.master_layout')
@section('title')
    <title>{{ __('Coupon List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Coupon List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Coupon List') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Coupon List')" />
                                <div>
                                    <a class="btn btn-primary" href="{{ route('admin.coupon.create') }}"><i
                                            class="fas fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Coupon Code') }}</th>
                                                <th>{{ __('Min Spend') }}</th>
                                                <th>{{ __('Discount') }}</th>
                                                <th>{{ __('Start time') }}</th>
                                                <th>{{ __('End time') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($coupons as $index => $coupon)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $coupon->name }}</td>
                                                    <td>{{ $coupon->coupon_code }}</td>
                                                    <td>{{ currency($coupon->minimum_spend) }}</td>
                                                    <td>
                                                        {{ $coupon?->is_percent == 1 ? $coupon?->discount . '%' : currency($coupon?->discount) }}
                                                    </td>

                                                    <td>{{ formattedDate($coupon->start_date) }}</td>
                                                    <td>{{ $coupon->is_never_expired ? __('Never expired') : formattedDate($coupon->expired_date) }}
                                                    </td>

                                                    <td>
                                                        <input id="status_toggle" data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger" type="checkbox"
                                                            onchange="changeStatus({{ $coupon->id }})"
                                                            {{ $coupon->status == 1 ? 'checked' : '' }}>
                                                    </td>

                                                    <td>

                                                        <a class="btn btn-warning btn-sm"
                                                            href="{{ route('admin.coupon.edit', ['coupon' => $coupon->id, 'code' => getSessionLanguage()]) }}"><i
                                                                class="fa fa-edit" aria-hidden="true"></i></a>

                                                        <x-admin.delete-button :id="$coupon->id" onclick="deleteData" />

                                                    </td>

                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Coupon')" route="" create="no"
                                                    :message="__('No data found!')" colspan="9"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex-justify-content-center">
                                {{ $coupons->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/coupon/') }}' + "/" + id)
        }
        "use strict"

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/coupon/status-update') }}" + "/" + id,
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
@endpush
