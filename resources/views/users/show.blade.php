<!-- resources/views/users/show.blade.php -->
<x-app-layout>
    <div class="p-6 bg-white border-b border-gray-200">
        <h1 class="text-xl font-semibold">{{ $user->name }}</h1>
        <p>Email: {{ $user->email }}</p>
        <p>Role: {{ $user->role }}</p>

        <h2 class="text-lg font-semibold mt-4">Surf Spots Created</h2>
        @if ($user->surfSpots->isEmpty())
            <p>No surf spots created by this user.</p>
        @else
            <ul>
                @foreach ($user->surfSpots as $spot)
                    <li>{{ $spot->name }}</li>
                @endforeach
            </ul>
        @endif

        <h2 class="text-lg font-semibold mt-4">Comments</h2>
        @if ($user->comments->isEmpty())
            <p>No comments from this user.</p>
        @else
            <ul>
                @foreach ($user->comments as $comment)
                    <li>
                        <strong>{{ $comment->surfSpot->name }}:</strong> {{ $comment->content }}
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
