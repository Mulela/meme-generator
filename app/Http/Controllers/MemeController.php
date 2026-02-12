<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MemeController extends Controller
{
    public function editor()
    {
        return view('editor');
    }

    public function gallery()
    {
        $memes = Meme::latest()->paginate(12);
        return view('gallery', compact('memes'));
    }

    public function store(Request $request)
    {
        // DB smoke test (sans canvas pour l’instant)
        $validated = $request->validate([
            'top_text' => 'nullable|string|max:100',
            'bottom_text' => 'nullable|string|max:100',
        ]);

        // Placeholder file (vide) pour tester stockage + DB + galerie
        $path = 'memes/' . Str::uuid() . '.png';
        Storage::disk('public')->put($path, '');

        Meme::create([
            'image_path' => $path,
            'top_text' => $validated['top_text'] ?? null,
            'bottom_text' => $validated['bottom_text'] ?? null,
        ]);

        return redirect()->route('gallery')->with('success', 'Meme saved (placeholder).');
    }

    public function download(Meme $meme)
    {
        if (!Storage::disk('public')->exists($meme->image_path)) {
            abort(404);
        }

        return Storage::disk('public')->download(
            $meme->image_path,
            'meme-' . $meme->id . '.png'
        );
    }
}
