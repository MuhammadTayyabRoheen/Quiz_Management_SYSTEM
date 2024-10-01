<!DOCTYPE html>
<html>
<head>
    <title>Password Setup</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>Please click the link below to set up your password:</p>
    <p><a href="{{ $resetUrl }}">Set up your password</a></p>
    <p>This link is valid for 24 hours.</p>
</body>
</html>
