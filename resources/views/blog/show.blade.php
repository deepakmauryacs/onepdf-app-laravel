@extends('layouts.app')

@section('title', $post->title . ' - OneLinkPDF Blog')

@include('blog.partials.styles')

@section('content')
<section class="blog-hero">
  <div class="container">
    <div class="row g-4">
      <div class="col-xl-9">
        <nav class="blog-breadcrumb mb-3" aria-label="Breadcrumb">
          <a href="{{ route('home') }}">Home</a>
          <i class="bi bi-chevron-right"></i>
          <a href="{{ route('blog.index') }}">Blog</a>
          <i class="bi bi-chevron-right"></i>
          <span aria-current="page">{{ $post->title }}</span>
        </nav>
        <span class="badge mb-3">Article</span>
        <h1 class="display-5 fw-bold mb-3">{{ $post->title }}</h1>
        <div class="blog-meta">
          <span><i class="bi bi-calendar3 me-1"></i>{{ $post->published_at?->format('F j, Y') ?? $post->created_at?->format('F j, Y') }}</span>
          @if ($post->updated_at && ($post->published_at ? $post->updated_at->gt($post->published_at) : $post->updated_at->gt($post->created_at)))
            <span class="dot"></span>
            <span>Updated {{ $post->updated_at->diffForHumans() }}</span>
          @endif
        </div>
        @if ($post->excerpt)
          <p class="lead mt-3">{{ $post->excerpt }}</p>
        @endif
      </div>
    </div>
  </div>
</section>

<section class="blog-section">
  <div class="container">
    <div class="row g-5">
      <div class="col-lg-8">
        <article class="blog-content">
          {!! nl2br(e($post->content)) !!}
        </article>
        <a href="{{ route('blog.index') }}" class="blog-back"><i class="bi bi-arrow-left"></i> Back to blog</a>
      </div>
      <div class="col-lg-4">
        @if ($relatedPosts->count())
          <div class="blog-related">
            <h5>More stories</h5>
            @foreach ($relatedPosts as $related)
              <article class="blog-card mb-3">
                <div class="blog-card__meta">
                  <i class="bi bi-calendar3"></i>
                  <span>{{ $related->published_at?->format('M j, Y') ?? $related->created_at?->format('M j, Y') }}</span>
                </div>
                <h3 class="blog-card__title">
                  <a href="{{ route('blog.show', $related->slug) }}">{{ $related->title }}</a>
                </h3>
                <p class="blog-card__excerpt">{{ $related->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($related->content), 110) }}</p>
                <div class="blog-card__footer">
                  Read more <i class="bi bi-arrow-right"></i>
                </div>
              </article>
            @endforeach
          </div>
        @endif
      </div>
    </div>
  </div>
</section>
@endsection
