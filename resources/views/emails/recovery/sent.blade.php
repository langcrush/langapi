
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME', 'Application') }}</title>
</head>
<body>
    <h1>Hello {{ $user->name }}, it looks like you forgor your password!</h1>

    <p>Click the link below to change the current password of your account.</p>

    <a href="{{ $url }}">Recover</a>

    <p>If you didn't request the password change, please ignore this message.</p>

    <p>Good luck learning!<br/>
    {{ env('APP_NAME', 'Application') }} Team</p>
</body>
</html>
