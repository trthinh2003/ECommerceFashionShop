<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container text-center">
        <h1 class="display-1">403</h1>
        <h2>Forbidden</h2>
        <p>Sorry, you don't have permission to access this page.</p>
        <a href="{{ url('/') }}" class="btn btn-primary">Go to Homepage</a>
    </div>
</body>
</html>
