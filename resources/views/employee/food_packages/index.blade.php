<script src="https://cdn.tailwindcss.com"></script>

@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-xl font-semibold text-center mb-6">Alle Voedselpakketten</h1>

    {{-- Success message --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded mb-4 shadow-sm max-w-2xl mx-auto text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Delete error message --}}
    @if ($errors->has('delete'))
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-4 shadow-sm max-w-2xl mx-auto text-sm">
            {{ $errors->first('delete') }}
        </div>
    @endif

    <div class="flex justify-end mb-4">
        <a href="{{ route('employee.food_packages.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition text-sm">
            + Maak Voedselpakket
        </a>
    </div>

    <div class="overflow-x-auto" x-data="{ showModal: false, deleteUrl: '' }">
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
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $package->customer->person->name ?? 'Onbekend' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ \Carbon\Carbon::parse($package->composition_date)->format('d-m-Y') }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $package->distribution_date
                            ? \Carbon\Carbon::parse($package->distribution_date)->format('d-m-Y')
                            : 'In afwachting' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        {{ $package->is_active ? 'Ja' : 'Nee' }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('employee.food_packages.show', $package) }}" class="text-gray-600 hover:text-black">
                                Bekijken
                            </a>
                            <a href="{{ route('employee.food_packages.edit', $package) }}" class="text-blue-600 hover:underline">
                                Bewerken
                            </a>
                            <button
                                type="button"
                                class="text-red-600 hover:underline cursor-pointer"
                                @click="showModal = true; deleteUrl = '{{ route('employee.food_packages.destroy', $package) }}'"
                            >
                                Verwijderen
                            </button>
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

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $packages->links() }}
        </div>

        {{-- Modal --}}
        <div
            x-show="showModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @keydown.escape.window="showModal = false"
            style="display: none;"
        >
            <div
                class="bg-white rounded shadow-lg p-6 max-w-md w-full"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200 transform"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                @click.away="showModal = false"
            >
                <h2 class="text-lg font-semibold mb-4">Weet je het zeker?</h2>
                <p class="mb-4">Weet je zeker dat je dit voedselpakket wilt verwijderen?</p>
                <div class="justify-end space-x-4">
                    <button
                        @click="showModal = false"
                        class="px-6 py-3 rounded-lg bg-gray-300 text-gray-800 font-semibold hover:bg-gray-400 transition focus:outline-none focus:ring-2 focus:ring-gray-400 leading-none">
                        Annuleren
                    </button>
                    <form :action="deleteUrl" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-3 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700 transition focus:outline-none focus:ring-2 focus:ring-red-500 leading-none" style="line-height: 1;">
                            Verwijderen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Alpine.js CDN --}}
    <script src="//unpkg.com/alpinejs" defer></script>
@endsection
