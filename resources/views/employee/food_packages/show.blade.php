@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Food Package Details</h1>

    <div class="space-y-2 bg-white p-6 rounded shadow">
        <div><strong>Customer:</strong> {{ $foodPackage->customer->person->name ?? '-' }}</div>
        <div><strong>Composition Date:</strong> {{ \Carbon\Carbon::parse($foodPackage->composition_date)->format('d-m-Y') }}</div>
        <div><strong>Distribution Date:</strong>
            {{ $foodPackage->distribution_date ? \Carbon\Carbon::parse($foodPackage->distribution_date)->format('d-m-Y') : 'Pending' }}
        </div>

    </div>

    <a href="{{ route('employee.food_packages.edit', $foodPackage) }}"
       class="mt-4 inline-block text-blue-600 hover:underline">Edit Package</a>
@endsection
