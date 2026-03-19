@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'You don’t have permission to access this page. If you believe this
    is an error, let us know.'))
