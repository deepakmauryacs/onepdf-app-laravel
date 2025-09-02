@extends('vendor.layouts.app')

@section('title', 'Profile')

@section('content')
  <div class="bw-band">
    <ol class="bw-crumb">
      <li><a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i>Home</a></li>
      <li>Profile</li>
    </ol>
  </div>

  <div class="container py-4">
    <div class="row mb-4 align-items-center">
      <div class="col-auto">
        <div class="avatar-circle">{{ \Illuminate\Support\Str::of(auth()->user()->first_name)->substr(0,1) }}{{ \Illuminate\Support\Str::of(auth()->user()->last_name)->substr(0,1) }}</div>
      </div>
      <div class="col">
        <h2 class="mb-0">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</h2>
        <div class="text-muted">{{ auth()->user()->country }}</div>
      </div>
    </div>

    @if (session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}">
      @csrf
      @method('PUT')

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">First Name</label>
          <input type="text" name="first_name" class="form-control" value="{{ old('first_name', auth()->user()->first_name) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Last Name</label>
          <input type="text" name="last_name" class="form-control" value="{{ old('last_name', auth()->user()->last_name) }}">
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label class="form-label">Company</label>
          <input type="text" name="company" class="form-control" value="{{ old('company', auth()->user()->company) }}">
        </div>
        <div class="col-md-6">
          <label class="form-label">Country</label>
          <input type="text" name="country" class="form-control" value="{{ old('country', auth()->user()->country) }}">
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="reset" class="btn btn-secondary">Reset</button>
      </div>
    </form>
  </div>
@endsection
