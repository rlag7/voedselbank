<script src="https://cdn.tailwindcss.com"></script>

@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Voedselpakket Details</h1>

    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow p-6 space-y-6">
        <div class="flex items-center">
            <span class="w-40 font-semibold text-gray-700">Klant:</span>
            <span class="text-gray-900">{{ $foodPackage->customer->person->name ?? '-' }}</span>
        </div>

        <div class="flex items-center">
            <span class="w-40 font-semibold text-gray-700">Samenstel Datum:</span>
            <span class="text-gray-900">{{ \Carbon\Carbon::parse($foodPackage->composition_date)->format('d-m-Y') }}</span>
        </div>

        <div class="flex items-center">
            <span class="w-40 font-semibold text-gray-700">Distributiedatum:</span>
            <span class="text-gray-900">
                {{ $foodPackage->distribution_date ? \Carbon\Carbon::parse($foodPackage->distribution_date)->format('d-m-Y') : 'In afwachting' }}
            </span>
        </div>

        <div class="flex items-center">
            <span class="w-40 font-semibold text-gray-700">Actief:</span>
            @if ($foodPackage->is_active)
                <span class="inline-block px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                    Ja
                </span>
            @else
                <span class="inline-block px-3 py-1 text-xs font-semibold text-gray-700 bg-gray-200 rounded-full">
                    Nee
                </span>
            @endif
        </div>

        <div class="pt-4 border-t border-gray-200 text-center">
            <a href="{{ route('employee.food_packages.edit', $foodPackage) }}"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-md shadow transition font-semibold">
                Bewerken
            </a>
            <a href="{{ route('employee.food_packages.index') }}"
               class="inline-block ml-4 text-gray-600 hover:text-gray-900 font-semibold">
                Terug naar overzicht
            </a>
        </div>
    </div>
@endsection
