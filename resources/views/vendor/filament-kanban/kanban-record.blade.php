<div id="{{ $record->getKey() }}" wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})" class="record bg-white dark:bg-gray-700 rounded-lg px-4 py-4 cursor-grab font-medium text-gray-600 dark:text-gray-200" @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3) x-data x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        " @endif>
        {{-- {{ $record->{static::$recordTitleAttribute} }} --}}
        <div>
            <p class="font-bold {{ $record->status == 'ready' ? 'text-lime-500' : ($record->status == 'preparing' ? "text-amber-500" : "text-sky-500") }}">Order #{{ $record->id }}</p>
        </div>
        <div class="flex flex-col gap-2">
            @foreach ($record->orderItems as $item)
            <div class="flex justify-between border-b-white/50 border-b last:border-none py-2 last:pb-0">
                <p>{{ $item->item->name }}</p>
                <p>{{ $item->quantity == floor($item->quantity) ? number_format($item->quantity) : number_format($item->quantity, 1) }}</p>
            </div>
            @endforeach
        </div>
        @if ($record->notes) 
            <div class="text-sm bg-gray-800 p-4 rounded-lg mt-4">
                {{ $record->notes }}
            </div>
        @endif
</div>
