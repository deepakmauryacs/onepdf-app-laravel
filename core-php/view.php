<?php
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Document;
use Illuminate\Support\Carbon;

$token = $_GET['doc'] ?? null;
if (!$token) {
    http_response_code(404);
    exit('Missing document token');
}

$doc = Document::where('share_token', $token)->first();
if (!$doc) {
    http_response_code(404);
    exit('Document not found');
}

if ($doc->share_expires_at && Carbon::now()->greaterThan($doc->share_expires_at)) {
    http_response_code(410);
    exit('Link expired');
}

$path = __DIR__.'/../public/'.$doc->filepath;
if (!is_file($path)) {
    http_response_code(404);
    exit('File missing');
}

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="'.$doc->filename.'"');
readfile($path);

