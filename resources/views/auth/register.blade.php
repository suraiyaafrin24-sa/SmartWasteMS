<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - SmartBin</title>
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
                <h2 class="text-2xl font-bold text-gray-800">Create Your Account</h2>
                <p class="text-green-600 mt-1 text-sm">To join the SmartBin network.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Phone Number -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('phone')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Sector & Road -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="sector" class="block text-sm font-medium text-gray-700 mb-1">Uttara Sector</label>
                    <select id="sector" name="sector" required
                        class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <option value="">Select Sector</option>
                        @for($i = 1; $i <= 18; $i++)
                            <option value="Sector {{ $i }}" {{ old('sector') == "Sector $i" ? 'selected' : '' }}>Sector {{ $i }}</option>
                        @endfor
                    </select>
                    <x-input-error :messages="$errors->get('sector')" class="mt-1" />
                </div>
                <div>
                    <label for="road" class="block text-sm font-medium text-gray-700 mb-1">Road/Block/House</label>
                    <input id="road" type="text" name="road" value="{{ old('road') }}" required
                        class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="e.g. Road 12">
                    <x-input-error :messages="$errors->get('road')" class="mt-1" />
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Full Address Details</label>
                <input id="address" type="text" name="address" value="{{ old('address') }}"
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all" placeholder="Detailed address">
                <x-input-error :messages="$errors->get('address')" class="mt-1" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                    Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full px-3 py-2 rounded border border-gray-300 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <div class="pt-6">
                <button type="submit" class="submit-btn">
                    Create Account
                </button>
            </div>

            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Sign
                        in</a>
                </p>
            </div>
        </form>
    </div>

</body>

</html>