@extends('dashboard')

@section('dashboard-content')
    <script src="https://cdn.tailwindcss.com"></script>
    <h1 class="text-2xl font-semibold mb-4">Supplier Details</h1>

    <div class="space-y-2 bg-white p-6 rounded shadow">
        <div><strong>Company Name:</strong> {{ $supplier->company_name }}</div>
        <div><strong>Address:</strong> {{ $supplier->address }}</div>
        <div><strong>Contact Name:</strong> {{ $supplier->contact_name }}</div>
        <div><strong>Contact Email:</strong> {{ $supplier->contact_email }}</div>
        <div><strong>Phone:</strong> {{ $supplier->phone }}</div>
        <div><strong>Next Delivery:</strong> {{ $supplier->next_delivery ?? 'N/A' }}</div>
    </div>

    <a href="{{ route('admin.suppliers.edit', $supplier) }}"
       class="mt-4 inline-block text-blue-600 hover:underline">Edit Supplier</a>
@endsection
