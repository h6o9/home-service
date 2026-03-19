@extends('admin.master_layout')
@section('title')
    <title>{{ __('Category List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Category List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Category List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-0">
                                <form action="" method="GET" onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group search-wrapper">
                                                <input class="form-control" name="keyword" type="text"
                                                    value="{{ request()->get('keyword') }}" placeholder="Search..."
                                                    autocomplete="off">

                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
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
                                        <div class="col-lg-3 col-md-6">
                                            <div class="form-group">
                                                <select class="form-select" id="parent_id" name="parent_id">
                                                    <option value="">{{ __('By') }} {{ __('Parent Name') }}
                                                    </option>
                                                    @foreach ($parentCategories as $pcat)
                                                        <option value="{{ $pcat->id }}"
                                                            {{ request('parent_id') == $pcat->id ? 'selected' : '' }}>
                                                            {{ $pcat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
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
                                <x-admin.form-title :text="__('Category List')" />
                                @adminCan('product.category.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.category.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body ">
                                <div class="table-responsive max-h-400 category_list_table">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SL') }}.</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Parent Name') }}</th>
                                                @adminCan('product.category.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('product.category.edit') || checkAdminHasPermission('product.category.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($categories as $index => $category)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($category->image)
                                                            <img class="me-2" src="{{ asset($category->image) }}"
                                                                alt="" width="50px" height="50px">
                                                        @endif
                                                        {{ $category->name }}
                                                    </td>
                                                    <td>
                                                        {{ __('N/A') }}
                                                    </td>
                                                    @adminCan('product.category.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $category->id }})"
                                                                {{ $category->status ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('product.category.edit') || checkAdminHasPermission('product.category.delete'))
                                                        <td>
                                                            @adminCan('product.category.edit')
                                                                <x-admin.edit-button :href="route('admin.category.edit', [
                                                                    'category' => $category->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('product.category.delete')
                                                                <x-admin.delete-button :id="$category->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                                @foreach ($category->children as $keyChild => $child)
                                                    <tr>
                                                        <td>
                                                            {{ $index + 1 }}. {{ $keyChild + 1 }}
                                                        </td>
                                                        <td>
                                                            <img class="me-2" src="{{ asset($child->icon) }}"
                                                                alt="" width="20" height="20">
                                                            {{ $child->name }}
                                                        </td>
                                                        <td>
                                                            @if ($child->parent_id)
                                                                {{ $child->parent->name }}
                                                            @else
                                                                {{ __('N/A') }}
                                                            @endif
                                                        </td>
                                                        @adminCan('product.category.update')
                                                            <td>
                                                                <input id="status_toggle" data-toggle="toggle"
                                                                    data-onlabel="{{ __('Active') }}"
                                                                    data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                    data-offstyle="danger" type="checkbox"
                                                                    onchange="changeStatus({{ $child->id }})"
                                                                    {{ $child->status ? 'checked' : '' }}>
                                                            </td>
                                                        @endadminCan
                                                        @if (checkAdminHasPermission('product.category.edit') || checkAdminHasPermission('product.category.delete'))
                                                            <td>
                                                                @adminCan('product.category.edit')
                                                                    <x-admin.edit-button :href="route('admin.category.edit', [
                                                                        'category' => $child->id,
                                                                        'code' => getSessionLanguage(),
                                                                    ])" />
                                                                @endadminCan
                                                                @adminCan('product.category.delete')
                                                                    <x-admin.delete-button :id="$child->id"
                                                                        onclick="deleteData" />
                                                                @endadminCan
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            @empty
                                                <x-empty-table :name="__('Category')" route="admin.category.create"
                                                    create="yes" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="d-flex justify-content-center mt-3">
                                        {{ $categories->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @adminCan('product.category.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('product.category.delete')
        "use strict"

        function deleteData(id) {
            let url = "{{ route('admin.category.destroy', ':id') }}";
            url = url.replace(':id', id);

            $("#deleteForm").attr("action", url);
        }
        @endadminCan
        @adminCan('product.category.update')
        "use strict"

        function changeStatus(id) {
            let url = "{{ route('admin.category.status', ':id') }}";
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
