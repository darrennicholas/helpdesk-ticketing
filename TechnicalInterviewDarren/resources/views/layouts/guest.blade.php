<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                @if($errors->any())
                    <div class="alert alert-danger">@foreach($errors->all() as $error){{ $error }}<br>@endforeach</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>