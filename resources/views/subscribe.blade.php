<!DOCTYPE html>
<html>
<head>
    <title>Subscribe to Weather Alerts</title>
</head>
<body>
<h1>Subscribe to Weather Alerts</h1>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

<form action="{{ route('subscribe') }}" method="POST">
    @csrf
    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>City:</label><br>
    <input type="text" name="city" required><br><br>

    <button type="submit">Subscribe</button>
</form>
</body>
</html>
