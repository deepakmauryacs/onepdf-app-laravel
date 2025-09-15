@extends('admin.layouts.app')

@section('title', 'Create Blog Post')

@include('admin.blogs.partials.styles')

@section('content')
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <nav class="crumb">
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <a href="{{ route('admin.blogs.index') }}" class="text-decoration-none">Blog</a>
        <i class="bi bi-chevron-right"></i>
        <span>New post</span>
      </nav>
    </div>
  </div>
</div>

<div class="container py-3">
  @if ($errors->any())
    <div class="alert-error mb-3">
      <div class="fw-semibold mb-1">Please fix the following:</div>
      <ul class="mb-0 ps-3">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">Create blog post</h5>
    </div>
    <div class="card-body">
      @include('admin.blogs.partials.form', [
        'action' => route('admin.blogs.store'),
        'method' => 'POST',
        'post' => $post,
        'submitLabel' => 'Create post',
      ])
    </div>
  </div>
</div>
@endsection
