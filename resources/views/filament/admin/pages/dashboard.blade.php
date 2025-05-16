<x-filament-panels::page>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <x-filament::card>
            <div class="text-sm text-gray-500">Total Users</div>
            <div class="text-2xl font-bold">{{ $totalUsers }}</div>
        </x-filament::card>

        <x-filament::card>
            <div class="text-sm text-gray-500">Total Products</div>
            <div class="text-2xl font-bold">{{ $totalProducts }}</div>
        </x-filament::card>

        <x-filament::card>
            <div class="text-sm text-gray-500">Total Orders</div>
            <div class="text-2xl font-bold">{{ $totalOrders }}</div>
        </x-filament::card>
    </div>
    
</x-filament-panels::page>
