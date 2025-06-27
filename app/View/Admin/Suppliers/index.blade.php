@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Alle Leveranciers</h1>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

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
@endsection
