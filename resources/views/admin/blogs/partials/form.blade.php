<form action="{{ $action }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
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
    <label for="featured_image" class="form-label">Featured image</label>
    <div class="featured-image-field">
      <div class="featured-image-preview mb-3" data-featured-image-preview {{ $post->featured_image_url ? '' : 'hidden' }}>
        @if ($post->featured_image_url)
          <img src="{{ $post->featured_image_url }}" alt="Preview of the featured image" class="featured-image-preview__image">
        @endif
      </div>
      <input type="file" class="form-control @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image" accept="image/png,image/jpeg">
      <div class="form-text">JPG or PNG up to 2MB. This image appears on listings and the article page.</div>
      @error('featured_image')
        <div class="invalid-feedback d-block">{{ $message }}</div>
      @enderror
    </div>
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

@push('scripts')
  @once
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
  @endonce
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.content) {
        CKEDITOR.instances.content.destroy(true);
      }

      if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('content', {
          height: 360,
          toolbarCanCollapse: true,
          removeButtons: 'PasteFromWord'
        });
      }

      const fileInput = document.getElementById('featured_image');
      const previewWrapper = document.querySelector('[data-featured-image-preview]');

      if (previewWrapper) {
        const existing = previewWrapper.querySelector('img');
        if (existing && !existing.dataset.originalSrc) {
          existing.dataset.originalSrc = existing.currentSrc || existing.src;
        }
      }

      if (fileInput && previewWrapper) {
        fileInput.addEventListener('change', function (event) {
          const [file] = event.target.files || [];

          if (!file) {
            previewWrapper.hidden = !previewWrapper.querySelector('img');
            if (!previewWrapper.hidden) {
              const existing = previewWrapper.querySelector('img');
              if (existing) {
                existing.src = existing.dataset.originalSrc || existing.src;
              }
            }
            return;
          }

          const reader = new FileReader();
          reader.onload = function (e) {
            let image = previewWrapper.querySelector('img');
            if (!image) {
              image = document.createElement('img');
              image.className = 'featured-image-preview__image';
              previewWrapper.appendChild(image);
            }

            if (!image.dataset.originalSrc) {
              image.dataset.originalSrc = image.currentSrc || image.src;
            }

            image.src = e.target?.result || '';
            previewWrapper.hidden = false;
          };
          reader.readAsDataURL(file);
        });
      }
    });
  </script>
@endpush
