<?php

namespace App\Http\Controllers;

use App\Models\Meme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // Validation hors try/catch (Laravel gère 422 automatiquement)
        $validated = $request->validate([
            'meme_file' => 'required|file|mimes:png|max:5120',
            'top_text' => 'nullable|string|max:100',
            'bottom_text' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $file = $validated['meme_file'];

            $filename = (string) Str::uuid() . '.png';
            $path = 'memes/' . $filename;

            Storage::disk('public')->putFileAs('memes', $file, $filename);

            $meme = Meme::create([
                'image_path' => $path,
                'top_text' => $validated['top_text'] ?? null,
                'bottom_text' => $validated['bottom_text'] ?? null,
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Meme created!',
                    'id' => $meme->id,
                    'redirect' => route('gallery'),
                ], 201);
            }

            return redirect()->route('gallery')->with('success', 'Meme created!');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Meme store failed', [
                'error' => $e->getMessage(),
            ]);

            if (isset($path) && Storage::disk('public')->exists($path)) {
                try {
                    Storage::disk('public')->delete($path);
                } catch (\Throwable $cleanupErr) {
                    Log::warning('Cleanup failed', ['error' => $cleanupErr->getMessage()]);
                }
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Server error while saving meme.',
                    'details' => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors([
                'meme_file' => 'Server error while saving meme. Check logs.',
            ])->withInput();
        }
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
