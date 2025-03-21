<x-app-layout>
    <section class="p-6 bg-white border-b border-gray-200">
        <header class="mb-4">
            <h2 class="text-xl font-semibold">Welcome, {{ Auth::user()->name }}!</h2>
            <p>You're logged in!</p>
        </header>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button 
                type="submit" 
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300 transition"
                aria-label="Log out of your account"
            >
                Logout
            </button>
        </form>

        <!-- User's Comments Section -->
        <section>
            <h3 class="text-lg font-semibold mt-6">Your Comments</h3>
            @if ($userComments->isEmpty())
                <p>You have not commented on any surf spots yet.</p>
            @else
                @foreach ($userComments as $comment)
                    <article class="my-2 border-b border-gray-300 pb-2">
                        <p>
                            <strong>{{ $comment->surfSpot->name }}</strong>: 
                            <q>{{ $comment->content }}</q>
                        </p>
                    </article>
                @endforeach
            @endif
        </section>

        <!-- Surf Spots You Liked Section -->
        @if ($likedSurfSpots->isNotEmpty())
            <section>
                <h3 class="mt-6 text-lg font-semibold">Surf Spots You Liked</h3>
                <ul>
                    @foreach ($likedSurfSpots as $surfSpot)
                        <li class="mt-4 border-b border-gray-300 pb-4">
                            <h4 class="font-semibold">{{ $surfSpot->name }}</h4>
                            <p>Location: {{ $surfSpot->location }}</p>
                            <p>Description: {{ $surfSpot->description }}</p>
                            <p>Difficulty: {{ ucfirst($surfSpot->difficulty) }}</p>
                            <p>Total Likes: {{ $surfSpot->users()->count() }}</p>

                            @php
                                $userComment = $surfSpot->comments->where('user_id', Auth::id())->first();
                            @endphp
                            @if ($userComment)
                                <p>Your Comment: <q>{{ $userComment->content }}</q></p>
                            @else
                                <p>You haven't commented on this spot yet.</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </section>
        @else
            <p class="mt-6 text-sm text-gray-500">You haven't liked any surf spots yet.</p>
        @endif
    
        <!-- ARIA Live Regions for Accessibility -->
        <div id="success-message" class="text-green-600 font-bold mb-4" aria-live="polite"></div>
        <div id="error-message" class="text-red-600 font-bold mb-4" aria-live="assertive"></div>

                <!-- All Surf Spots Section -->
                <section aria-labelledby="surf-spots-title">
                    <h3 id="surf-spots-title" class="text-lg font-semibold mt-6">All Surf Spots</h3>
                    <div id="surf-spot-list">
                        @foreach ($allSurfSpots as $surfSpot)
                            <article class="border p-4 mb-4 rounded" aria-labelledby="surf-spot-{{ $surfSpot->id }}-title">
                                <!-- Surf Spot Details -->
                                <header>
                                    <h4 id="surf-spot-{{ $surfSpot->id }}-title" class="text-xl font-semibold">{{ $surfSpot->name }}</h4>
                                </header>
                                <p>Location: {{ $surfSpot->location }}</p>
                                <p>Description: {{ $surfSpot->description }}</p>
                                <p>Difficulty: {{ ucfirst($surfSpot->difficulty) }}</p>
                                <p>Total Views: {{ $surfSpot->view_count }}</p>
                                <p>Likes: {{ $surfSpot->users()->count() }}</p>

                                <!-- Admin-Only Section -->
                                @if (Auth::user()->role === 'admin')
                                    <p class="text-red-500">Admin-only: You can manage this surf spot.</p>

                                    <!-- Edit Button -->
                                    <button 
                                        class="bg-blue-500 text-white px-4 py-2 rounded mt-2 focus:outline-none focus:ring focus:ring-blue-300"
                                        onclick="toggleEditForm({{ $surfSpot->id }})"
                                        aria-label="Edit details for surf spot {{ $surfSpot->name }}"
                                    >
                                        Edit Surf Spot
                                    </button>

                                    <!-- Edit Form -->
                                    <div id="edit-form-{{ $surfSpot->id }}" class="hidden mt-4">
                                        <form id="edit-surfspot-form-{{ $surfSpot->id }}" aria-label="Edit form for {{ $surfSpot->name }}">
                                            @csrf
                                            <div class="grid gap-2">
                                                <label for="name-{{ $surfSpot->id }}">Name</label>
                                                <input 
                                                    type="text" 
                                                    id="name-{{ $surfSpot->id }}"
                                                    name="name" 
                                                    value="{{ $surfSpot->name }}" 
                                                    class="border p-2 rounded w-full"
                                                    required
                                                />

                                                <label for="location-{{ $surfSpot->id }}">Location</label>
                                                <input 
                                                    type="text" 
                                                    id="location-{{ $surfSpot->id }}"
                                                    name="location" 
                                                    value="{{ $surfSpot->location }}" 
                                                    class="border p-2 rounded w-full"
                                                    required
                                                />

                                                <label for="description-{{ $surfSpot->id }}">Description</label>
                                                <textarea 
                                                    id="description-{{ $surfSpot->id }}"
                                                    name="description" 
                                                    class="border p-2 rounded w-full"
                                                    required
                                                >{{ $surfSpot->description }}</textarea>

                                                <label for="difficulty-{{ $surfSpot->id }}">Difficulty</label>
                                                <select 
                                                    id="difficulty-{{ $surfSpot->id }}"
                                                    name="difficulty" 
                                                    class="border p-2 rounded w-full"
                                                    required
                                                >
                                                    <option value="beginner" {{ $surfSpot->difficulty === 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="intermediate" {{ $surfSpot->difficulty === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="advanced" {{ $surfSpot->difficulty === 'advanced' ? 'selected' : '' }}>Advanced</option>
                                                </select>

                                                <button 
                                                    type="button" 
                                                    class="bg-green-500 text-white px-4 py-2 rounded mt-2 focus:outline-none focus:ring focus:ring-green-300"
                                                    onclick="editSurfSpot(event, {{ $surfSpot->id }})"
                                                    aria-label="Update details for surf spot {{ $surfSpot->name }}"
                                                >
                                                    Update Surf Spot
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                                <!-- Comment Form -->
                                <form id="comment-form-{{ $surfSpot->id }}" class="mt-4" aria-label="Comment form for {{ $surfSpot->name }}">
                                    @csrf
                                    <label for="comment-content-{{ $surfSpot->id }}">Write a comment</label>
                                    <textarea
                                        id="comment-content-{{ $surfSpot->id }}"
                                        name="content"
                                        placeholder="Write your comment here"
                                        class="border p-2 rounded w-full"
                                        required
                                    ></textarea>
                                    <button
                                        type="submit"
                                        class="bg-blue-500 text-white px-4 py-2 rounded mt-2 focus:outline-none focus:ring focus:ring-blue-300"
                                        onclick="postComment(event, {{ $surfSpot->id }})"
                                        aria-label="Post comment on surf spot {{ $surfSpot->name }}"
                                    >
                                        Post Comment
                                    </button>
                                </form>

                                <!-- Comment List -->
                                <div id="comments-list-{{ $surfSpot->id }}" class="mt-4" aria-label="Comments for {{ $surfSpot->name }}">
                                    @foreach ($surfSpot->comments as $comment)
                                    <div class="comment border p-3 rounded mb-3 flex justify-between items-center" id="comment-{{ $comment->id }}">
                                        <p>
                                            <strong class="text-gray-800">{{ $comment->user->name }}</strong>: 
                                            <span class="comment-content text-gray-600">{{ $comment->content }}</span>
                                        </p>
                                        <div class="flex gap-2">
                                            @if (Auth::user() && Auth::user()->role === 'admin')
                                                <!-- Edit Comment Button -->
                                                <button
                                                    class="bg-yellow-500 text-white px-3 py-1 rounded text-sm hover:bg-yellow-600 focus:ring focus:ring-yellow-300"
                                                    onclick="editComment({{ $comment->id }}, '{{ $comment->content }}')"
                                                >
                                                    Edit
                                                </button>

                                                <!-- Delete Comment Button -->
                                                <button
                                                    class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600 focus:ring focus:ring-red-300"
                                                    onclick="deleteComment({{ $comment->id }})"
                                                >
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @endforeach
                                </div>

                                <!-- Modal for Editing Comments -->
                                <div id="edit-comment-modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg p-6 shadow-lg w-1/3">
                                        <h4 class="text-xl font-bold mb-4 text-gray-800">Edit Comment</h4>
                                        <form id="edit-comment-form">
                                            @csrf
                                            <textarea 
                                                id="edit-comment-content" 
                                                class="border rounded p-2 w-full focus:ring focus:ring-blue-300 focus:outline-none" 
                                                placeholder="Edit your comment here..." 
                                                required
                                            ></textarea>
                                            <input type="hidden" id="edit-comment-id">
                                            <div class="mt-4 flex justify-end">
                                                <button 
                                                    type="button" 
                                                    class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600 focus:ring focus:ring-gray-300"
                                                    onclick="closeEditModal()"
                                                >
                                                    Cancel
                                                </button>
                                                <button 
                                                    type="submit" 
                                                    class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 focus:ring focus:ring-blue-300"
                                                >
                                                    Save
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                @auth
                                <!-- Favourite Button -->
                                <button 
                                    onclick="toggleFavourite({{ $surfSpot->id }})"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded mt-2 focus:outline-none focus:ring focus:ring-yellow-300"
                                    aria-label="{{ Auth::user()->favouriteSurfSpots->contains($surfSpot->id) ? 'Remove from favourites' : 'Add to favourites' }} surf spot {{ $surfSpot->name }}"
                                >
                                    @if (Auth::user()->favouriteSurfSpots->contains($surfSpot->id))
                                        Unfavourite
                                    @else
                                        Favourite
                                    @endif
                                </button>
                                @endauth
                            </article>
                        @endforeach
                    </div>

                    <!-- Surf Spots Pagination -->
                    <nav class="mt-4" aria-label="Surf spots pagination">
                        {{ $allSurfSpots->appends(['user_page' => $userPage])->links() }}
                    </nav>

                    <!-- Add New Surf Spot Form -->
                    @if (Auth::user()->role === 'admin')
                    <section>
                        @if ($errors->any())
                            <div class="text-red-500 font-bold mb-4">
                                Failed to add surf spot. Please try again.
                            </div>
                        @endif
                        <h3 class="text-lg font-semibold mt-6">Add New Surf Spot</h3>
                        <form id="surf-spot-form" enctype="multipart/form-data" aria-label="Add a new surf spot">
                            @csrf
                            <div class="grid gap-2">
                                <label for="new-surfspot-name">Name</label>
                                <input 
                                    type="text" 
                                    id="new-surfspot-name"
                                    name="name" 
                                    placeholder="Name" 
                                    class="border p-2 rounded w-full" 
                                    required
                                />
                                <label for="new-surfspot-location">Location</label>
                                <input 
                                    type="text" 
                                    id="new-surfspot-location"
                                    name="location" 
                                    placeholder="Location" 
                                    class="border p-2 rounded w-full" 
                                    required
                                />
                                <label for="new-surfspot-description">Description</label>
                                <textarea 
                                    id="new-surfspot-description"
                                    name="description" 
                                    placeholder="Description" 
                                    class="border p-2 rounded w-full" 
                                    required
                                ></textarea>
                                <label for="new-surfspot-difficulty">Difficulty</label>
                                <select 
                                    id="new-surfspot-difficulty"
                                    name="difficulty" 
                                    class="border p-2 rounded w-full"
                                    required
                                >
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                                <button 
                                    type="submit" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded mt-2 focus:outline-none focus:ring focus:ring-blue-300"
                                    onclick="addSurfSpot(event)"
                                    aria-label="Add new surf spot"
                                >
                                    Add Surf Spot
                                </button>
                            </div>
                        </form>
                    </section>
                @endif





            </div>

            <!-- Right Column: Notifications -->
            <div class="lg:w-1/3 mt-8 lg:mt-0 lg:ml-6" aria-labelledby="notifications-title">
                <h3 id="notifications-title" class="text-lg font-semibold">Notifications</h3>
                <ul id="notifications-list" class="mt-4" role="list">
                    @foreach ($notifications as $notification)
                        <li class="border-b border-gray-200 py-2 flex justify-between items-center" role="listitem">
                            <div>
                                <p>{{ $notification->data['message'] }}</p>
                                <span class="text-gray-500 text-sm" role="note">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <button 
                                class="ml-4 bg-blue-500 text-white px-2 py-1 rounded focus:outline-none focus:ring focus:ring-blue-300" 
                                onclick="markNotificationAsRead('{{ $notification->id }}')"
                                aria-label="Mark notification as read: {{ $notification->data['message'] }}"
                            >
                                Mark as Read
                            </button>
                        </li>
                    @endforeach
                </ul>



                <!-- Add Notification (Admin-Only) -->
                @if (Auth::user()->role === 'admin')
                <h3 id="add-notification-title" class="text-lg font-semibold mt-6">Post Notification</h3>
                <form id="notification-form" aria-labelledby="add-notification-title">
                    @csrf
                    <div class="mb-4">
                        <label for="notification-message" class="block text-sm font-medium text-gray-700">
                            Write your notification:
                        </label>
                        <textarea 
                            id="notification-message" 
                            name="message" 
                            rows="3" 
                            class="border w-full p-2 rounded focus:outline-none focus:ring focus:ring-green-300" 
                            placeholder="Write your notification here" 
                            aria-required="true"
                            required
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="bg-green-500 text-white px-4 py-2 rounded focus:outline-none focus:ring focus:ring-green-300"
                        onclick="addNotification(event)"
                        aria-label="Submit new notification"
                    >
                        Post Notification
                    </button>
                </form>
            @endif

            </div>


        <!-- User Table -->
        @if (Auth::user()->role === 'admin')
            <div class="mt-8" aria-labelledby="manage-users-title">
            <h3 id="manage-users-title" class="text-lg font-semibold">Manage Users</h3>
            <table class="w-full border-collapse mt-4" aria-label="List of users">
                <thead>
                    <tr>
                        <th scope="col" class="border px-4 py-2">Name</th>
                        <th scope="col" class="border px-4 py-2">Email</th>
                        <th scope="col" class="border px-4 py-2">Role</th>
                        <th scope="col" class="border px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr tabindex="0">
                            <td class="border px-4 py-2">
                                <button 
                                    class="text-blue-500 hover:underline focus:outline-none focus:ring focus:ring-blue-300" 
                                    onclick="fetchUserDetails({{ $user->id }})"
                                    aria-label="Fetch details for {{ $user->name }}"
                                >
                                    {{ $user->name }}
                                </button>
                            </td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2">{{ $user->role }}</td>
                            <td class="border px-4 py-2">
                                <form action="{{ route('admin.delete.user', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        class="text-red-500 hover:underline focus:outline-none focus:ring focus:ring-red-300"
                                        aria-label="Delete user {{ $user->name }}"
                                    >
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <nav class="mt-4" aria-label="Users pagination">
                {{ $users->appends(request()->except('surf_page'))->links() }}
            </nav>
        </div>

        @endif

        <!-- User Details Section -->
        <div id="user-details" class="hidden mt-8 p-6 bg-gray-100 border border-gray-300 rounded" aria-labelledby="user-details-title" aria-hidden="true">
            <h3 id="user-details-title" class="text-lg font-semibold">User Details</h3>
            <p><strong>Name:</strong> <span id="details-name"></span></p>
            <p><strong>Email:</strong> <span id="details-email"></span></p>

            <!-- User Comments -->
            <h4 class="mt-4 text-lg font-semibold">Comments</h4>
            <ul id="details-comments" role="list"></ul>

            <!-- Surf Spots Created -->
            <h4 class="mt-4 text-lg font-semibold">Surf Spots</h4>
            <ul id="details-surf-spots" role="list"></ul>

            <!-- Favourited Surf Spots -->
            <h4 class="mt-4 text-lg font-semibold">Favourited Surf Spots</h4>
            <ul id="details-favourites" class="list-disc pl-5" role="list"></ul>

            <button 
                class="mt-4 bg-red-500 text-white px-4 py-2 rounded focus:outline-none focus:ring focus:ring-red-300"
                onclick="closeUserDetails()"
                aria-label="Close user details"
            >
                Close
            </button>
        </div>


    </div>

    <!-- JavaScript for User Details -->
    <script>
        function markNotificationAsRead(notificationId) {
            console.log("Notification ID:", notificationId);

            fetch(`/notifications/${notificationId}/mark-as-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to mark notification as read');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('success-message').textContent = data.message;
                    // Remove the notification from the UI
                    const notificationElement = document.querySelector(`button[onclick="markNotificationAsRead('${notificationId}')"]`).parentElement;
                    notificationElement.remove();
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    document.getElementById('error-message').textContent = 'Failed to mark notification as read. Please try again.';
                });
        }


        function fetchNotifications() {
            fetch('/notifications/fetch', {
                headers: {
                    'Accept': 'application/json',
                },
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched Notifications:', data.notifications);
                    const notificationsList = document.getElementById('notifications-list');
                    notificationsList.innerHTML = ''; 

                    if (data.notifications.length > 0) {
                        data.notifications.forEach(notification => {
                            const li = document.createElement('li');
                            li.setAttribute('role', 'listitem');
                            li.classList.add('border-b', 'border-gray-200', 'py-2', 'flex', 'justify-between', 'items-center');
                            li.innerHTML = `
                                <div>
                                    <p>${notification.message}</p>
                                    <span class="text-gray-500 text-sm">${notification.time}</span>
                                </div>
                                <button 
                                    class="ml-4 bg-blue-500 text-white px-2 py-1 rounded" 
                                    onclick="markNotificationAsRead('${notification.id}')"
                                >
                                    Mark as Read
                                </button>
                            `;
                            notificationsList.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.textContent = 'No new notifications';
                        li.setAttribute('tabindex', '0');
                        notificationsList.appendChild(li);
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    document.getElementById('error-message').textContent = 'Error fetching notifications. Please try again.';
                });
        }

        // Call fetchNotifications initially and set it to refresh every 30 seconds
        fetchNotifications();
        setInterval(fetchNotifications, 30000);


        function closeUserDetails() {
            document.getElementById('user-details').classList.add('hidden');
        }


        
        // Function to post a new comment
        function postComment(event, surfSpotId) {
            event.preventDefault(); 

            const form = document.getElementById(`comment-form-${surfSpotId}`);
            const formData = new FormData(form);

            // Clear previous messages
            document.getElementById('success-message').textContent = '';
            document.getElementById('error-message').textContent = '';

            fetch(`/dashboard/surf-spots/${surfSpotId}/comments`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to post comment');
                    }
                    return response.json();
                })
                .then(data => {
                    // Create a new comment element with correct styling
                    const commentList = document.querySelector(`#comments-list-${surfSpotId}`);
                    const newComment = document.createElement('div');
                    newComment.classList.add('comment', 'border', 'p-3', 'rounded', 'mb-3');
                    newComment.innerHTML = `
                        <p>
                            <strong>${data.user.name}</strong>: ${data.content}
                        </p>
                    `;
                    newComment.setAttribute('aria-label', `Comment by ${data.user.name}: ${data.content}`);
                    
                    // Show the new comment at the top of the list
                    commentList.prepend(newComment);

                    // Clear the comment form
                    form.reset();

                    // Display a success message
                    document.getElementById('success-message').textContent = 'Comment posted successfully!';
                })
                .catch(error => {
                    console.error('Error posting comment:', error);

                    // Display an error message
                    document.getElementById('error-message').textContent = 'Failed to post comment. Please try again.';
                });
        }

        // Function to add a new notification
        function addNotification(event) {
            event.preventDefault(); 

            const form = document.querySelector('#notification-form');
            const formData = new FormData(form);

            fetch('/admin/notifications/store', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add notification');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('success-message').textContent = 'Notification posted successfully!';
                    form.reset(); 
                    fetchNotifications(); 
                })
                .catch(error => {
                    console.error('Error adding notification:', error);
                    document.getElementById('error-message').textContent = 'Failed to post notification. Please try again.';
                });
        }


        function toggleEditForm(id) {
            const form = document.getElementById(`edit-form-${id}`);
            form.classList.toggle('hidden');
        }

        function editSurfSpot(event, surfSpotId) {
            event.preventDefault();

            // Select the correct form by ID
            const form = document.getElementById(`edit-surfspot-form-${surfSpotId}`);

            // Ensure the form exists before proceeding
            if (!form) {
                console.error(`Form with ID edit-surfspot-form-${surfSpotId} not found.`);
                return;
            }

            const formData = new FormData(form);

            const jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            fetch(`/dashboard/surf-spots/${surfSpotId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(jsonData), 
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); 
            })
            .then(data => {
                alert('Surf spot updated successfully!');
                location.reload(); 
            })
            .catch(error => {
                // Handle errors
                console.error('Error updating surf spot:', error);
                alert('Failed to update surf spot. Please try again.');
            });
        }

        

        function toggleFavourite(surfSpotId) {
        fetch(`/dashboard/surf-spots/${surfSpotId}/favourite`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to toggle favourite');
                }
                return response.json();
            })
            .then(data => {
                alert(data.message);

                fetchNotifications();

                const favouriteButton = document.querySelector(`#favourite-button-${surfSpotId}`);
                if (favouriteButton) {
                    favouriteButton.textContent = data.message.includes('removed') ? 'Favourite' : 'Unfavourite';
                }
            })
            .catch(error => {
                console.error('Error toggling favourite:', error);
                alert('An error occurred while toggling favourite.');
            });
    }


        function fetchUserDetails(userId) {
            fetch(`/users/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                // User details
                document.getElementById('details-name').textContent = data.name;
                document.getElementById('details-email').textContent = data.email;

                // Comments
                const commentsList = document.getElementById('details-comments');
                commentsList.innerHTML = '';
                data.comments.forEach(comment => {
                    const li = document.createElement('li');
                    li.textContent = `${comment.surf_spot}: "${comment.content}"`;
                    commentsList.appendChild(li);
                });

                // Surf Spots Created
                const surfSpotsList = document.getElementById('details-surf-spots');
                surfSpotsList.innerHTML = '';
                data.surf_spots.forEach(spot => {
                    const li = document.createElement('li');
                    li.textContent = `${spot.name} (${spot.location}) - ${spot.view_count} views, ${spot.likes} likes`;
                    surfSpotsList.appendChild(li);
                });

                // Favourited Surf Spots
                const favouritesList = document.getElementById('details-favourites');
                favouritesList.innerHTML = '';
                data.favourite_surf_spots.forEach(favourite => {
                    const li = document.createElement('li');
                    li.textContent = `${favourite.name} (${favourite.location}) - ${favourite.likes} likes`;
                    favouritesList.appendChild(li);
                });

                // Show the user details section
                document.getElementById('user-details').classList.remove('hidden');
            })
            .catch(error => console.error('Error fetching user details:', error));
        }

        // Function to add a new surf spot
        function addSurfSpot(event) {
            event.preventDefault(); 

            const form = document.querySelector('#surf-spot-form');
            const formData = new FormData(form);

            fetch('/dashboard/surf-spots', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 
                },
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to add surf spot');
                    }
                    return response.json();
                })
                .then(data => {
                    document.getElementById('success-message').textContent = 'Surf spot added successfully!';
                    location.reload(); 
                })
                .catch(error => {
                    console.error('Error adding surf spot:', error);
                    document.getElementById('error-message').textContent = 'Failed to add surf spot. Please try again.';
                });
        }



        // Open the modal for editing a comment
        function editComment(commentId, content) {
            document.getElementById('edit-comment-id').value = commentId;
            document.getElementById('edit-comment-content').value = content;
            document.getElementById('edit-comment-modal').classList.remove('hidden');
        }

        // Close the edit modal
        function closeEditModal() {
            document.getElementById('edit-comment-modal').classList.add('hidden');
        }

        // Show loading spinner
        function showLoading(message = "Processing...") {
            const loadingOverlay = document.createElement('div');
            loadingOverlay.id = 'loading-overlay';
            loadingOverlay.innerHTML = `
                <div class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white p-4 rounded shadow-lg flex items-center gap-2">
                        <div class="loader border-4 border-blue-500 border-t-transparent rounded-full w-6 h-6 animate-spin"></div>
                        <span>${message}</span>
                    </div>
                </div>
            `;
            document.body.appendChild(loadingOverlay);
        }

        // Hide loading spinner
        function hideLoading() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                loadingOverlay.remove();
            }
        }

        // Show toast notification
        function showToast(message, success = true) {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 bg-${success ? 'green' : 'red'}-500 text-white p-3 rounded shadow-lg z-50`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        // Submit the edited comment
        document.getElementById('edit-comment-form').addEventListener('submit', function (event) {
            event.preventDefault();

            const commentId = document.getElementById('edit-comment-id').value;
            const content = document.getElementById('edit-comment-content').value;

            showLoading('Saving changes...');
            fetch(`/comments/${commentId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ content }),
            })
                .then((response) => response.json())
                .then((data) => {
                    hideLoading();
                    if (data.success) {
                        document.querySelector(`#comment-${commentId} .comment-content`).textContent = content;
                        showToast('Comment updated successfully!');
                        closeEditModal();
                    } else {
                        showToast(data.message || 'Failed to update the comment.', false);
                    }
                })
                .catch((error) => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('An error occurred.', false);
                });
        });

        // Delete a comment
        function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) return;

            showLoading('Deleting comment...');
            fetch(`/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    hideLoading();
                    if (data.success) {
                        document.getElementById(`comment-${commentId}`).remove();
                        showToast('Comment deleted successfully!');
                    } else {
                        showToast(data.message || 'Failed to delete the comment.', false);
                    }
                })
                .catch((error) => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('An error occurred.', false);
                });
        }

    </script>
</x-app-layout>
