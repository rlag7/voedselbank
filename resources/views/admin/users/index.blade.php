@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">All Users</h1>

    @if (session('success'))
        <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="text-red-600 mb-4">{{ session('error') }}</div>
    @endif

    <a href="{{ route('admin.users.create') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block hover:bg-blue-700 transition">
        + Create User
    </a>

    <table class="w-full bg-white rounded shadow">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-4 text-left">Name</th>
            <th class="p-4 text-left">Email</th>
            <th class="p-4 text-left">Role</th>
            <th class="p-4 text-center">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="border-t hover:bg-gray-50">
                <td class="p-4">{{ $user->name }}</td>
                <td class="p-4">{{ $user->email }}</td>
                <td class="p-4">{{ $user->roles->pluck('name')->first() ?? 'None' }}</td>
                <td class="p-4 flex justify-center space-x-2">
                    <a href="{{ route('admin.users.show', $user) }}" class="text-gray-600 hover:text-black">View</a>
                    <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:underline">Edit</a>
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                          onsubmit="return confirm('Are you sure?')" class="inline-block">
                        @csrf @method('DELETE')
                        <button class="text-red-600 hover:underline">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
