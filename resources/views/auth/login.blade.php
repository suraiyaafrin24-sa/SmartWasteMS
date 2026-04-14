<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SmartBin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            background-color: #ffffff;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .form-container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border: 1px solid #c2cfcbff;
            border-radius: 0.5rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        .submit-btn {
            width: 100%;
            background-color: #084838ff;
            color: white;
            padding: 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            display: block;
        }

        .submit-btn:hover {
            background-color: #11685cff;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <div class="mb-6">
            <div class="text-center mt-2">
                <h2 class="text-2xl font-bold text-gray-800">Login</h2>
                <p class="text-green-600 mt-1 text-sm">Please enter your credentials to login.</p>
            </div>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <label for="remember_me" class="ms-2 text-sm text-gray-600">Remember me</label>
            </div>

            <div class="pt-2">
                <button type="submit" class="submit-btn">
                    Login
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Don't have an account? <a href="{{ route('register') }}"
                        class="text-blue-600 hover:underline">Register</a>
                </p>
            </div>
        </form>
    </div>

</body>

</html>