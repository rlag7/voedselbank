@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Create New Allergy</h1>

    <form method="POST" action="{{ route('employee.allergy.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Description</label>
            <input type="text" name="description" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Risk</label>
            <input type="text" name="risk" class="w-full border rounded px-3 py-2" required>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Create Allergy</button>
    </form>
@endsection
