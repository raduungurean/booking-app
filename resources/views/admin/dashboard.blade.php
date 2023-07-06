@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
    <p>Welcome to the admin dashboard!</p>
    @auth
        <form action="{{ route('admin.logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endauth

    <div class="flex flex-col mt-8">

        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center">
                <label for="consultant-filter" class="block mr-2 font-bold text-gray-700">Filter by Consultant Name</label>
                <div class="flex">
                    <select name="consultant-filter" id="consultant-filter" class="w-full px-3 py-2 border rounded-md" onchange="this.form.submit()">
                        <option value="">All Consultants</option>
                        @foreach ($consultants as $consultant)
                            <option value="{{ $consultant->id }}" @if ($consultant->id == $filter) selected @endif>{{ $consultant->first_name }} {{ $consultant->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8 mb-4">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <div class="flex items-center justify-between bg-gray-50 px-4 py-2">
                        <h1 class="text-lg font-semibold">Appointments</h1>
                        <a href="{{ route('admin.add-booking') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Add New</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Consultant Name</th>
                                <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Appointment Time</th>
                                <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Booked by</th>
                                <th class="px-6 py-3 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @if ($appointments->isEmpty())
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No appointments found.</td>
                                </tr>
                            @else
                                @foreach ($appointments as $appointment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{$appointment->id}} - {{ $appointment->consultant->first_name }} {{ $appointment->consultant->last_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y h:i A') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $appointment->name }}</td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 text-center">
                                            <form action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Are you sure you want to delete this appointment?')" class="text-red-500 hover:text-red-700">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M6 8V6a3 3 0 0 1 6 0v2h4a1 1 0 0 1 0 2h-1v6a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-6H3a1 1 0 0 1 0-2h4zm5-4a1 1 0 0 1 1 1v1H8V5a1 1 0 0 1 1-1h2zm3 5H6v7a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-7z"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{ $appointments->links() }}
    </div>

@endsection
