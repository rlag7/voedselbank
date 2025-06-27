@extends('dashboard')

@section('dashboard-content')


    <h1 class="text-2xl font-semibold mb-4">Edit Allergy</h1>


@if ($errors->any())
    <div 
        class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-lg px-4"
        x-data="{ show: true }" 
        x-show="show"
    >
        <div class="bg-red-100 border border-red-300 text-red-800 px-6 py-4 rounded-lg shadow-md relative">
            <button 
                type="button" 
                class="absolute top-2 right-3 text-2xl leading-none text-red-600 hover:text-red-800" 
                @click="show = false"
            >
                &times;
            </button>

            <strong class="block mb-1 font-semibold"></strong>
            <ul class="list-disc list-inside text-lg">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif


    <form method="POST" action="{{ route('employee.allergy.update', $allergy) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" value="{{ $allergy->name }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Description</label>
            <input type="text" name="description" value="{{ $allergy->description }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Risk</label>
            <input type="text" name="risk" value="{{ $allergy->risk }}"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Allergy</button>
    </form>
@endsection
