<div wire:poll.1s class="w-full flex flex-col justify-center items-center gap-4">
    <div class="w-10/12 grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="flex flex-col gap-4">
            <div class="bg-amber-600 rounded-lg px-4 py-2 text-2xl font-bold text-gray-100">Being Prepared</div>
            <div class="flex flex-col gap-2">
                @foreach ($ordersPrepping as $order)
                <div class="bg-gray-300 rounded-lg px-4 py-2">
                    <h1 class="text-2xl font-black">Order #{{ $order->id }}</h1>
                    <p>Your order is being prepared please wait.</p>
                </div>
                @endforeach
            </div>
        </div>
        <div class="flex flex-col gap-4">
            <div class="bg-lime-600 text-gray-100 rounded-lg px-4 py-2 text-2xl font-bold">
                Ready to take
            </div>
            <div class="flex flex-col gap-2">
                @foreach ($orders as $order)
                <div class="bg-lime-200 rounded-lg px-4 py-2">
                    <h1 class="text-2xl font-black">Order #{{ $order->id }}</h1>
                    <p>Your order is ready please pick it up from the pickup window</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
