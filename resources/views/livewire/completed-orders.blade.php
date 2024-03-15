<div wire:poll.1s class="w-full flex flex-col justify-center items-center gap-4">
    @foreach ($orders as $order)
        <div class="w-8/12 bg-gray-300 rounded-lg px-4 py-2">
            <h1 class="text-2xl font-black">Order #{{ $order->id }}</h1>
            <p>Your order is ready please pick it up from the pickup window</p>
        </div>
    @endforeach
</div>
