@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">User Details</h1>

    <div class="space-y-2 bg-white p-6 rounded shadow">
        <div><strong>Name:</strong> {{ $user->name }}</div>
        <div><strong>Email:</strong> {{ $user->email }}</div>
        <div><strong>Role:</strong> {{ $user->roles->pluck('name')->first() ?? 'None' }}</div>
    </div>

    <a href="{{ route('admin.users.edit', $user) }}"
       class="mt-4 inline-block text-blue-600 hover:underline">Edit User</a>
@endsection
