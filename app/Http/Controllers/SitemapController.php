<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

class SitemapController extends Controller
{
    /**
     * Display the XML sitemap for public pages.
     */
    public function index(): Response
    {
        $routeNames = [
            'home',
            'features',
            'pricing',
            'how-it-works',
            'contact',
            'partnerships',
            'docs',
            'integrations',
            'blog.index',
            'terms',
            'privacy',
            'refund-policy',
        ];

        $urls = collect($routeNames)
            ->filter(fn ($name) => Route::has($name))
            ->map(function ($name) {
                try {
                    $loc = route($name);
                } catch (\InvalidArgumentException $e) {
                    return null;
                }

                return [
                    'loc' => $loc,
                    'lastmod' => now()->toAtomString(),
                    'changefreq' => $name === 'home' ? 'daily' : 'weekly',
                    'priority' => $name === 'home' ? '1.0' : '0.8',
                ];
            })
            ->filter()
            ->values();

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
