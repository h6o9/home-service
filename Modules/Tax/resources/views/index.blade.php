@extends('admin.master_layout')
@section('title')
    <title>{{ __('Tax List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Tax List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Tax List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Tax List')" />
                                @adminCan('tax.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.tax.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Percentage') }}</th>
                                                @adminCan('tax.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('tax.edit') || checkAdminHasPermission('tax.delete'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($taxes as $tax)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $tax->title }}</td>
                                                    <td>{{ $tax->percentage }}</td>
                                                    @adminCan('tax.update')
                                                        <td>
                                                            <input onchange="changeStatus({{ $tax->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $tax->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('tax.edit') || checkAdminHasPermission('tax.delete'))
                                                        <td class="text-center">
                                                            <div>
                                                                @adminCan('tax.edit')
                                                                    <x-admin.edit-button :href="route('admin.tax.edit', [
                                                                        'tax' => $tax->id,
                                                                        'code' => getSessionLanguage(),
                                                                    ])" />
                                                                @endadminCan
                                                                @adminCan('tax.delete')
                                                                    <x-admin.delete-button :id="$tax->id"
                                                                        onclick="deleteData" />
                                                                @endadminCan
                                                            </div>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('tax')" route="admin.tax.create" create="yes"
                                                    :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $taxes->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('tax.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('tax.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/tax/') }}' + "/" + id)
        }
        @endadminCan

        @adminCan('tax.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/tax/status-update') }}" + "/" + id,
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
            })
        }
        @endadminCan
    </script>
@endpush
