@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Edit Customer</h1>

    <form method="POST" action="{{ route('employee.customers.update', $customer) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Select Person</label>
            <select name="person_id" class="w-full border rounded px-3 py-2" required>
                @foreach($people as $person)
                    <option value="{{ $person->id }}" {{ $customer->person_id == $person->id ? 'selected' : '' }}>
                        {{ $person->name }} ({{ $person->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block mb-1">Adults</label>
                <input type="number" name="number_of_adults" value="{{ $customer->number_of_adults }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Children</label>
                <input type="number" name="number_of_children" value="{{ $customer->number_of_children }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block mb-1">Babies</label>
                <input type="number" name="number_of_babies" value="{{ $customer->number_of_babies }}"
                       class="w-full border rounded px-3 py-2" required>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <input type="hidden" name="is_vegan" value="0">
                <label><input type="checkbox" name="is_vegan" value="1" {{ $customer->is_vegan ? 'checked' : '' }}> Vegan</label>
            </div>
            <div>
                <input type="hidden" name="is_vegetarian" value="0">
                <label><input type="checkbox" name="is_vegetarian" value="1" {{ $customer->is_vegetarian ? 'checked' : '' }}> Vegetarian</label>
            </div>
            <div>
                <input type="hidden" name="no_pork" value="0">
                <label><input type="checkbox" name="no_pork" value="1" {{ $customer->no_pork ? 'checked' : '' }}> No Pork</label>
            </div>
        </div>


        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Customer</button>
    </form>
@endsection
