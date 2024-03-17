<div class="mt-16 text-2xl text-white space-x-2 translate-x-6">
    @auth
        <a href="{{ route('home') }}" class="font-semibold text-gray-100 hover:text-gray-300 bg-primary-600 px-4 py-2 rounded-lg hover:bg-primary-800 duration-150">Dashboard</a>
    @else
        <a href="{{ route('login') }}" class="font-semibold text-gray-100 hover:text-gray-300 bg-primary-600 px-4 py-2 rounded-lg hover:bg-primary-800 duration-150">Log in</a>

        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="font-semibold hover:bg-gray-100 hover:text-primary-700 px-4 py-2 rounded-lg duration-150">Register</a>
        @endif
    @endauth
</div>
