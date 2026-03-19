@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message', __('Received an invalid response from the upstream server. Hang tight.'))
