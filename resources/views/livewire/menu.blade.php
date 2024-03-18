<div>
    <div class="flex gap-2 max-w-7xl mx-auto justify-center">

        @foreach ($categories as $category)
        <button wire:click="changeCategory({{ $category->id }})" class="px-2 py-2 rounded-lg duration-300 {{ $activeCategory == $category->id ? 'bg-primary-500 text-white' : 'bg-gray-200' }}">
        {{ $category->name }}
        </button>
        @endforeach
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 max-w-7xl mx-auto bg-gray-800 py-8 gap-4 px-6 duration-300" wire:differ>
        @foreach ($items as $item)
        @php
        $itemName = $item->name;
        $itemDescription = $item->description;
        $itemPrice = $item->price;
        $itemImage = $item->item_photo_path;
        @endphp
        <x-item-card :itemName=$itemName :itemDescription=$itemDescription :itemPrice=$itemPrice :itemImage=$itemImage></x-item-card>
        @endforeach
    </div>

    <div class="max-w-7xl mx-auto">
        {{ $items->links() }}
    </div>
</div>
