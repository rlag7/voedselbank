@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Nieuwe Klant Toevoegen</h1>

    {{-- ✅ Succesmelding --}}
    @if (session('success'))
        <div class="mb-4 rounded border-l-4 border-green-500 bg-green-50 p-4">
            <p class="text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- ❌ Foutmeldingen --}}
    @if ($errors->any())
        <div class="mb-4 rounded border-l-4 border-red-500 bg-red-50 p-4">
            <p class="text-red-700 font-semibold mb-2">Er is iets misgegaan:</p>
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('employee.customers.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Selecteer Persoon</label>
            <select name="person_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Kies een persoon --</option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                        {{ $person->name }} ({{ $person->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Volwassenen</label>
                <input type="number" name="number_of_adults" min="0"
                       value="{{ old('number_of_adults', 0) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Kinderen</label>
                <input type="number" name="number_of_children" min="0"
                       value="{{ old('number_of_children', 0) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Baby’s</label>
                <input type="number" name="number_of_babies" min="0"
                       value="{{ old('number_of_babies', 0) }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Veganistisch</label>
                <select name="is_vegan" class="w-full border rounded px-3 py-2" required>
                    <option value="0" {{ old('is_vegan') == '0' ? 'selected' : '' }}>Nee</option>
                    <option value="1" {{ old('is_vegan') == '1' ? 'selected' : '' }}>Ja</option>
                </select>
            </div>
            <div>
                <label class="block mb-1">Vegetarisch</label>
                <select name="is_vegetarian" class="w-full border rounded px-3 py-2" required>
                    <option value="0" {{ old('is_vegetarian') == '0' ? 'selected' : '' }}>Nee</option>
                    <option value="1" {{ old('is_vegetarian') == '1' ? 'selected' : '' }}>Ja</option>
                </select>
            </div>
            <div>
                <label class="block mb-1">Geen Varkensvlees</label>
                <select name="no_pork" class="w-full border rounded px-3 py-2" required>
                    <option value="0" {{ old('no_pork') == '0' ? 'selected' : '' }}>Nee</option>
                    <option value="1" {{ old('no_pork') == '1' ? 'selected' : '' }}>Ja</option>
                </select>
            </div>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Opslaan
        </button>
    </form>
@endsection
