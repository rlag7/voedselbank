@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Allergy Details</h1>

    <div class="space-y-2 bg-white p-6 rounded shadow">
        <div><strong>Name:</strong> {{ $allergy->name }}</div>
        <div><strong>Description:</strong> {{ $allergy->description }}</div>
        <div><strong>Risk:</strong> {{ $allergy->risk }}</div>
    </div>

    <a href="{{ route('employee.allergy.edit', $allergy) }}"
       class="mt-4 inline-block text-blue-600 hover:underline">Edit Allergy</a>
@endsection
