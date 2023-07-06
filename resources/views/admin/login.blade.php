@extends('layouts.login')

@section('title', 'Login')

@section('content')
    <div class="flex items-center justify-center">
        <div class="max-w-md w-full mx-auto px-6 py-8 bg-white rounded shadow-lg">
            <h2 class="text-2xl font-bold text-center mb-4">Login</h2>
            @if(session('error'))
                <div class="bg-red-500 text-white px-4 py-2 mb-4 rounded">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block mb-1">Email</label>
                    <input type="email" name="email" id="email" required autofocus class="w-full rounded-md border border-gray-300 p-2">
                </div>

                <div class="mb-4">
                    <label for="password" class="block mb-1">Password</label>
                    <input type="password" name="password" id="password" required class="w-full rounded-md border border-gray-300 p-2">
                </div>

                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember" class="mr-2">
                    <label for="remember">Remember Me</label>
                </div>

                <div>
                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Login</button>
                </div>
            </form>
        </div>
    </div>
@endsection
