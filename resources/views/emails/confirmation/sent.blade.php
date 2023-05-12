<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME', 'Application') }}</title>
</head>
<body>
    <h1>Hello {{ $user->name }}, thank you for signing up in our application!</h1>

    <p>Click the link below to confirm your email and end the registration process.</p>

    <a href="{{ $url }}">Confirm</a><br/>

    <p>Good luck learning!<br/>
    {{ env('APP_NAME', 'Application') }} Team</p>
</body>
</html>
