@extends('admin.master_layout')
@section('title')
    <title>{{ __('Product Reviews') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Product Review') }}" :list="[
                'Dashboard' => route('admin.dashboard'),
                'Product Review' => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="15%">{{ __('Name') }}</th>
                                                <th width="50%">{{ __('Product') }}</th>
                                                <th width="5%">{{ __('Rating') }}</th>
                                                <th width="10%">{{ __('Status') }}</th>
                                                <th width="10%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviews as $index => $review)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $review->user->name ?? '' }}</td>
                                                    <td><a
                                                            href="{{ route('seller.product.show', $review->product->id) }}">{{ $review->product->name ?? '' }}</a>
                                                    </td>

                                                    <td>{{ $review->rating }}</td>
                                                    <td>
                                                        <input id="status_toggle" data-toggle="toggle"
                                                            data-onlabel="{{ __('Published') }}"
                                                            data-offlabel="{{ __('Hidden') }}" data-onstyle="success"
                                                            data-offstyle="danger" type="checkbox" @adminCan('product.reviews.update')
                                                            onchange="status({{ $review->id }})" @else disabled
                                                            @endadminCan
                                                            {{ $review->status ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-primary btn-sm"
                                                            href="{{ route('admin.show-product-review', $review->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>

                                                        @adminCan('product.reviews.delete')
                                                            <a class="btn btn-danger" href="javascript:;"
                                                                onclick="deleteData({{ $review->id }})">{{ __('Delete') }}</a>
                                                        @endadminCan
                                                    </td>

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-center">
                                <div class="text-center">
                                    {{ $reviews->links() }}
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

        function status(id) {
            handleStatus("{{ route('admin.product-review.status', ':id') }}".replace(':id', id));

            let status = $('[data-status=' + id + ']').text()
            // remove whitespaces using regex
            status = status.replaceAll(/\s/g, '');
            $('[data-status=' + id + ']').text(status != 'Hidden' ? 'Hidden' : 'Published');
        }

        function deleteData(id) {
            var id = id;
            var url = '{{ route('admin.product-review.delete', ':id') }}';
            url = url.replace(':id', id);
            $("#deleteForm").attr('action', url);
            $('#deleteModal').modal('show');
        }
    </script>
@endpush
