<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SecureChatController extends Controller
{
    public function index(): View
    {
        return view('shared.chat', [
            'messages' => Chat::query()->with('user')->latest()->limit(100)->get()->reverse()->values(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pesan' => ['nullable', 'string', 'max:1000'],
            'media' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,zip,jpg,jpeg,png,webp,gif,mp4,mov,mp3,wav,m4a'],
        ]);

        if ($request->filled('pesan') === false && ! $request->hasFile('media')) {
            return back()->withErrors(['pesan' => 'Pesan atau lampiran wajib diisi.']);
        }

        $mediaPath = null;
        $mediaType = null;

        if ($request->hasFile('media')) {
            $file = $validated['media'];
            $mediaPath = Storage::disk('local')->putFile('chat', $file);
            $mime = $file->getMimeType();
            $mediaType = str_starts_with($mime, 'image/') ? 'image'
                : (str_starts_with($mime, 'video/') ? 'video'
                    : (str_starts_with($mime, 'audio/') ? 'audio' : 'document'));
        }

        Chat::create([
            'user_id' => $request->user()->id,
            'pesan' => $validated['pesan'] ?? null,
            'media_path' => $mediaPath,
            'media_type' => $mediaType,
        ]);

        return back();
    }

    public function media(Chat $chat): StreamedResponse
    {
        abort_unless($chat->media_path, 404);

        $disk = Storage::disk('local')->exists($chat->media_path) ? 'local' : 'public';
        abort_unless(Storage::disk($disk)->exists($chat->media_path), 404);

        return Storage::disk($disk)->response($chat->media_path);
    }
}
