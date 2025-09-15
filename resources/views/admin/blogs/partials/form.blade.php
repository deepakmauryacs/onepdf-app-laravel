<form action="{{ $action }}" method="POST" class="needs-validation" novalidate>
  @csrf
  @isset($method)
    @if (strtoupper($method) !== 'POST')
      @method($method)
    @endif
  @endisset

  <div class="mb-4">
    <label for="title" class="form-label">Title</label>
    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
    @error('title')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-4">
    <label for="slug" class="form-label">Slug <span class="form-text">(optional)</span></label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $post->slug) }}" placeholder="auto-generated from title">
    @error('slug')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-4">
    <label for="excerpt" class="form-label">Excerpt <span class="form-text">(short summary for listings)</span></label>
    <textarea class="form-control @error('excerpt') is-invalid @enderror" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
    @error('excerpt')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="mb-4">
    <label for="content" class="form-label">Content</label>
    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="12" required>{{ old('content', $post->content) }}</textarea>
    @error('content')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>

  <div class="form-check form-switch mb-4">
    <input class="form-check-input" type="checkbox" role="switch" id="is_published" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_published">Publish immediately</label>
  </div>

  <div class="d-flex justify-content-between align-items-center">
    <a href="{{ route('admin.blogs.index') }}" class="btn btn-outline-secondary">Cancel</a>
    <button type="submit" class="btn btn-dark">{{ $submitLabel }}</button>
  </div>
</form>
