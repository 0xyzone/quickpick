@php
$pageTitle = "Something";
$itemName = 'Buff Momo';
$itemDescription = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur libero accusantium recusandae quo eligendi atque dolore cum quas hic quae nulla placeat dignissimos harum, suscipit, ex itaque, necessitatibus asperiores. Omnis quisquam sed, reprehenderit, necessitatibus odit placeat ratione, optio suscipit pariatur voluptatem libero vitae modi aut temporibus dicta assumenda quos delectus.";
$itemPrice = 80;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle ?? 'QuickPick' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="w-full">
    <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-end z-10">
        @auth
            <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" wire:navigate>Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" wire:navigate>Log in</a>
    
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="ms-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500" wire:navigate>Register</a>
            @endif
        @endauth
    </div>
    <div class="flex justify-center items-center bg-gray-800 py-32">
        <x-item-card :itemName=$itemName :itemDescription=$itemDescription :itemPrice=$itemPrice></x-item-card>
    </div>
</body>
</html>
