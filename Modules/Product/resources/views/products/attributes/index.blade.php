@extends('admin.master_layout')
@section('title')
    <title>{{ __('Attribute List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Attribute List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Attribute List') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Attribute List')" />
                                @adminCan('product.attribute.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.attribute.create')" text="{{ __('Add Attribute') }}" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SL.') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($attributes as $attribute)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $attribute->name }}</td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <a class="btn btn-primary btn-sm"
                                                                href="{{ route('admin.attributes.values.index') }}?attribute_id={{ $attribute->id }}"
                                                                title="{{ __('Values') }}">
                                                                <i class="fas fa-cog"></i>
                                                            </a>
                                                            @adminCan('product.attribute.edit')
                                                                <a class="btn btn-warning btn-sm" data-toggle="tooltip"
                                                                    href="{{ route('admin.attribute.edit', ['attribute' => $attribute->id]) }}?code={{ getSessionLanguage() }}"
                                                                    title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                                            @endadminCan

                                                            @adminCan('product.attribute.delete')
                                                                <a class="btn btn-danger btn-sm trigger--fire-modal-1 deleteValue"
                                                                    data-url="{{ route('admin.attribute.destroy', $attribute->id) }}"
                                                                    data-form="deleteForm" data-id="{{ $attribute->id }}"
                                                                    href="javascript:void(0)" title="{{ __('Delete') }}"><i
                                                                        class="fas fa-trash"></i></a>
                                                            @endadminCan
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Attribute')" route="admin.attribute.create"
                                                    create="yes" :message="__('No data found!')" colspan="3"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $attributes->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="confirm-availibility" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <input name="attribute_id" type="hidden">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Confirm Delete') }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">

                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">
                            {{ __('Attribute has values. Sure to Delete?') }}
                        </p>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                        <button class="btn btn-danger" type="submit">{{ __('Yes, Delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            'use strict';

            $('.deleteValue').on('click', function() {
                $('.preloader_area').removeClass('d-none')

                const id = $(this).data('id');

                const route = "{{ route('admin.attribute.destroy', ':id') }}".replace(':id', id);
                $.ajax({
                    url: "{{ route('admin.attribute.has-value') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        attribute_id: id
                    },
                    success: function(response) {
                        if (response.status) {
                            $('#confirm-availibility').find('form').attr('action', route);
                            $('[name="attribute_id"]').val(id);
                            $('#confirm-availibility').modal('show');
                        } else {
                            $('#confirm-availibility').find('form').attr('action', route);
                            $('[name="attribute_id"]').val(id);
                            $('#confirm-availibility').modal('show');
                        }

                        $('.preloader_area').addClass('d-none')
                    }
                });
            });
        });
    </script>
@endpush
