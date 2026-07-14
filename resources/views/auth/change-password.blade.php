<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
</head>
<body>
    <h1>Test: Change Password Page</h1>
    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PATCH')
        <input type="password" name="password" required>
        <input type="password" name="password_confirmation" required>
        <button type="submit">Update Password</button>
    </form>
</body>
</html>