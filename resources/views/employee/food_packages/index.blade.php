@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Alle Voedselpakketten</h1>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    <a href="{{ route('employee.food_packages.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Maak Voedselpakket
    </a>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Klant</th>
            <th class="p-4 text-left">Samengesteld</th>
            <th class="p-4 text-left">Gedistribueerd</th>
            <th class="p-4 text-left">Actief</th>
            <th class="p-4 text-center">Acties</th>
        </tr>
        </thead>
        <tbody>
        @forelse($packages as $package)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-4">{{ $package->customer->person->name ?? 'Onbekend' }}</td>
                <td class="p-4">{{ \Carbon\Carbon::parse($package->composition_date)->format('d-m-Y') }}</td>
                <td class="p-4">
                    {{ $package->distribution_date ? \Carbon\Carbon::parse($package->distribution_date)->format('d-m-Y') : 'In afwachting' }}
                </td>

                <td class="p-4">{{ $package->is_active ? 'Ja' : 'Nee' }}</td>
                <td class="p-4 flex justify-center space-x-2">
                    <a href="{{ route('employee.food_packages.show', $package) }}" class="text-gray-600 hover:text-black">Bekijken</a>
                    <a href="{{ route('employee.food_packages.edit', $package) }}" class="text-blue-600 hover:underline">Bewerken</a>
                    <form method="POST" action="{{ route('employee.food_packages.destroy', $package) }}"
                          onsubmit="return confirm('Weet je het zeker?')" class="inline-block">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Verwijderen</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="p-4 text-center text-gray-500">Er zijn geen voedselpakketten gemaakt.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Pagination links -->
    <div class="mt-4">
        {{ $packages->links() }}
    </div>
@endsection
