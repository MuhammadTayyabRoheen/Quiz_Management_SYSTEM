<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password for {{ $user->name }}</h1>
    
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
        <input type="hidden" name="token" value="{{ $token }}">
        
        <label for="password">New Password</label>
        <input type="password" name="password" required>
        
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" required>
        
        <button type="submit">Reset Password</button>
    </form>
</body>
</html>
