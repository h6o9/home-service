@extends('admin.master_layout')

@section('title')
    <title>{{ __('Unit List') }}</title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Product List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Product List') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-xxl-3 col-lg-4">
                        <div class="card mb-5">
                            @adminCan('product.unit.create')
                                <div class="card-header d-flex justify-content-between">
                                    <h4 class="section_title" id="section_title">{{ __('Create Unit Type') }}</h4>
                                    <div>
                                    </div>
                                </div>
                                <div class="card-body pb-0">
                                    <form class="search_form" id="form" action="{{ route('admin.unit.store') }}"
                                        method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label>{{ __('Name') }} <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="name" name="name" type="text">
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label>{{ __('Short Name') }} <span class="text-danger">*</span></label>
                                                    <input class="form-control" id="ShortName" name="ShortName" type="text">
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="form-group">
                                                    <label>{{ __('Base Unit') }}</label>
                                                    <select class="form-control" id="base_unit" name="base_unit">
                                                        <option value="">{{ __('Select Base Unit') }}</option>
                                                        @foreach ($parentUnits as $parentUnit)
                                                            <option value="{{ $parentUnit->id }}">{{ $parentUnit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 operator d-none">
                                                <div class="form-group">
                                                    <label>{{ __('Operator') }}</label>
                                                    <select class="form-control" id="operator" name="operator">
                                                        <option value="*">{{ __('Multiply') }} (*)</option>
                                                        <option value="/">{{ __('Divide') }} (/)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 operator_value d-none">
                                                <div class="form-group">
                                                    <label>{{ __('Operator Value') }} <span
                                                            class="text-danger">*</span></label>
                                                    <input class="form-control" id="operator_value" name="operator_value"
                                                        type="text" value="1">
                                                </div>
                                            </div>
                                            <div class="col-xl-12">
                                                <div class="form-group mb-1">
                                                    <label>{{ __('Status') }} </label>
                                                    <div class="d-flex flex-wrap gap-3 border rounded py-2 px-4">
                                                        <div class="d-flex gap-2 align-items-center py-1">
                                                            <input id="active" name='status' type="radio" value="1"
                                                                checked />
                                                            <label class="mb-0" for="active">{{ __('Active') }} </label>
                                                        </div>
                                                        <div class="d-flex gap-2 align-items-center py-1">
                                                            <input id="inactive" name='status' type="radio"
                                                                value="0" />
                                                            <label class="mb-0" for="inactive">{{ __('Inactive') }} </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-12 mt-3">
                                                <div class="form-group mt-1">
                                                    <x-admin.save-button :text="__('Save')" />
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endadminCan
                        </div>
                    </div>

                    <div class="col-xxl-9 col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-invoice" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Short Name') }}</th>
                                                <th>{{ __('Base Unit') }}</th>
                                                <th>{{ __('Operator') }}</th>
                                                <th>{{ __('Operator Value') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($units as $index => $unit)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $unit->name }}</td>
                                                    <td>{{ $unit->ShortName }}</td>
                                                    <td>{{ $unit->parent?->name ?? '' }}</td>
                                                    <td>{{ $unit->operator }}</td>
                                                    <td>{{ $unit->operator_value }}</td>
                                                    <td>
                                                        @if ($unit->status == 1)
                                                            <span class="badge badge-success">{{ __('Active') }}</span>
                                                        @else
                                                            <span class="badge badge-danger">{{ __('Inactive') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (checkAdminHasPermission('product.unit.edit') || checkAdminHasPermission('product.unit.delete'))
                                                            <div class="btn-group" role="group">
                                                                <button class="btn bg-label-primary dropdown-toggle"
                                                                    id="btnGroupDrop{{ $unit->id }}"
                                                                    data-bs-toggle="dropdown" type="button"
                                                                    aria-haspopup="true" aria-expanded="false">
                                                                    Action
                                                                </button>

                                                                <div class="dropdown-menu"
                                                                    aria-labelledby="btnGroupDrop{{ $unit->id }}">
                                                                    @adminCan('product.unit.edit')
                                                                        <a class="dropdown-item edit-btn"
                                                                            href="{{ route('admin.unit.edit', $unit->id) }}">{{ __('Edit') }}</a>
                                                                    @endadminCan
                                                                    @adminCan('product.unit.delete')
                                                                        <a class="dropdown-item" href="javascript:;"
                                                                            onclick="deleteData({{ $unit->id }})">
                                                                            {{ __('Delete') }}
                                                                        </a>
                                                                    @endadminCan
                                                                </div>
                                                            </div>
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
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#name').val(response.name);
                        $('#ShortName').val(response.ShortName);
                        $('#base_unit').val(response.base_unit);
                        $('#operator').val(response.operator);
                        $('#operator_value').val(response.operator_value);
                        $('#section_title').text('{{ __('Update Unit Type') }}');

                        if (response.base_unit) {
                            $('.operator').removeClass('d-none');
                            $('.operator_value').removeClass('d-none');
                        } else {
                            $('.operator').addClass('d-none');
                            $('.operator_value').addClass('d-none');
                        }
                        $('input[name="status"][value="' + response.status + '"]').prop(
                            'checked', true);
                        let url = "{{ route('admin.unit.update', ':id') }}";
                        url = url.replace(':id', response.id);
                        $('#form').attr('action', url);
                        const unitId = "<input type='hidden' name='unit_type_id' value='" +
                            response.id + "'>";
                        const method = "<input type='hidden' name='_method' value='PUT'>";
                        $('#form').append(unitId);
                        $('#form').append(method);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            })

            $('#base_unit').on("change", function() {
                const baseUnit = $(this).val();
                if (baseUnit) {
                    $('.operator').removeClass('d-none');
                    $('.operator_value').removeClass('d-none');
                } else {
                    $('.operator').addClass('d-none');
                    $('.operator_value').addClass('d-none');
                }
            });
        });

        function deleteData(id) {
            let url = '{{ route('admin.unit.destroy', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
