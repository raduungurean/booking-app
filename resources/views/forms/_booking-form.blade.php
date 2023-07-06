<div class="p-4">
    <h1 class="text-xl mb-8">Booking Form</h1>
    <form action="{{ $formAction }}" method="POST" id="booking-form" class="w-full md:w-1/2 lg:w-1/3 mr-auto">
        @csrf

        @if(session('success'))
            <div class="bg-green-500 text-white px-4 py-2 mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-500 text-white px-4 py-2 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-4">
            <label for="consultant" class="block mb-2 font-bold text-gray-700">Consultant</label>
            <select name="consultant" id="consultant" class="w-full px-3 py-2 border rounded-md">
                <option value="">Please select a consultant</option>
                @foreach ($consultants as $consultant)
                    <option value="{{ $consultant->id }}">{{ $consultant->first_name }} {{$consultant->last_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="date" class="block mb-2 font-bold text-gray-700">Date</label>
            <div class="relative">
                <input type="text" name="date" id="date-f-booking" class="w-full px-3 py-2 border rounded-md">
                <button id="clear-date-button" type="button" class="absolute right-0 top-0 mt-2 mr-3 text-gray-500 hover:text-gray-700 focus:outline-none">
                    Clear
                </button>
            </div>
        </div>

        <div id="loader" class="hidden"></div>
        <div class="mb-4" id="bookingContainer">
            <label id="booking-time-slot-label" style="display: none" for="time-slot" class="block mb-2 font-bold text-gray-700">Appointment Options</label>
            <div id="time-slot-container"></div>
        </div>

        <div class="mb-4">
            <label for="name" class="block mb-2 font-bold text-gray-700">Name</label>
            <input type="text" name="name" id="name-booking" class="w-full px-3 py-2 border rounded-md" placeholder="Enter your name">
        </div>

        <div class="mb-4">
            <label for="details" class="block mb-2 font-bold text-gray-700">Reservation Details</label>
            <textarea name="details" id="details" class="w-full px-3 py-2 border rounded-md" placeholder="Enter your reservation details"></textarea>
        </div>

        <button
            disabled="disabled"
            type="submit"
            class="px-4 py-2 text-white bg-blue-500 rounded-md disabled:opacity-60 disabled:cursor-not-allowed"
            id="submit-button-book"
        >
            Book Now
        </button>

    </form>
</div>
