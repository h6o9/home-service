@extends('admin.master_layout')

@section('title')
    <title>{{ __('Label List') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Label List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Label List') => '#',
            ]" />

            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form action="" method="GET" onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}" placeholder="Search..."
                                                    autocomplete="off">

                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <select class="form-select" id="order_by" name="order_by">
                                                    <option value="">{{ __('Order By') }}</option>
                                                    <option value="asc"
                                                        {{ request('order_by') == 'asc' ? 'selected' : '' }}>
                                                        {{ __('ASC') }}
                                                    </option>
                                                    <option value="desc"
                                                        {{ request('order_by') == 'desc' ? 'selected' : '' }}>
                                                        {{ __('DESC') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group">
                                                <select class="form-select" id="par-page" name="par-page">
                                                    <option value="">{{ __('Per Page') }}</option>
                                                    <option value="10"
                                                        {{ '10' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('10') }}
                                                    </option>
                                                    <option value="50"
                                                        {{ '50' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('50') }}
                                                    </option>
                                                    <option value="100"
                                                        {{ '100' == request('par-page') ? 'selected' : '' }}>
                                                        {{ __('100') }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Label List')" />
                                @adminCan('product.label.create')
                                    <div>
                                        <x-admin.add-button data-bs-toggle="modal" data-bs-target="#add-label"
                                            href="javascript:;" text="{{ __('Add Label') }}" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SL') }}.</th>
                                                <th>{{ __('Name') }}</th>
                                                @adminCan('product.label.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('product.label.edit') || checkAdminHasPermission('product.label.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($productLabels as $index => $productLabel)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $productLabel->name }}</td>
                                                    @adminCan('product.label.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $productLabel->id }})"
                                                                {{ $productLabel->status ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('product.label.edit') || checkAdminHasPermission('product.label.delete'))
                                                        <td>
                                                            @adminCan('product.label.edit')
                                                                <x-admin.edit-button :href="route('admin.label.edit', [
                                                                    'label' => $productLabel->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('product.label.delete')
                                                                <x-admin.delete-button :id="$productLabel->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Label')" route="" create="no"
                                                    :message="__('No data found!')" colspan="4" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $productLabels->onEachSide(0)->links() }}
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

@push('modals')
    @adminCan('product.label.create')
        @include('product::products.label.create-model')
    @endadminCan
@endpush

@push('js')

    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $('[name="name"]').on('input', function() {
                    var name = $(this).val();
                    var slug = convertToSlug(name);
                    $("[name='slug']").val(slug);
                });
            });
        })(jQuery);
    </script>

    <script>
        @adminCan('product.label.delete')
        "use strict"

        function deleteData(id) {
            let url = "{{ route('admin.label.destroy', ':id') }}";
            url = url.replace(':id', id);

            $("#deleteForm").attr("action", url);
        }
        @endadminCan

        @adminCan('product.label.update')
        "use strict"

        function changeStatus(id) {
            let url = "{{ route('admin.label.status', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                headers: {
                    "accept": "application/json",
                },
                url,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    handleFetchError(err);
                }
            });
        }
        @endadminCan
    </script>
@endpush
