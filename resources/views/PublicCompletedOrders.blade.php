<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Completed Orders</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900">
<div class="w-full min-h-svh flex flex-col justify-center items-center gap-6">
    <h1 class="text-white text-6xl">Order Status</h1>
    @livewire('completed-orders')
</div>
</body>
</html>
