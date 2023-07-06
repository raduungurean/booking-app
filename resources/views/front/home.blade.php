@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @include('forms._booking-form', ['formAction' => route('bookings.store')])
    @vite('resources/js/booking-form.js')
@endsection
