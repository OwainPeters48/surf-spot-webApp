<div class="w-full max-w-md mx-auto mt-10 p-6 bg-white rounded shadow-md">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-semibold">Email:</label>
            <input type="email" id="email" name="email" class="w-full border rounded p-2 focus:ring focus:ring-blue-300" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-gray-700 font-semibold">Password:</label>
            <input type="password" id="password" name="password" class="w-full border rounded p-2 focus:ring focus:ring-blue-300" required>
            @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 flex items-center">
            <input type="checkbox" id="remember" name="remember" class="rounded border-gray-300 focus:ring focus:ring-blue-300">
            <label for="remember" class="ml-2 text-gray-700 text-sm">Remember Me</label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:ring focus:ring-blue-300">
                Login
            </button>
        </div>
    </form>
</div>
