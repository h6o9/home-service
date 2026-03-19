@extends('admin.master_layout')
@section('title')
    <title>{{ __('Product Return Policy') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product Return Policy') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product Return Policy') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Product Return Policy')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table product_list_table min-height-600" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="">
                                            @foreach ($returnPolicies as $index => $returnPolicy)
                                                <tr>
                                                    <td>{{ $returnPolicies->firstItem() + $index }}</td>
                                                    <td>{{ $returnPolicy->question }}</td>
                                                    <td>{{ $returnPolicy->answer ? str($returnPolicy->answer)->limit(300) : '' }}
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $returnPolicy->status == 1 ? 'success' : 'danger' }}">
                                                            {{ $returnPolicy->status == 1 ? __('Active') : __('Inactive') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-sm btn-danger" href="javascript:;"
                                                            onclick="deleteData({{ $returnPolicy->id }})"><i
                                                                class="fas fa-trash"></i> {{ __('Delete') }}</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right mt-5">
                                        {{ $returnPolicies->onEachSide(0)->links() }}
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

@push('js')
    <script>
        "use strict";

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.products.product-return-policy.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
