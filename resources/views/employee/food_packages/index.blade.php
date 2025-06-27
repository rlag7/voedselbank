<script src="https://cdn.tailwindcss.com"></script>

@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-xl font-semibold text-center mb-6">Alle Voedselpakketen</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded mb-6 max-w-2xl mx-auto text-sm shadow-sm">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif


    <div class="flex justify-end mb-4">
        <a href="{{ route('employee.food_packages.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
            + Maak Voedselpakket
        </a>
    </div>


    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300 text-sm text-left">
            <thead>
            <tr class="bg-gray-200">
                <th class="border border-gray-300 px-4 py-2">Klant</th>
                <th class="border border-gray-300 px-4 py-2">Samengesteld</th>
                <th class="border border-gray-300 px-4 py-2">Gedistribueerd</th>
                <th class="border border-gray-300 px-4 py-2">Actief</th>
                <th class="border border-gray-300 px-4 py-2 text-center">Acties</th>
            </tr>
            </thead>
            <tbody>
            @forelse($packages as $package)
                <tr class="bg-gray-100 hover:bg-gray-200">
                    <td class="border border-gray-300 px-4 py-2">{{ $package->customer->person->name ?? 'Onbekend' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($package->composition_date)->format('d-m-Y') }}</td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $package->distribution_date ? \Carbon\Carbon::parse($package->distribution_date)->format('d-m-Y') : 'In afwachting' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $package->is_active ? 'Ja' : 'Nee' }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('employee.food_packages.show', $package) }}" class="text-gray-600 hover:text-black">Bekijken</a>
                            <a href="{{ route('employee.food_packages.edit', $package) }}" class="text-blue-600 hover:underline">Bewerken</a>
                            <form method="POST" action="{{ route('employee.food_packages.destroy', $package) }}"
                                  onsubmit="return confirm('Weet je het zeker?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Verwijderen</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="border border-gray-300 px-4 py-4 text-center text-gray-500">
                        Er zijn geen voedselpakketten gemaakt.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $packages->links() }}
    </div>

@endsection
