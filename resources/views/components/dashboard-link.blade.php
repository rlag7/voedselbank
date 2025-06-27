@props(['route', 'label'])

<a href="{{ route($route) }}"
   class="flex items-center p-3 rounded-xl bg-gray-50 hover:bg-blue-100 text-blue-800 border border-blue-200 shadow transition-all">
    {{ $slot }}
    <span class="ml-2 font-semibold">{{ $label }}</span>
</a>
