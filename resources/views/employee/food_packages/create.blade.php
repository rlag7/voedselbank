@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-xl font-semibold text-center mb-6">Voedselpakket Aanmaken</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6 max-w-xl mx-auto text-sm shadow-sm">
            @foreach ($errors->all() as $error)
                <p class="mb-1">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="flex justify-center">
        <form method="POST" action="{{ route('employee.food_packages.store') }}"
              class="bg-gray-100 p-6 rounded border border-gray-300 w-full max-w-xl space-y-4">
            @csrf

            <div>
                <label class="block text-sm text-gray-700 mb-1">Selecteer Klant</label>
                <select name="customer_id" class="w-full px-3 py-2 border border-gray-300 rounded text-sm" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->person->name }} (ID {{ $customer->id }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-700 mb-1">Samenstel Datum</label>
                <input type="date" name="composition_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>
            @error('composition_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div>
                <label class="block text-sm text-gray-700 mb-1">Distributiedatum</label>
                <input type="date" name="distribution_date"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>
            @error('distribution_date')
            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror

            <div>
                <input type="hidden" name="is_active" value="0">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="form-checkbox text-blue-600"
                        {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700">Actief</span>
                </label>
            </div>

            <div class="text-center space-x-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition text-sm">
                    Maken
                </button>
                <a href="{{ route('employee.food_packages.index') }}"
                   class="inline-block bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition text-sm">
                    Annuleren
                </a>
            </div>

        </form>
    </div>
@endsection
