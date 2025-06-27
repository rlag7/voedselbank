@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">All Allergies</h1>

    @if (session('success'))
    <div 
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-lg px-4"
        x-data="{ show: true }"
        x-show="show"
    >
        <div class="bg-green-100 border border-green-300 text-green-900 px-6 py-4 rounded-lg shadow-md relative">
            <button 
                type="button" 
                class="absolute top-2 right-3 text-xl font-bold text-green-800 hover:text-green-900" 
                @click="show = false"
            >
                &times;
            </button>
            <p class="text-base font-medium">
                {{ session('success') }}
            </p>
        </div>
    </div>
@endif


    <a href="{{ route('employee.allergy.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Create Allergy
    </a>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Name</th>
            <th class="p-4 text-left">Description</th>
            <th class="p-4 text-left">Risk</th>
            <th class="p-4 text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
    @forelse($allergies as $allergy)
        <tr class="border-t hover:bg-gray-50">
            <td class="p-4">{{ $allergy->name }}</td>
            <td class="p-4">{{ $allergy->description }}</td>
            <td class="p-4">{{ $allergy->risk }}</td>
            <td class="p-4 flex justify-center space-x-2">
                <a href="{{ route('employee.allergy.show', $allergy) }}" class="text-gray-600 hover:text-black">View</a>
                <a href="{{ route('employee.allergy.edit', $allergy) }}" class="text-blue-600 hover:underline">Edit</a>
                <form method="POST" action="{{ route('employee.allergy.destroy', $allergy) }}"
                      onsubmit="return confirm('Are you sure?')" class="inline-block">
                    @csrf @method('DELETE')
                    <button class="text-red-600 hover:underline">Delete</button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="p-4 text-center text-gray-500">
                Er zijn nog geen allergieën geregistreerd.
            </td>
        </tr>
    @endforelse
</tbody>
    </table>
@endsection
