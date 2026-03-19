@extends('admin.master_layout')
@section('title')
    <title>{{ __('Custom Page List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Custom Page List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Custom Page List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Custom Page List')" />
                                @adminCan('page.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.custom-pages.create')" />
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
                                                <th width="15%">{{ __('Slug') }}</th>
                                                @adminCan('page.update')
                                                    <th width="15%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pages as $page)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><a href="" target="_blank">{{ $page->title }}</a>
                                                    </td>
                                                    <td>{{ $page->slug }}</td>
                                                    @adminCan('page.update')
                                                        <td>
                                                            <input id="status_toggle" data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger" type="checkbox"
                                                                onchange="changeStatus({{ $page->id }})"
                                                                {{ $page->status ? 'checked' : '' }}
                                                                @disabled(in_array($page->slug, ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller']))>
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('page.edit') || checkAdminHasPermission('page.delete'))
                                                        <td>
                                                            @adminCan('page.edit')
                                                                <x-admin.edit-button :href="route('admin.custom-pages.edit', [
                                                                    'page' => $page->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('page.delete')
                                                                @if (!in_array($page->slug, ['terms-contidions', 'privacy-policy', 'return-policy', 'join-as-seller']))
                                                                    <x-admin.delete-button :id="$page->id"
                                                                        onclick="deleteData" />
                                                                @endif
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Custom Page')" route="admin.custom-pages.index"
                                                    create="no" :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $pages->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('page.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('page.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/custom-pages/') }}' + "/" + id)
        }
        @endadminCan

        @adminCan('page.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/custom-pages/status-update') }}" + "/" + id,
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
