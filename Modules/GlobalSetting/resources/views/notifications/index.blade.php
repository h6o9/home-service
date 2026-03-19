@extends('admin.master_layout')

@section('title')
    <title>
        {{ __('Notifications') }}
    </title>
@endsection

@section('admin-content')
    <div class="main-content">
        <section class="section">
            <div class="section-header d-flex justify-content-between">
                <h1>{{ __('Notifications') }}</h1>
                @if ($setting->is_queueable == config('services.default_status.active_text'))
                    <div>
                        <p class="badge badge-info">
                            {{ __('Read notifications from the previous month will be automatically deleted once a month.') }}
                        </p>
                    </div>
                @endif
                <a class="btn btn-success" href="{{ route('admin.settings') }}">{{ __('Back') }}</a>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form id="filter_form" action="{{ route('admin.notifications.index') }}" method="get">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <input class="form-control" id="search" name="search" type="text"
                                                value="{{ request('search') }}" placeholder="{{ __('Search') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="type" name="type">
                                                <option value="" @if (request('type') == 'all') selected @endif>
                                                    {{ __('All') }} ({{ __('Read') . ': ' . $readCount ?? 0 }} +
                                                    {{ __('Unread') . ': ' . $unreadCount ?? 0 }})</option>
                                                <option class="text-success" value="read"
                                                    @if (request('type') == 'read') selected @endif>
                                                    {{ __('Read') }}: {{ $readCount ?? 0 }}</option>
                                                <option class="text-danger" value="unread"
                                                    @if (request('type') == 'unread') selected @endif>
                                                    {{ __('Unread') }}: {{ $unreadCount ?? 0 }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="alert_type" name="alert_type">
                                                <option value="" @if (request('alert_type') == 'all') selected @endif>
                                                    {{ __('All Alert Types') }}</option>
                                                <option class="text-info" value="info"
                                                    @if (request('alert_type') == 'info') selected @endif>
                                                    {{ __('Info') }}: {{ $infoCount ?? 0 }}</option>
                                                <option class="text-success" value="success"
                                                    @if (request('alert_type') == 'success') selected @endif>
                                                    {{ __('Success') }}: {{ $successCount ?? 0 }}</option>
                                                <option class="text-warning" value="warning"
                                                    @if (request('alert_type') == 'warning') selected @endif>
                                                    {{ __('Warning') }}: {{ $warningCount ?? 0 }}</option>
                                                <option class="text-danger" value="danger"
                                                    @if (request('alert_type') == 'danger') selected @endif>
                                                    {{ __('Danger') }}: {{ $dangerCount ?? 0 }}</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="order" name="order">
                                                <option value="asc" @if (request('order') == 'asc') selected @endif>
                                                    {{ __('Ascending') }}</option>
                                                <option value="desc" @if (request('order') == 'desc') selected @endif>
                                                    {{ __('Descending') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('All Notifications') }} ({{ $totalNotificationsCount ?? 0 }})</h4>
                                <div class="card-header-form d-flex justify-content-end">
                                    <button class="btn btn-success m-1 confirm"
                                        data-href="{{ route('admin.notifications.mark-as-read') }}"
                                        type="button">{{ __('Mark all as read') }}:
                                        <span id="mark_read_count">{{ $unreadCount ?? 0 }}</span></button>
                                    <button class="btn btn-danger m-1 confirm"
                                        data-href="{{ route('admin.notifications.delete-all') }}"
                                        type="button">{{ __('Delete All') }}:
                                        <span id="delete_all_count">{{ $totalNotificationsCount ?? 0 }}</span></button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="1%">
                                                    <input class="form-check-input" id="select_all" type="checkbox">
                                                </th>
                                                <th width="3%">{{ __('SN') }}</th>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Created at') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $sn = getCurrentSerial($notifications);
                                            @endphp

                                            @forelse ($notifications as $index => $message)
                                                <tr>
                                                    <td width="3%">
                                                        <input class="form-check-input select_single"
                                                            data-id="{{ $message->id }}" type="checkbox">
                                                    </td>
                                                    <td>{{ $sn }}</td>
                                                    <td class="text-{{ $message->type }}">
                                                        {{ htmlDecode($message->title) }}
                                                        ({{ str($message->type)->title() }})
                                                    </td>
                                                    <td>
                                                        @if ($message->is_read == 1)
                                                            <i class="fas fa-check-circle text-primary"
                                                                title="{{ __('Read at') }} {{ formattedDateTime($message->updated_at) }}"></i>
                                                        @endif
                                                        {{ formattedDateTime($message->created_at) }}
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-success btn-sm"
                                                            href="{{ route('admin.notifications.show', $message->id) }}"><i
                                                                class="fa fa-eye" aria-hidden="true"></i></a>
                                                        <a class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal" href="javascript:;"
                                                            onclick="deleteData({{ $message->id }})"><i
                                                                class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                @php
                                                    $sn++;
                                                @endphp
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="5"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer d-flex justify-content-center">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <x-admin.delete-modal />

    @push('js')
        <script src="{{ asset('global/js/sweetalert.js') }}"></script>

        <script>
            "use strict";

            function deleteData(id) {
                $("#deleteForm").attr("action", "{{ url('/admin/notifications/destroy') }}" + "/" + id)
            }

            $('#filter_form').on('change', function() {
                $(this).submit();
            });

            $('#select_all').on('click', function() {
                if (this.checked) {
                    $('.select_single').each(function() {
                        this.checked = true;
                    });
                } else {
                    $('.select_single').each(function() {
                        this.checked = false;
                    });
                }

                updateMarkReadCount();
                updateDeleteAllCount();
            });

            $('.select_single').on('click', function() {
                if ($('.select_single:checked').length == $('.select_single').length) {
                    $('#select_all').prop('checked', true);
                } else {
                    $('#select_all').prop('checked', false);
                }

                updateMarkReadCount();
                updateDeleteAllCount();
            });

            function updateMarkReadCount() {
                var selectedIds = [];
                $('.select_single:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });
                if (selectedIds.length == 0) {
                    $('#mark_read_count').text("{{ $unreadCount ?? 0 }}");
                } else {
                    $('#mark_read_count').text(selectedIds.length);
                }

            }

            function updateDeleteAllCount() {
                var selectedIds = [];
                $('.select_single:checked').each(function() {
                    selectedIds.push($(this).data('id'));
                });
                if (selectedIds.length == 0) {
                    $('#delete_all_count').text("{{ $unreadCount ?? 0 }}");
                } else {
                    $('#delete_all_count').text(selectedIds.length);
                }
            }

            $(".confirm").on('click', function(e) {
                e.preventDefault();
                var url = $(this).data('href');
                Swal.fire({
                    title: "{{ __('Are you sure?') }}",
                    text: "{{ __('You would not be able to revert this!') }}",
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonColor: "#3085d6",
                    confirmButtonColor: "#d33",
                    confirmButtonText: "{{ __('Yes!') }}",
                }).then((result) => {
                    if (result.isConfirmed) {
                        var selectedIds = [];

                        $('.select_single:checked').each(function() {
                            selectedIds.push($(this).data('id'));
                        });

                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(result) {
                                location.reload();
                            },
                            error: function(err) {
                                toastr.error(err.responseJSON.message);
                                console.log(err);
                            }
                        })
                    }
                });
            });
        </script>
    @endpush
@endsection
