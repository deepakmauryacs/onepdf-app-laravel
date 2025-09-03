@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="p-4">
  <h1 class="h3 mb-4">Admin Dashboard</h1>
  <p>Welcome, {{ auth()->user()->name }}.</p>
</div>
@endsection
