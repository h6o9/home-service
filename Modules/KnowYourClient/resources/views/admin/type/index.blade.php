@extends('admin.master_layout')

@section('title')
    <title>{{ __('Manage Kyc Type') }}</title>
@endsection

@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>{{ __('Manage Kyc Type') }}</h1>
            </div>

            <div class="section-body">
                <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create_coupon_id" href="javascript:;"><i
                        class="fas fa-plus"></i> {{ __('Add New') }}</a>
                <div class="row mt-sm-4">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kycType as $index => $type)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $type->name }}</td>
                                                    <td>{{ $type->description }}</td>
                                                    <td>
                                                        @if ($type->status == 1)
                                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>

                                                        <a class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#edit_coupon_id_{{ $type->id }}"
                                                            href="javascript:;"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>
                                                        @if ($type->kycApplications->count() == 0)
                                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#deleteModal" href="javascript:;"
                                                                onclick="deleteData({{ $type->id }})"><i
                                                                    class="fa fa-trash" aria-hidden="true"></i></a>
                                                        @else
                                                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                                data-bs-target="#canNotDeleteModal" href="javascript:;"
                                                                disabled><i class="fa fa-trash" aria-hidden="true"></i></a>
                                                        @endif
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="canNotDeleteModal" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    {{ __('You can not delete this Plan. Because there are one or more Plan has been Purcheced.') }}
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @foreach ($kycType as $index => $ktype)
        <div class="modal fade" id="edit_coupon_id_{{ $ktype->id }}" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Update Kyc Type') }}</h5>
                        <button class="btn btn-danger" data-dismiss="modal" type="button" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body pb-0">
                        <form id="update-{{ $ktype->id }}" action="{{ route('admin.kyc.update', $ktype->id) }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" type="text" value="{{ $ktype->name }}"
                                    autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="">{{ __('Description') }}</label>
                                <input class="form-control" name="description" type="text"
                                    value="{{ $ktype->description }}" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="status">
                                    <option value="1" {{ $ktype->status == 1 ? 'selected' : '' }}>
                                        {{ __('Active') }}</option>
                                    <option value="0" {{ $ktype->status == 0 ? 'selected' : '' }}>
                                        {{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer pt-0">
                        <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                        <button class="btn btn-primary" form="update-{{ $ktype->id }}"
                            type="submit">{{ __('Update') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <!-- Modal -->
    <div class="modal fade" id="create_coupon_id" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create kyc Type') }}</h5>
                    <button class="btn btn-danger" data-dismiss="modal" type="button" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.kyc.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">{{ __('Name') }} <span class="text-danger">*</span></label>
                                <input class="form-control" name="name" type="text" required autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label for="">{{ __('Description') }}</label>
                                <input class="form-control" name="description" type="text" value=""
                                    autocomplete="off">
                            </div>

                            <div class="form-group">
                                <label>{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="" name="status">
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-dismiss="modal" type="button">{{ __('Close') }}</button>
                    <button class="btn btn-primary" type="submit">{{ __('Save') }}</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <x-admin.delete-modal />
@endsection

@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/kyc/') }}' + "/" + id)
        }
    </script>
@endpush
