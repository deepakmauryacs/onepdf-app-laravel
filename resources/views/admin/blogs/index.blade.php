@extends('admin.layouts.app')

@section('title', 'Blog Posts')

@include('admin.blogs.partials.styles')

@section('content')
<div class="top-band">
  <div class="container py-3">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
      <nav class="crumb">
        <a href="{{ route('admin.dashboard') }}"><i class="bi bi-house-door me-1"></i> Home</a>
        <i class="bi bi-chevron-right"></i>
        <span>Blog</span>
      </nav>
      <a href="{{ route('admin.blogs.create') }}" class="btn btn-dark"><i class="bi bi-plus-lg me-1"></i> New post</a>
    </div>
  </div>
</div>

<div class="container py-3">
  @if (session('status'))
    <div class="alert-status mb-3">{{ session('status') }}</div>
  @endif

  <div class="card">
    <div class="card-header">
      <div class="files-toolbar">
        <div class="folder"><i class="bi bi-journal-text"></i> Blog posts <span class="count">{{ $posts->total() }}</span></div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Slug</th>
            <th>Status</th>
            <th>Published</th>
            <th>Updated</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($posts as $post)
            <tr>
              <td>
                <div class="fw-semibold">{{ $post->title }}</div>
                <div class="text-muted small">Created {{ $post->created_at?->format('M j, Y') }}</div>
              </td>
              <td class="text-muted">{{ $post->slug }}</td>
              <td>
                @if ($post->is_published)
                  <span class="status-badge status-live"><i class="bi bi-broadcast"></i> Published</span>
                @else
                  <span class="status-badge status-draft"><i class="bi bi-eye-slash"></i> Draft</span>
                @endif
              </td>
              <td>{{ $post->published_at?->format('M j, Y') ?? '—' }}</td>
              <td>{{ $post->updated_at?->diffForHumans() }}</td>
              <td class="text-end">
                <div class="actions">
                  <a href="{{ route('admin.blogs.edit', $post) }}" class="btn btn-sm btn-outline-dark">Edit</a>
                  <form action="{{ route('admin.blogs.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No posts yet. Create your first blog post.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <nav class="mt-4 pagination-wrap" aria-label="Blog posts pagination">
    @if ($posts->total())
      <div class="pager-summary">Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }}</div>
    @endif
    <div class="pagination-modern">
      {{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}
    </div>
  </nav>
</div>
@endsection
