@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $role = $user->roles->pluck('name')->first();
@endphp

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" />
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 shadow-md flex flex-col justify-between">
        <div class="p-6">
            <!-- Profile -->
            <div class="flex items-center space-x-3 mb-10">
                @if ($user->profile_photo_path)
                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&color=fff" class="w-12 h-12 rounded-full object-cover">
                @endif
                <div>
                    <div class="font-semibold">{{ $user->name }}</div>
                    <div class="text-sm text-gray-500 capitalize">{{ $role }}</div>
                </div>
            </div>

            <!-- Role-specific Nav -->
            <nav class="space-y-2">
                @if ($user->hasRole('admin'))
                    <x-dashboard-link route="admin.users.index" label="Users">
                        <i class="fas fa-users mr-2"></i>
                    </x-dashboard-link>
                    <x-dashboard-link route="admin.suppliers.index" label="Suppliers">
                        <i class="fas fa-truck mr-2"></i>
                    </x-dashboard-link>
                    {{-- Add more admin items here --}}

                @elseif ($user->hasRole('employee'))
                    <x-dashboard-link route="employee.food_packages.index" label="Food Packages">
                        <i class="fas fa-box-open mr-2"></i>
                    </x-dashboard-link>
                    <x-dashboard-link route="employee.allergy.index" label="Allergies">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                    </x-dashboard-link>
                    <x-dashboard-link route="employee.customers.index" label="Customers">
                        <i class="fas fa-user-friends mr-2"></i>
                    </x-dashboard-link>

                @elseif ($user->hasRole('volunteer'))
                    {{-- Optional: add volunteer links here if needed --}}

                @elseif ($user->hasRole('user'))
                    {{-- Optional: add regular user links here --}}
                @endif
            </nav>
        </div>

        <!-- Bottom Links -->
        <div class="p-6 space-y-4">
            <a href="/" target="_blank" class="flex items-center text-blue-700 hover:underline">
                <i class="fas fa-globe mr-2"></i> Live Website
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center text-red-600 hover:underline">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Panel -->
    <main class="flex-1 p-8 overflow-y-auto">
        @yield('dashboard-content')
    </main>
</div>
</body>
</html>
