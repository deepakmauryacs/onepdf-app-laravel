<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = BlogPost::orderByDesc('created_at')->paginate(10);

        return view('admin.blogs.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $post = new BlogPost([
            'is_published' => true,
        ]);

        return view('admin.blogs.create', compact('post'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $post = BlogPost::create($data);

        return redirect()
            ->route('admin.blogs.edit', $post)
            ->with('status', 'Blog post created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blog): View
    {
        return view('admin.blogs.edit', ['post' => $blog]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $this->validatedData($request, $blog);

        $blog->update($data);

        return redirect()
            ->route('admin.blogs.edit', $blog)
            ->with('status', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blog): RedirectResponse
    {
        $this->deleteFeaturedImage($blog->featured_image_path);

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('status', 'Blog post deleted successfully.');
    }

    /**
     * Validate the request data and prepare it for persistence.
     */
    protected function validatedData(Request $request, ?BlogPost $blog = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'is_published' => ['nullable', 'boolean'],
            'featured_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $slugSource = $validated['slug'] ?? $validated['title'];
        $slug = Str::slug($slugSource);
        if ($slug === '') {
            $slug = Str::slug(Str::random(8));
        }
        $validated['slug'] = $this->ensureUniqueSlug($slug, $blog?->id);

        if ($validated['is_published']) {
            $validated['published_at'] = $blog && $blog->published_at
                ? $blog->published_at
                : now();
        } else {
            $validated['published_at'] = null;
        }

        if ($request->hasFile('featured_image')) {
            if ($blog) {
                $this->deleteFeaturedImage($blog->featured_image_path);
            }

            $disk = Storage::disk('public_assets');

            if (! $disk->exists('uploads/blogs')) {
                $disk->makeDirectory('uploads/blogs', 0755, true);
            }

            $validated['featured_image_path'] = $request->file('featured_image')
                ->store('uploads/blogs', 'public_assets');
        }

        unset($validated['featured_image']);

        return $validated;
    }

    /**
     * Remove the existing featured image from storage if it exists.
     */
    protected function deleteFeaturedImage(?string $path): void
    {
        if (! $path) {
            return;
        }

        $disk = Str::startsWith($path, ['uploads/', 'uploads\\'])
            ? 'public_assets'
            : 'public';

        Storage::disk($disk)->delete($path);
    }

    /**
     * Ensure the slug is unique in the database.
     */
    protected function ensureUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug;
        $counter = 2;

        while ($this->slugExists($slug, $ignoreId)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Determine if a slug already exists.
     */
    protected function slugExists(string $slug, ?int $ignoreId = null): bool
    {
        return BlogPost::where('slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();
    }
}
