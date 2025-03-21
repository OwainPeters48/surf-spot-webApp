<div>
    <h2 class="text-2xl font-bold mb-4">Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email:</label>
            <input type="email" id="email" name="email" class="w-full border rounded p-2">
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700">Password:</label>
            <input type="password" id="password" name="password" class="w-full border rounded p-2">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Register</button>
    </form>
</div>
