<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sentimental App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-800 via-gray-900 to-black">
    <div class="w-full max-w-md px-8 py-6 bg-gray-800 rounded-lg shadow-lg backdrop-blur-lg bg-opacity-90">
        <!-- Title -->
        <div class="text-center">
            <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-teal-400 via-blue-500 to-purple-600">
                Welcome Back
            </h1>
            <p class="mt-2 text-sm text-gray-400">Enter your credentials to log in.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-400">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    class="block mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none text-gray-100"
                    placeholder="Enter your email"
                    value="{{ old('email') }}"
                    required
                />
                @error('email')
                    <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-400">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="block mt-1 w-full px-4 py-2 bg-gray-900 border border-gray-700 rounded-lg focus:ring-2 focus:ring-teal-500 focus:outline-none text-gray-100"
                    placeholder="Enter your password"
                    required
                />
                @error('password')
                    <span class="mt-2 text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center space-x-2">
                    <input
                        id="remember_me"
                        name="remember"
                        type="checkbox"
                        class="w-4 h-4 text-teal-500 bg-gray-900 border border-gray-700 rounded focus:ring-0 focus:ring-teal-500 focus:outline-none"
                    />
                    <span class="text-sm text-gray-400">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full px-4 py-2 text-white bg-gradient-to-r from-teal-400 to-blue-500 hover:from-teal-500 hover:to-blue-600 rounded-lg focus:outline-none focus:ring-4 focus:ring-teal-500">
                    Log in
                </button>
            </div>
        </form>

        <!-- Footer -->
        <p class="mt-6 text-center text-sm text-gray-400">
            Don’t have an account? <a href="{{ route('register') }}" class="text-teal-500 hover:text-teal-400">Sign up</a>
        </p>
    </div>
</body>
</html>
