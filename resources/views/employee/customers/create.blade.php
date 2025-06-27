@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Create New Customer</h1>

    <form method="POST" action="{{ route('employee.customers.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Select Person</label>
            <select name="person_id" class="w-full border rounded px-3 py-2" required>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->name }} ({{ $person->email }})</option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Adults</label>
                <input type="number" name="number_of_adults" min="0" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Children</label>
                <input type="number" name="number_of_children" min="0" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Babies</label>
                <input type="number" name="number_of_babies" min="0" class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <label><input type="checkbox" name="is_vegan" value="1"> Vegan</label>
            <label><input type="checkbox" name="is_vegetarian" value="1"> Vegetarian</label>
            <label><input type="checkbox" name="no_pork" value="1"> No Pork</label>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Create Customer</button>
    </form>
@endsection
