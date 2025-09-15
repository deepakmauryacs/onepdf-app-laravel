@extends('layouts.app')

@section('title', 'Blog - OneLinkPDF')

@include('blog.partials.styles')

@section('content')
<section class="blog-hero">
  <div class="container">
    <div class="row align-items-center g-4">
      <div class="col-lg-8">
        <span class="badge mb-3">OneLinkPDF Blog</span>
        <h1 class="display-5 fw-bold mb-3">Insights on secure document sharing & analytics</h1>
        <p class="lead">Product updates, best practices, and stories about making documents safer to share.</p>
      </div>
    </div>
  </div>
</section>

<section class="blog-section">
  <div class="container">
    @if ($posts->count())
      <div class="row g-4">
        @foreach ($posts as $post)
          <div class="col-md-6 col-lg-4">
            <article class="blog-card">
              @if ($post->featured_image_url)
                <figure class="blog-card__media">
                  <img src="{{ $post->featured_image_url }}" alt="Featured image for {{ $post->title }}">
                </figure>
              @endif
              <div class="blog-card__meta">
                <i class="bi bi-calendar3"></i>
                <span>{{ $post->published_at?->format('M j, Y') ?? $post->created_at?->format('M j, Y') }}</span>
              </div>
              <h3 class="blog-card__title">
                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
              </h3>
              <p class="blog-card__excerpt">
                {{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}
              </p>
              <div class="blog-card__footer">
                Read more <i class="bi bi-arrow-right"></i>
              </div>
            </article>
          </div>
        @endforeach
      </div>

      <div class="blog-pagination">
        <div class="pager-summary">Showing {{ $posts->firstItem() }}–{{ $posts->lastItem() }} of {{ $posts->total() }} posts</div>
        {{ $posts->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
    @else
      <div class="blog-empty">
        <p>No blog posts yet. Check back soon for the latest updates.</p>
      </div>
    @endif
  </div>
</section>
@endsection
