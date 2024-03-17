@php
$pageTitle = "Something";
$itemName = 'Buff Momo';
$itemDescription = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur libero accusantium recusandae quo eligendi atque dolore cum quas hic quae nulla placeat dignissimos harum, suscipit, ex itaque, necessitatibus asperiores. Omnis quisquam sed, reprehenderit, necessitatibus odit placeat ratione, optio suscipit pariatur voluptatem libero vitae modi aut temporibus dicta assumenda quos delectus.";
$itemPrice = 80;
@endphp
<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'QuickPick' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="w-full bg-gray-800 min-h-svh">
    <header class="sticky top-0 z-[999]">
        <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
                <a href="#" class="flex items-center gap-2">
                    <x-dark-application-logo></x-dark-application-logo>
                </a>
                <div class="flex items-center lg:order-2">
                    <a href="#" class="text-gray-800 dark:text-white hover:bg-gray-50 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">Log in</a>
                    <a href="#" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Get started</a>
                    <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                    <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                        <li>
                            <a href="#" class="block py-2 pr-4 pl-3 text-white rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 dark:text-white" aria-current="page">Home</a>
                        </li>
                        <li>
                            <a href="#" class="block py-2 pr-4 pl-3 text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700">Menu</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    {{-- @if ($heroes->count() > 0) 
        <div id="indicator-carousel" class="relative w-full" data-carousel="slide" data-carousel-interval="7000">
            <!-- Carousel wrapper -->
            <div class="relative overflow-hidden h-96 w-full">
                @foreach ($heroes as $hero)
                <!-- Item 1 -->
                <div class="hidden duration-[1500ms] ease-in-out" data-carousel-item>
                    <div class="absolute w-full h-full bg-black/50 z-10"></div>
                    <img src="{{ asset('storage/' . $hero->background_photo_path) }}" class="absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover" alt="...">
                    <div class="absolute z-[999] w-full h-full flex flex-col gap-8 justify-center items-center">
                        <h1 class="text-3xl md:text-6xl font-bold text-white">{{ $hero->header }}</h1>
                        <p class="text-xs md:text-xl text-white md:w-5/12 text-center w-8/12">{{ $hero->description }}</p>
                        <a href="{{ $hero->cta_url }}">
                            <x-primary-button>
                                {{ $hero->cta_text }}
                            </x-primary-button>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @if ($heroes->count() > 1) 
                <!-- Slider indicators -->
                <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                    @foreach ($heroes as $hero)
                    <button type="button" class="w-3 h-3 rounded-full" aria-current="true" aria-label="Slide {{ $hero->id }}" data-carousel-slide-to="{{ $hero->id }}" ></button>
                    @endforeach
                </div>
            @endif
            <!-- Slider controls -->
            @if ($heroes->count() > 1) 
                <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            @endif
        </div>
    @endif --}}

    {{-- <div class="grid grid-cols-2 md:grid-cols-4 max-w-7xl mx-auto bg-gray-800 py-32 gap-4">
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
    </div> --}}
    @livewire('menu')
    <footer class="bg-white rounded-lg shadow sm:flex sm:items-center sm:justify-between p-4 sm:p-6 xl:p-8 dark:bg-gray-800 antialiased">
        <p class="mb-4 text-sm text-center text-gray-500 dark:text-gray-400 sm:mb-0">
            &copy; {{ Carbon\Carbon::now()->format('Y') == '2024' ? '2024' : '2024 - ' . Carbon\Carbon::now()->format('Y') }} <a href="#" class="hover:text-violet-500 duration-300" target="_blank">QuickPick</a>. All rights reserved.
        </p>
        <div class="flex justify-center items-center space-x-1">
            <a href="#" data-tooltip-target="tooltip-facebook" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 8 19">
                    <path fill-rule="evenodd" d="M6.135 3H8V0H6.135a4.147 4.147 0 0 0-4.142 4.142V6H0v3h2v9.938h3V9h2.021l.592-3H5V3.591A.6.6 0 0 1 5.592 3h.543Z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Facebook</span>
            </a>
            <div id="tooltip-facebook" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                Like us on Facebook
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a href="#" data-tooltip-target="tooltip-insta" class="inline-flex justify-center p-2 text-gray-500 rounded-lg cursor-pointer dark:text-gray-400 dark:hover:text-white hover:text-gray-900 hover:bg-gray-100 dark:hover:bg-gray-600">
                {{-- <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path fill="currentColor" d="M12.186 8.672 18.743.947h-2.927l-5.005 5.9-4.44-5.9H0l7.434 9.876-6.986 8.23h2.927l5.434-6.4 4.82 6.4H20L12.186 8.672Zm-2.267 2.671L8.544 9.515 3.2 2.42h2.2l4.312 5.719 1.375 1.828 5.731 7.613h-2.2l-4.699-6.237Z" />
                </svg> --}}
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" viewBox="0 0 256 256" xml:space="preserve">

                    <defs>
                    </defs>
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                        <path fill="currentColor" d="M 45 8.109 c 12.015 0 13.439 0.046 18.184 0.262 c 4.387 0.2 6.77 0.933 8.356 1.549 c 1.955 0.721 3.723 1.872 5.174 3.366 c 1.495 1.452 2.645 3.22 3.366 5.174 c 0.616 1.586 1.349 3.968 1.549 8.356 c 0.216 4.745 0.262 6.168 0.262 18.184 s -0.046 13.439 -0.262 18.184 c -0.2 4.387 -0.933 6.77 -1.549 8.356 c -1.514 3.924 -4.616 7.026 -8.54 8.54 c -1.586 0.616 -3.968 1.349 -8.356 1.549 C 58.44 81.847 57.016 81.893 45 81.893 S 31.56 81.847 26.816 81.63 c -4.387 -0.2 -6.77 -0.933 -8.356 -1.549 c -1.955 -0.721 -3.723 -1.872 -5.174 -3.366 c -1.495 -1.452 -2.645 -3.22 -3.366 -5.174 c -0.616 -1.586 -1.349 -3.968 -1.549 -8.356 c -0.216 -4.745 -0.262 -6.168 -0.262 -18.184 S 8.154 31.562 8.37 26.816 c 0.2 -4.387 0.933 -6.77 1.549 -8.356 c 0.721 -1.955 1.872 -3.723 3.367 -5.174 c 1.452 -1.495 3.22 -2.645 5.174 -3.366 c 1.586 -0.616 3.968 -1.349 8.356 -1.549 C 31.561 8.154 32.984 8.108 45 8.109 M 45 0 C 32.779 0 31.246 0.052 26.447 0.271 c -4.79 0.219 -8.061 0.979 -10.923 2.092 c -3.003 1.13 -5.723 2.901 -7.97 5.19 c -2.29 2.248 -4.061 4.968 -5.192 7.97 c -1.112 2.862 -1.872 6.133 -2.09 10.923 C 0.052 31.246 0 32.779 0 45 s 0.052 13.754 0.272 18.553 c 0.219 4.79 0.979 8.061 2.092 10.923 c 1.13 3.003 2.901 5.723 5.19 7.97 c 2.248 2.289 4.968 4.06 7.97 5.19 c 2.862 1.112 6.133 1.873 10.923 2.092 C 31.247 89.948 32.779 90 45 90 s 13.754 -0.052 18.553 -0.271 c 4.79 -0.219 8.061 -0.979 10.923 -2.092 c 6.044 -2.338 10.823 -7.116 13.161 -13.161 c 1.112 -2.862 1.873 -6.133 2.092 -10.923 C 89.948 58.754 90 57.221 90 45 s -0.052 -13.754 -0.271 -18.553 c -0.219 -4.79 -0.979 -8.061 -2.092 -10.923 c -1.13 -3.003 -2.901 -5.723 -5.19 -7.97 c -2.248 -2.29 -4.968 -4.061 -7.971 -5.191 c -2.862 -1.112 -6.133 -1.872 -10.923 -2.09 C 58.754 0.052 57.221 0 45 0 L 45 0 L 45 0 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <path d="M 45 21.892 c -12.762 0 -23.108 10.346 -23.108 23.108 S 32.238 68.108 45 68.108 S 68.108 57.762 68.108 45 l 0 0 C 68.108 32.238 57.762 21.892 45 21.892 z M 45 60 c -8.284 0 -15 -6.716 -15 -15 s 6.716 -15 15 -15 c 8.284 0 15 6.716 15 15 C 60 53.284 53.284 60 45 60 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        <circle cx="69.02" cy="20.98" r="5.4" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform="  matrix(1 0 0 1 0 0) "/>
                    </g>
                    </svg>
                <span class="sr-only">Instagram</span>
            </a>
            <div id="tooltip-insta" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                Follow us on Instagram
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </footer>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</body>
</html>
