<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

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
                'url'      => $d->public_url,
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

        // ✅ capture size BEFORE move (temp file still exists)
        $size  = (int) $file->getSize();

        $dirAbs = public_path('uploads/'.$useId);
        if (!is_dir($dirAbs)) {
            mkdir($dirAbs, 0777, true);
        }

        do {
            $stored = \Illuminate\Support\Str::random(16).($ext ? ".{$ext}" : '');
            $abs    = $dirAbs.DIRECTORY_SEPARATOR.$stored;
        } while (file_exists($abs));

        // move returns a Symfony File object for the new path
        $moved = $file->move($dirAbs, $stored);
        @chmod($moved->getRealPath(), 0644);

        $relative = 'uploads/'.$useId.'/'.$stored;

        // optional: if you prefer, you can re-read size from the moved file:
        // $size = filesize($moved->getRealPath());

        $doc = \App\Models\Document::create([
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

    public function generateLink(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $doc = Document::where('id', $request->id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $doc->share_token       = Str::random(40);
        $doc->share_expires_at  = Carbon::now()->addDays(30); // change as needed
        $doc->save();

        return response()->json([
            'url' => route('vendor.files.public', $doc->share_token),
        ]);
    }

    // public access by token (simple inline view)
    public function public(string $token)
    {
        $doc = Document::where('share_token', $token)->firstOrFail();

        if ($doc->share_expires_at && now()->greaterThan($doc->share_expires_at)) {
            abort(410, 'Link expired');
        }

        $path = public_path($doc->filepath);
        abort_unless(is_file($path), 404);

        // inline open in browser
        return response()->file($path, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$doc->filename.'"',
        ]);
    }
}
