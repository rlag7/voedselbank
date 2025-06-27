<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Leveranciers Overzicht</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Alle Leveranciers</h1>

    <a href="{{ route('admin.suppliers.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Leverancier toevoegen
    </a>

    @if ($suppliers->count())
        <div class="overflow-x-auto">
            <table class="w-full bg-white rounded shadow text-sm">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Bedrijfsnaam</th>
                    <th class="p-3 text-left">Contactpersoon</th>
                    <th class="p-3 text-left">Email</th>
                    <th class="p-3 text-left">Type</th>
                    <th class="p-3 text-left">Leveranciersnummer</th>
                    <th class="p-3 text-left">Actief</th>
                    <th class="p-3 text-left">Producten</th>
                    <th class="p-3 text-left">Acties</th>
                </tr>
                </thead>
                <tbody>
                @foreach($suppliers as $supplier)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-3 max-w-[150px] truncate" title="{{ $supplier->company_name }}">{{ $supplier->company_name }}</td>
                        <td class="p-3">{{ $supplier->contact_name }}</td>
                        <td class="p-3">{{ $supplier->contact_email }}</td>
                        <td class="p-3">{{ ucfirst($supplier->supplier_type) }}</td>
                        <td class="p-3">{{ $supplier->supplier_number }}</td>
                        <td class="p-3">
                            @if ($supplier->is_active)
                                <span class="text-green-600 font-semibold">Ja</span>
                            @else
                                <span class="text-gray-500">Nee</span>
                            @endif
                        </td>
                        <td class="p-3">
                            @forelse($supplier->products as $product)
                                <div class="truncate" title="{{ $product->name }}">
                                    {{ $product->name }} ({{ $product->pivot->stock_quantity }})
                                </div>
                            @empty
                                Geen producten
                            @endforelse
                        </td>
                        <td class="p-3 space-x-2">
                            <a href="{{ route('admin.suppliers.show', $supplier) }}" class="text-gray-600 hover:text-black">Bekijken</a>
                            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="text-blue-600 hover:underline">Wijzig</a>
                            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}"
                                  onsubmit="return confirm('Weet je zeker dat je deze leverancier wilt verwijderen?')" class="inline-block">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Verwijder</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">Er zijn geen leveranciers beschikbaar.</p>
    @endif

    {{-- Modal voor foutmelding --}}
    @if (session('error'))
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-red-500 text-white p-6 rounded shadow text-center w-96">
                <p>{{ session('error') }}</p>
                <button onclick="this.closest('div').parentElement.remove()"
                        class="mt-4 px-4 py-2 bg-white text-red-600 rounded">Sluiten</button>
            </div>
        </div>
    @endif

    {{-- Modal voor succesmelding --}}
    @if (session('success'))
        <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-green-500 text-white p-6 rounded shadow text-center w-96">
                <p>{{ session('success') }}</p>
                <button onclick="this.closest('div').parentElement.remove()"
                        class="mt-4 px-4 py-2 bg-white text-green-600 rounded">Sluiten</button>
            </div>
        </div>
    @endif
@endsection

</body>
</html>
