@extends('dashboard')

@section('dashboard-content')
    <h1 class="text-2xl font-semibold mb-4">Create New User</h1>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-1">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>

        <div>
            <label class="block mb-1">Role</label>
            <select name="role" class="w-full border rounded px-3 py-2" required>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">Create</button>
    </form>
@endsection
