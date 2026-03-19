@extends('admin.master_layout')
@section('title')
    <title>{{ __('Blog List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Blog List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body pb-1 ">
                                <form action="{{ route('admin.blogs.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')">
                                    <div class="row">
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" value="{{ request()->get('keyword') }}"
                                                    placeholder="{{ __('Search') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="show_homepage"
                                                name="show_homepage">
                                                <x-admin.select-option value="" text="{{ __('Show Homepage') }}" />
                                                <x-admin.select-option value="1" :selected="request('show_homepage') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('show_homepage') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="is_popular" name="is_popular">
                                                <x-admin.select-option value="" text="{{ __('Select Popular') }}" />
                                                <x-admin.select-option value="1" :selected="request('is_popular') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('is_popular') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="status" name="status">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option value="1" :selected="request('status') == '1'"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option value="0" :selected="request('status') == '0'"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
                                            <x-admin.form-select class="form-select" id="order_by" name="order_by">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option value="1" :selected="request('order_by') == '1'"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option value="0" :selected="request('order_by') == '0'"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-6 col-lg-3 col-xl-2 form-group mb-3">
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
                                <x-admin.form-title :text="__('Blog List')" />
                                @adminCan('blog.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.blogs.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="15%">{{ __('Category') }}</th>
                                                <th width="10%">{{ __('Show Homepage') }}</th>
                                                <th width="10%">{{ __('Popular') }}</th>
                                                @adminCan('blog.update')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('blog.edit') || checkAdminHasPermission('blog.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($posts as $blog)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $blog->title }}</td>
                                                    <td>{{ $blog?->category?->title }}</td>

                                                    <td>
                                                        @if ($blog->show_homepage == 1)
                                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('No') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($blog->is_popular == 1)
                                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('No') }}</span>
                                                        @endif
                                                    </td>
                                                    @adminCan('blog.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $blog->id }})"
                                                                {{ $blog->status ? 'checked' : '' }}>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('blog.edit') || checkAdminHasPermission('blog.delete'))
                                                        <td>
                                                            @adminCan('blog.edit')
                                                                <x-admin.edit-button :href="route('admin.blogs.edit', [
                                                                    'blog' => $blog->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('blog.delete')
                                                                <x-admin.delete-button :id="$blog->id"
                                                                    onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Post')" route="admin.blogs.create"
                                                    create="yes" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $posts->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('blog.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('blog.delete')
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/blogs/') }}' + "/" + id)
        }
        @endadminCan
        @adminCan('blog.update')
        "use strict"

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/blogs/status-update') }}" + "/" + id,
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
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            });
        }
        @endadminCan
    </script>
@endpush
