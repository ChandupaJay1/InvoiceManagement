<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-3xl font-bold text-gray-900">Create Account</h2>
        <p class="text-gray-500 text-sm mt-2">Join us and start managing your invoices</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <div class="mt-1">
                <input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="John Doe">
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
            <div class="mt-1">
                <input id="email" type="email" name="email" :value="old('email')" required autocomplete="username"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="you@example.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <div class="mt-1">
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <div class="mt-1">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full px-4 py-3 rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-white/50 backdrop-blur-sm"
                    placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-300 hover:scale-[1.02] active:scale-95">
                Register
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition duration-300 font-semibold">
                    Sign in
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
