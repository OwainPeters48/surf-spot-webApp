<x-app-layout>
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-xl font-semibold">{{ $user->name }}'s Profile</h2>
        <p>Email: {{ $user->email }}</p>
        <p>Role: {{ $user->role }}</p>

        <h3 class="text-lg font-semibold mt-6">User's Surf Spots</h3>
        @if ($user->surfSpots->isEmpty())
            <p>No surf spots created by this user yet.</p>
        @else
            <ul>
                @foreach ($user->surfSpots as $spot)
                    <li>
                        <a href="{{ route('surf-spots.show', $spot->id) }}" class="text-blue-500 hover:underline">
                            {{ $spot->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <h3 class="text-lg font-semibold mt-6">User's Comments</h3>
        @if ($user->comments->isEmpty())
            <p>No comments by this user yet.</p>
        @else
            <ul>
                @foreach ($user->comments as $comment)
                    <li>
                        <strong>
                            <a href="{{ route('surf-spots.show', $comment->surfSpot->id) }}" class="text-blue-500 hover:underline">
                                {{ $comment->surfSpot->name }}
                            </a>
                        </strong>: "{{ $comment->content }}"
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
