@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">All Customers</h1>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('employee.customers.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Create Customer
    </a>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Name</th>
            <th class="p-4 text-left">Email</th>
            <th class="p-4 text-left">Adults / Children / Babies</th>
            <th class="p-4 text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-4">{{ $customer->person->name ?? '-' }}</td>
                <td class="p-4">{{ $customer->person->email ?? '-' }}</td>
                <td class="p-4">
                    {{ $customer->number_of_adults }} / {{ $customer->number_of_children }} / {{ $customer->number_of_babies }}
                </td>
                <td class="p-4 flex justify-center space-x-2">
                    <a href="{{ route('employee.customers.show', $customer) }}" class="text-gray-600 hover:text-black">View</a>
                    <a href="{{ route('employee.customers.edit', $customer) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('employee.customers.destroy', $customer) }}"
                          onsubmit="return confirm('Are you sure?')" class="inline-block">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
