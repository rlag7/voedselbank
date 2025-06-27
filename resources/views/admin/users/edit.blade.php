@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Edit User Role</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1">User:</label>
            <div class="bg-gray-100 p-2 rounded">{{ $user->name }} ({{ $user->email }})</div>
        </div>

        <div>
            <label for="role" class="block mb-1">Role:</label>
            <select name="role" id="role" class="w-full border rounded px-3 py-2">
                @foreach($roles as $role)
                    <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                        {{ ucfirst($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Update Role</button>
    </form>
@endsection
