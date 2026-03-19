@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Kyc') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Kyc') }}</h1>
            </div>

            <div class="section-body">
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Document') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Document Name') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kycs as $index => $kyc)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>
                                                        <a href="{{ asset($kyc->file) }}">
                                                            <img class="img-thumbnail" src="{{ asset($kyc->file) }}"
                                                                alt="" width="120" @style(['margin: 3px !important'])>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        {{-- TODO: add provider profile link --}}
                                                        <a
                                                            href="">
                                                            {{ $kyc->shop->shop_name ?? 'Unknown' }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $kyc->type->name }}</td>
                                                    <td>
                                                        @if ($kyc->status->value == 0)
                                                            <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                        @endif
                                                        @if ($kyc->status->value == 1)
                                                            <span class="badge bg-success">{{ __('Approved') }}</span>
                                                        @endif
                                                        @if ($kyc->status->value == 2)
                                                            <span class="badge bg-danger">{{ __('Reject') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>

                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.kyc.show', $kyc->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>

                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" href="javascript:;"
                                                            onclick="deleteData({{ $kyc->id }})"><i
                                                                class="fa fa-trash" aria-hidden="true"></i></a>

                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 d-flex justify-content-center">
                        {{ $kycs->links() }}
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
            $("#deleteForm").attr("action", "{{ url('admin/delete-kyc-info/') }}" + "/" + id)
        }
    </script>
@endpush
