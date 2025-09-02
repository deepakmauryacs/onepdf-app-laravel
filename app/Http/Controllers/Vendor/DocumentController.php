<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Link;
use App\Models\LinkAnalytics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class DocumentController extends Controller
{
    public function index()
    {
        return view('vendor.files.index');
    }

    public function list()
    {
        $user = Auth::user();
        $docs = Document::where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(fn($d) => [
                'id'       => $d->id,
                'filename' => $d->filename,
                'size'     => (int) $d->size,
                'modified' => optional($d->updated_at)->format('Y-m-d H:i'),
                'url'      => $d->public_url ?? null,
            ]);

        return response()->json(['files' => $docs]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required','file','mimetypes:application/pdf','max:51200'], // 50MB
        ],[
            'file.mimetypes' => 'Only PDF files are allowed',
            'file.max'       => 'File exceeds 50MB limit',
        ]);

        $user  = auth()->user();
        $useId = $user->use_id ?? $user->id;

        $file  = $request->file('file');

        $orig  = $file->getClientOriginalName();
        $safe  = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $orig);
        $ext   = strtolower($file->getClientOriginalExtension());

        // Capture size before move
        $size  = (int) $file->getSize();

        $dirAbs = public_path('uploads/'.$useId);
        if (!is_dir($dirAbs)) {
            mkdir($dirAbs, 0777, true);
        }

        do {
            $stored = Str::random(16) . ($ext ? ".{$ext}" : '');
            $abs    = $dirAbs.DIRECTORY_SEPARATOR.$stored;
        } while (file_exists($abs));

        $moved = $file->move($dirAbs, $stored);
        @chmod($moved->getRealPath(), 0644);

        $relative = 'uploads/'.$useId.'/'.$stored;

        $doc = Document::create([
            'user_id'  => $user->id,
            'filename' => $safe,
            'filepath' => $relative,
            'size'     => $size,
        ]);

        return response()->json(['success' => true, 'id' => $doc->id]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $doc = Document::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $full = public_path($doc->filepath);
        if (is_file($full)) @unlink($full);

        $doc->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Create or update a link (slug) for a document and return viewer URL (/view?doc=SLUG).
     * Accepts optional 'permissions' JSON in the request (download/print/analytics).
     */
    public function generateLink(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $doc = Document::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Parse permissions from request (optional)
        $permJson = (string) $request->input('permissions', '{}');
        $permArr  = json_decode($permJson, true);
        if (!is_array($permArr)) {
            $permArr = [];
        }

        // Find or create Link row
        $link = Link::firstOrNew(['document_id' => $doc->id]);

        if (!$link->exists) {
            $link->slug = bin2hex(random_bytes(5)); // 10-char hex
        }

        // If your Link model doesn't cast 'permissions' => 'array',
        // encode manually to ensure proper storage into JSON column.
        $link->permissions = json_encode($permArr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $link->created_at  = $link->created_at ?: now();
        $link->save();

        // Build pretty viewer URL: /view?doc=SLUG
        $url = URL::to('/view') . '?doc=' . $link->slug;

        // Optionally store the URL on the document for listing (only if column exists)
        if (Schema::hasColumn('documents', 'public_url')) {
            $doc->public_url = $url;
            $doc->save();
        }

        return response()->json(['url' => $url]);
    }

    /**
     * Pretty viewer route: /view?doc=SLUG
     * Shows Blade viewer and (optionally) logs a "view" event.
     */
    public function viewer(Request $request)
    {
        $slug = (string) $request->query('doc', '');

        $link = Link::where('slug', $slug)->firstOrFail();

        // permissions array (avoid shorthand ?:)
        $perms = [];
        if (is_array($link->permissions)) {
            $perms = $link->permissions;
        } else {
            $decoded = json_decode((string) $link->permissions, true);
            if (is_array($decoded)) {
                $perms = $decoded;
            }
        }

        // Optional analytics (only if table exists)
        if (!empty($perms['analytics']) && Schema::hasTable('link_analytics')) {
            try {
                LinkAnalytics::create([
                    'link_id'    => $link->id,
                    'event'      => 'view',
                    'meta'       => null,
                    'ip'         => $request->ip(),
                    'user_agent' => (string) $request->userAgent(),
                ]);
            } catch (\Throwable $e) {
                // swallow – analytics should never block viewing
            }
        }

        $pdfUrl      = route('public.pdf', ['code' => $slug]);
        $downloadUrl = route('public.pdf', ['code' => $slug, 'download' => 1]);

        return view('public.view', [
            'slug'        => $slug,
            'pdfUrl'      => $pdfUrl,
            'downloadUrl' => $downloadUrl,
            'perms'       => $perms,
        ]);
    }

    /**
     * Serve the actual PDF bytes by slug. /get-pdf?code=SLUG[&download=1]
     */
    public function streamBySlug(Request $request)
    {
        $slug = (string) $request->query('code', '');
        $download = (string) $request->query('download', '') === '1';

        $link = Link::where('slug', $slug)->firstOrFail();
        $doc  = Document::where('id', $link->document_id)->firstOrFail();

        $path = public_path($doc->filepath);
        abort_unless(is_file($path), 404);

        if ($download) {
            // (Optional) you could log a 'download' event here if analytics table exists
            if (Schema::hasTable('link_analytics')) {
                try {
                    LinkAnalytics::create([
                        'link_id'    => $link->id,
                        'event'      => 'download',
                        'meta'       => null,
                        'ip'         => $request->ip(),
                        'user_agent' => (string) $request->userAgent(),
                    ]);
                } catch (\Throwable $e) {}
            }

            return response()->download($path, $doc->filename, [
                'Content-Type' => 'application/pdf',
            ]);
        }

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->filename.'"',
        ]);
    }

    /**
     * Legacy public route (/s/{token}) if you still use Document.share_token.
     */
    public function public(string $token)
    {
        $doc = Document::where('share_token', $token)->firstOrFail();

        if ($doc->share_expires_at && now()->greaterThan($doc->share_expires_at)) {
            abort(410, 'Link expired');
        }

        $path = public_path($doc->filepath);
        abort_unless(is_file($path), 404);

        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->filename.'"',
        ]);
    }

    /**
     * Capture lightweight analytics events from the public viewer.
     * Expects: slug, event, page (optional), meta (optional JSON)
     */
    public function track(Request $request)
    {
        // If analytics table doesn't exist, just no-op
        if (!Schema::hasTable('link_analytics')) {
            return response()->noContent();
        }

        $request->validate([
            'slug'  => 'required|string',
            'event' => 'required|string|max:50',
            'page'  => 'nullable|integer|min:1',
            'meta'  => 'nullable',
        ]);

        $link = Link::where('slug', $request->slug)->first();
        if (!$link) {
            return response()->json(['error' => 'Invalid link'], 404);
        }

        $meta = [];
        if ($request->filled('page')) {
            $meta['page'] = (int) $request->page;
        }
        if ($request->filled('meta')) {
            $extra = json_decode($request->meta, true);
            if (is_array($extra)) {
                $meta = array_merge($meta, $extra);
            }
        }
        $meta['ref'] = $request->headers->get('referer');

        try {
            LinkAnalytics::create([
                'link_id'    => $link->id,
                'event'      => $request->event,
                'meta'       => $meta,
                'ip'         => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // ignore insert errors for analytics
        }

        return response()->noContent();
    }
}
