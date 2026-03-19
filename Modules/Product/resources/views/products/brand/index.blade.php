@extends('admin.master_layout')
@section('title')
    <title>{{ __('Brand List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Brand List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Brand List') => '#',
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
                                <x-admin.form-title :text="__('Brand List')" />
                                @adminCan('product.brand.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.brand.create')" />
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
                                                @adminCan('product.brand.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('product.brand.edit') || checkAdminHasPermission('product.brand.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($brands as $index => $brand)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $brand->name }}</td>

                                                    @adminCan('product.brand.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $brand->id }})"
                                                                {{ $brand->status ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('product.brand.edit') || checkAdminHasPermission('product.brand.delete'))
                                                        <td>
                                                            @adminCan('product.brand.edit')
                                                                <x-admin.edit-button :href="route('admin.brand.edit', [
                                                                    'brand' => $brand->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('product.brand.delete')
                                                                <x-admin.delete-button :id="$brand->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('brand')" route="admin.brand.create" create="yes"
                                                    :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $brands->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @adminCan('product.brand.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('product.brand.delete')
        "use strict"

        function deleteData(id) {
            let url = "{{ route('admin.brand.destroy', ':id') }}";
            url = url.replace(':id', id);

            $("#deleteForm").attr("action", url);
        }
        @endadminCan
        @adminCan('product.brand.update')
        "use strict"

        function changeStatus(id) {
            let url = "{{ route('admin.brand.status', ':id') }}";
            url = url.replace(':id', id);
            $.ajax({
                type: "patch",
                data: {
                    _token: '{{ csrf_token() }}',
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
