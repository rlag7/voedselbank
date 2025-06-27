@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Customer Details</h1>

    <div class="space-y-2 bg-white p-6 rounded shadow">
        <div><strong>Name:</strong> {{ $customer->person->name }}</div>
        <div><strong>Email:</strong> {{ $customer->person->email }}</div>
        <div><strong>Phone:</strong> {{ $customer->person->phone ?? '-' }}</div>
        <div><strong>Adults:</strong> {{ $customer->number_of_adults }}</div>
        <div><strong>Children:</strong> {{ $customer->number_of_children }}</div>
        <div><strong>Babies:</strong> {{ $customer->number_of_babies }}</div>
        <div><strong>Vegan:</strong> {{ $customer->is_vegan ? 'Yes' : 'No' }}</div>
        <div><strong>Vegetarian:</strong> {{ $customer->is_vegetarian ? 'Yes' : 'No' }}</div>
        <div><strong>No Pork:</strong> {{ $customer->no_pork ? 'Yes' : 'No' }}</div>
    </div>

    <a href="{{ route('employee.customers.edit', $customer) }}"
       class="mt-4 inline-block text-blue-600 hover:underline">Edit Customer</a>
@endsection
