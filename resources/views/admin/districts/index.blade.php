@extends('admin.master_layout')

@section('title')
    <title>{{ __('District List') }}</title>
@endsection

@section('admin-content')
@can('district.view')

<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>{{ __('District List') }}</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4>{{ __('District List') }}</h4>

                            @can('district.create')
                            <a class="btn btn-primary" href="{{ route('admin.districts.create') }}">
                                <i class="fa fa-plus"></i> {{ __('Add New') }}
                            </a>
                            @endcan
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped" id="districtTable">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('District Name') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($districts as $index => $district)
                                        <tr>
                                            <td>{{ $districts->firstItem() + $index }}</td>
                                            <td>{{ $district->name }}</td>

                                            <!-- STATUS -->
                                            <td>
                                                <input 
                                                    type="checkbox" 
                                                    id="status_toggle_{{ $district->id }}"
                                                    onchange="changeDistrictStatus({{ $district->id }})"
                                                    {{ $district->status == 'active' ? 'checked' : '' }}
                                                    
                                                    data-toggle="toggle" 
                                                    data-on="{{ __('Active') }}"
                                                    data-off="{{ __('Inactive') }}"
                                                    data-onstyle="success"
                                                    data-offstyle="danger"
                                                    data-size="small"
                                                >
                                            </td>

                                            <!-- ACTION -->
                                            <td>
                                                @can('district.delete')
                                                <a class="btn btn-danger btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    href="javascript:;"
                                                    onclick="deleteData({{ $district->id }})">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endcan
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>

                        <div class="card-footer text-center">
                            {{ $districts->links() }}
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<!-- DELETE MODAL -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5>{{ __('Delete District') }}</h5>
                <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body">
                {{ __('Are you sure you want to delete this district?') }}
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('Cancel') }}
                </button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        {{ __('Delete') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@else
<div class="main-content">
    <div class="alert alert-danger m-4">
        {{ __('Access Denied') }}
    </div>
</div>
@endcan
@endsection


@push('js')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>

<script>
"use strict";

// DELETE
function deleteData(id) {
    let url = '{{ route("admin.districts.destroy", ":id") }}';
    url = url.replace(':id', id);
    $('#deleteForm').attr('action', url);
}

// DELETE SUBMIT
$('#deleteForm').submit(function(e){
    e.preventDefault();

    let form = $(this);

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res){
            if(res.success){
                $('#deleteModal').modal('hide');
                toastr.success(res.message);
                setTimeout(()=> location.reload(), 1000);
            } else {
                toastr.error(res.message);
            }
        },
        error: function(){
            toastr.error('Delete failed');
        }
    });
});


// STATUS CHANGE
function changeDistrictStatus(id) {

    let toggle = $('#status_toggle_' + id);
    let status = toggle.prop('checked') ? 'active' : 'inactive';

    $.ajax({
        url: '{{ route("admin.districts.change-status", ":id") }}'.replace(':id', id),
        type: 'POST',
        data: {
            status: status,
            _token: '{{ csrf_token() }}'
        },
        success: function(res){
            if(res.success){
                toastr.success('Status updated');
            } else {
                toastr.error('Error');
                toggle.bootstrapToggle(status == 'active' ? 'off' : 'on');
            }
        },
        error: function(){
            toastr.error('Server error');
            toggle.bootstrapToggle(status == 'active' ? 'off' : 'on');
        }
    });
}

</script>

@endpush