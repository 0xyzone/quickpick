<x-filament-panels::page>
        <div class="w-auto shrink-0 h-max p-4 bg-gray-800 rounded-lg">
            <h2 class="text-gray-100">Orders out for delivery</h2>
            <div class="flex gap-2 mt-2">
            @foreach (App\Models\Order::where('status','out_delivery')->get() as $delivery)
            <p class="p-2 px-4 bg-primary-800 rounded-lg">Order #{{ $delivery->id }}</p>
            @endforeach
            </div>
        </div>
    <div x-data wire:ignore.self class="md:flex overflow-x-auto overflow-y-hidden gap-4 pb-4" wire:poll.5s>
        @foreach($statuses as $status)
        @include(static::$statusView)
        @endforeach

        <div wire:ignore>
            @include(static::$scriptsView)
        </div>
    </div>

    @unless($disableEditModal)
    <x-filament-kanban::edit-record-modal />
    @endunless
</x-filament-panels::page>
