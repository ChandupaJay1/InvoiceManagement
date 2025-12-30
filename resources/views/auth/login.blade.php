<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-3xl font-bold text-gray-900">Welcome Back</h2>
        <p class="text-gray-500 text-sm mt-2">Please sign in to your account</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition duration-300" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>
            <div class="mt-1 relative rounded-md shadow-sm">
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded transition duration-300">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-300 hover:scale-[1.02] active:scale-95">
                Sign in
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-300 font-semibold">
                    Create an account
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
