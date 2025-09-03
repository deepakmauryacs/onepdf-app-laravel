<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Document;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    public function index()
    {
        $query = User::where('is_admin', false);
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users', 'search'));
    }

    public function files(User $user)
    {
        return view('admin.users.files', compact('user'));
    }

    public function filesList(User $user, Request $request)
    {
        $query = Document::where('user_id', $user->id);
        if ($search = $request->input('search')) {
            $query->where('filename', 'like', "%{$search}%");
        }

        $docs = $query->with('link')->latest()->paginate(10);
        $files = $docs->getCollection()->map(function ($d) {
            return [
                'id'         => $d->id,
                'filename'   => $d->filename,
                'size'       => (int) $d->size,
                'modified'   => optional($d->updated_at)->format('Y-m-d'),
                'time'       => optional($d->updated_at)->format('H:i'),
                'public_url' => $d->link ? URL::to('/view').'?doc='.$d->link->slug : null,
            ];
        });

        return response()->json([
            'files'        => $files,
            'current_page' => $docs->currentPage(),
            'last_page'    => $docs->lastPage(),
            'total'        => $docs->total(),
            'per_page'     => $docs->perPage(),
        ]);
    }

    public function generateLink(User $user, Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $doc = Document::where('id', $request->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $permJson = (string) $request->input('permissions', '{}');
        $permArr  = json_decode($permJson, true);
        if (!is_array($permArr)) {
            $permArr = [];
        }

        $link = Link::firstOrNew(['document_id' => $doc->id]);
        if (!$link->exists) {
            $link->slug    = bin2hex(random_bytes(5));
            $link->user_id = $user->id;
        }

        $link->permissions = json_encode($permArr, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $link->created_at  = $link->created_at ?: now();
        $link->save();

        $url = URL::to('/view') . '?doc=' . $link->slug;

        if (Schema::hasColumn('documents', 'public_url')) {
            $doc->public_url = $url;
            $doc->save();
        }

        return response()->json(['url' => $url]);
    }

}
