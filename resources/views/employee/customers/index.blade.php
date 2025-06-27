@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Klantoverzicht</h1>

    {{-- ✅ Meldingen --}}
    @if (session('success') || session('error'))
        <div id="notification"
             class="mb-4 rounded border-l-4 p-4 transition-opacity duration-500
             {{ session('success') ? 'border-green-500 bg-green-50 text-green-700' : 'border-red-500 bg-red-50 text-red-700' }}">
            <p class="font-medium">
                <i class="fa-solid {{ session('success') ? 'fa-check-circle' : 'fa-circle-exclamation' }} mr-2"></i>
                {{ session('success') ?? session('error') }}
            </p>
        </div>
        <script>
            setTimeout(() => {
                const note = document.getElementById('notification');
                if (note) {
                    note.style.opacity = '0';
                    setTimeout(() => note.remove(), 500);
                }
            }, 3000);
        </script>
    @endif

    {{-- Nieuwe klant toevoegen --}}
    <a href="{{ route('employee.customers.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        <i class="fa-solid fa-plus mr-2"></i>Nieuwe Klant
    </a>

    <table class="w-full bg-white rounded shadow text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Naam</th>
            <th class="p-4 text-left">E-mailadres</th>
            <th class="p-4 text-center">Volw.</th>
            <th class="p-4 text-center">Kind.</th>
            <th class="p-4 text-center">Baby’s</th>
            <th class="p-4 text-center">Dieet</th>
            <th class="p-4 text-left">Allergieën</th>
            <th class="p-4 text-center">Actief</th>
            <th class="p-4 text-center">Acties</th>
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
                        <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Vegetarisch</span>
                    @endif
                    @if ($customer->no_pork)
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Geen Varkensvlees</span>
                    @endif
                </td>
                <td class="p-4">
                    @if($customer->allergies->isEmpty())
                        <span class="text-gray-400 italic">Geen</span>
                    @else
                        <div class="flex flex-wrap gap-1">
                            @foreach($customer->allergies as $allergy)
                                <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full"
                                      title="{{ $allergy->description }} | Risico: {{ $allergy->risk }}">
                                    {{ $allergy->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif
                </td>
                <td class="p-4 text-center">
                    <form method="POST" action="{{ route('employee.customers.toggle-active', $customer) }}">
                        @csrf
                        @method('PATCH')
                        <button
                            title="Toggle actief"
                            class="transition duration-300 ease-in-out transform hover:scale-110 {{ $customer->is_active ? 'text-green-600 hover:text-green-800' : 'text-gray-400 hover:text-gray-600' }}">
                            <i class="fa-solid fa-toggle-{{ $customer->is_active ? 'on' : 'off' }} text-xl transition-all"></i>
                        </button>
                    </form>
                </td>
                <td class="p-4 flex justify-center space-x-4 text-sm">
                    <a href="{{ route('employee.customers.show', $customer) }}"
                       class="text-gray-600 hover:text-black" title="Bekijken">
                        <i class="fa-regular fa-eye"></i>
                    </a>
                    <a href="{{ route('employee.customers.edit', $customer) }}"
                       class="text-blue-600 hover:text-blue-800" title="Bewerken">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form method="POST" action="{{ route('employee.customers.destroy', $customer) }}"
                          onsubmit="return confirm('Weet je het zeker?')" class="inline-block" title="Verwijderen">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:text-red-800">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="p-4 text-center text-gray-500">
                    Er zijn nog geen klanten toegevoegd.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
@endsection
