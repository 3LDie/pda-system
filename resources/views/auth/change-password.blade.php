<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <style>
        .error { color: red; font-size: 0.8em; }
        .form-group { margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>Change Your Password</h1>

    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
        </div>

        <button type="submit">Update Password</button>
    </form>
</body>
</html>