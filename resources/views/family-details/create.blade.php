<x-app-layout>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-lg p-6 bg-white rounded-lg shadow-lg">
            <!-- Success Message -->
            @if (session('success'))
                <div class="bg-green-100 text-green-700 border-l-4 border-green-500 p-4 mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('family-details.store') }}" method="POST">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                    Add Family Details
                </h2>
                @csrf

                <!-- Family Name -->
                <div class="mb-4">
                    <label for="family_name" class="block text-sm font-medium text-gray-700">Family Name</label>
                    <input type="text" id="family_name" name="family_name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('family_name') }}" required>
                    @error('family_name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Age -->
                <div class="mb-4">
                    <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                    <input type="number" id="age" name="age"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('age') }}">
                    @error('age')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Relationship -->
                <div class="mb-4">
                    <label for="relationship" class="block text-sm font-medium text-gray-700">Relationship</label>
                    <input type="text" id="relationship" name="relationship"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('relationship') }}">
                    @error('relationship')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Spouse Name -->
                <div class="mb-4">
                    <label for="spouse_name" class="block text-sm font-medium text-gray-700">Spouse Name</label>
                    <input type="text" id="spouse_name" name="spouse_name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('spouse_name') }}">
                    @error('spouse_name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Children -->
                <div class="mb-4">
                    <label for="children" class="block text-sm font-medium text-gray-700">Children</label>
                    <input type="number" id="children" name="children"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        value="{{ old('children') }}">
                    @error('children')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <x-primary-button>{{ __('Save') }}</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
