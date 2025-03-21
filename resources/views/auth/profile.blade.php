<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
</head>
<body>
    <h1>User Profile</h1>

    <p><strong>Name:</strong> {{ Auth::user()->name }}</p>
    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>

    <!-- Form to update profile -->
    <h2>Update Profile</h2>
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="{{ Auth::user()->name }}" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="{{ Auth::user()->email }}" required><br><br>

        <button type="submit">Update Profile</button>
    </form>

    <!-- Form to change password -->
    <h2>Change Password</h2>
    <form action="{{ route('password.change') }}" method="POST">
        @csrf
        
        <label for="current_password">Current Password:</label>
        <input type="password" id="current_password" name="current_password" required><br><br>

        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>

        <label for="new_password_confirmation">Confirm New Password:</label>
        <input type="password" id="new_password_confirmation" name="new_password_confirmation" required><br><br>

        <button type="submit">Change Password</button>
    </form>
</body>
</html>
