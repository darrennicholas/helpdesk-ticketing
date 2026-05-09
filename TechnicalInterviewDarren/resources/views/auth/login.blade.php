@extends('layouts.guest')
@section('content')
<div class="card">
    <div class="card-header">Login</div>
    <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required autofocus></div>
            <div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="{{ route('register') }}" class="btn btn-link">Register</a>
        </form>
    </div>
</div>
@endsection