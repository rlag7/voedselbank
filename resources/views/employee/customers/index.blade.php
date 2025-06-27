@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Customer Overview</h1>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('employee.customers.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Add Customer
    </a>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Name</th>
            <th class="p-4 text-left">Email</th>
            <th class="p-4 text-center">Adults</th>
            <th class="p-4 text-center">Children</th>
            <th class="p-4 text-center">Babies</th>
            <th class="p-4 text-center">Diets</th>
            <th class="p-4 text-left">Allergies</th>
            <th class="p-4 text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($customers as $customer)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-4">{{ $customer->person->name ?? '-' }}</td>
                <td class="p-4">{{ $customer->person->email ?? '-' }}</td>
                <td class="p-4 text-center">{{ $customer->number_of_adults }}</td>
                <td class="p-4 text-center">{{ $customer->number_of_children }}</td>
                <td class="p-4 text-center">{{ $customer->number_of_babies }}</td>
                <td class="p-4 text-center space-x-1">
                    @if ($customer->is_vegan)
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Vegan</span>
                    @endif
                    @if ($customer->is_vegetarian)
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Vegetarian</span>
                    @endif
                    @if ($customer->no_pork)
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">No Pork</span>
                    @endif
                </td>
                <td class="p-4">
                    @if($customer->allergies->isEmpty())
                        <span class="text-gray-400 italic">None</span>
                    @else
                        <div class="flex flex-wrap gap-1">
                            @foreach($customer->allergies as $allergy)
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full"
                                      title="{{ $allergy->description }} | Risk: {{ $allergy->risk }}">
                                    {{ $allergy->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="p-4 flex justify-center space-x-2">
                    <a href="{{ route('employee.customers.show', $customer) }}" class="text-gray-600 hover:text-black">View</a>
                    <a href="{{ route('employee.customers.edit', $customer) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('employee.customers.destroy', $customer) }}"
                          onsubmit="return confirm('Are you sure?')" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-4 text-center text-gray-500">
                    No customers have been added yet.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
