@extends('layouts.admin')

@section('title', 'Add Booking')

@section('content')
    @include('forms._booking-form', ['formAction' => route('admin.store-booking')])
    @vite('resources/js/booking-form.js')
@endsection
