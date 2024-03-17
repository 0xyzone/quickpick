<div class="bg-violet-900 rounded-2xl flex flex-col overflow-clip text-white hover:scale-105 duration-300 hover:shadow-2xl hover:shadow-purple-900 group">
    <div class="bg-gray-300 rounded-bl-[4rem] overflow-clip shrink-0">
        <img src="{{ $itemImage ? asset('storage/' . $itemImage) : asset('defaultImage.png') }}" alt="" class="group-hover:scale-[1.1] duration-300 w-96 p-10">
    </div>
    <div class="px-8 py-4 w-full space-y-4 flex flex-col justify-between h-full grow-0">
        <div>
            <h1 class="text-sm md:text-2xl font-bold truncate">{{ $itemName }}</h1>
            <p class="line-clamp-3 text-xs md:text-sm">{{ $itemDescription ?? 'No Description' }}</p>
        </div>
        <div class="flex justify-between items-center">
            <p class="shrink-0">रु {{ $itemPrice }}</p>
            <button class="hidden w-12 aspect-square rounded-full bg-fuchsia-600 hover:bg-fuchsia-700 items-center justify-center duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                    <path d="M1 1.75A.75.75 0 0 1 1.75 1h1.628a1.75 1.75 0 0 1 1.734 1.51L5.18 3a65.25 65.25 0 0 1 13.36 1.412.75.75 0 0 1 .58.875 48.645 48.645 0 0 1-1.618 6.2.75.75 0 0 1-.712.513H6a2.503 2.503 0 0 0-2.292 1.5H17.25a.75.75 0 0 1 0 1.5H2.76a.75.75 0 0 1-.748-.807 4.002 4.002 0 0 1 2.716-3.486L3.626 2.716a.25.25 0 0 0-.248-.216H1.75A.75.75 0 0 1 1 1.75ZM6 17.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0ZM15.5 19a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z" />
                </svg>
            </button>
        </div>

    </div>
</div>
