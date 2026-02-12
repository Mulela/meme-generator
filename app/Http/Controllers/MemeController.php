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
        // Validation hors try/catch (422 auto si invalide)
        $validated = $request->validate([
            'meme_file' => 'required|file|mimes:png|max:5120', // 5MB
            'top_text' => 'nullable|string|max:100',
            'bottom_text' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            $file = $validated['meme_file'];

            // Nom unique (simple et efficace)
            $filename = (string) Str::uuid() . '.png';
            $path = 'memes/' . $filename;

            // Stockage sur disk "public" => storage/app/public/...
            Storage::disk('public')->putFileAs('memes', $file, $filename);

            $meme = Meme::create([
                'image_path' => $path,
                'top_text' => $validated['top_text'] ?? null,
                'bottom_text' => $validated['bottom_text'] ?? null,
            ]);

            DB::commit();

            // Si ça vient du fetch()
            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Mème enregistré !',
                    'id' => $meme->id,
                    'redirect' => route('gallery'),
                ], 201);
            }

            return redirect()->route('gallery')->with('success', 'Mème enregistré !');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Store meme failed', ['error' => $e->getMessage()]);

            // Nettoyage simple si fichier partiellement créé
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
                    'message' => 'Erreur serveur lors de l’enregistrement.',
                    'details' => $e->getMessage(),
                ], 500);
            }

            return back()->withErrors([
                'meme_file' => 'Erreur serveur lors de l’enregistrement. Vérifie les logs.',
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

    public function destroy(Request $request, Meme $meme)
    {
        try {
            DB::beginTransaction();

            // On supprime d’abord le fichier si présent
            if ($meme->image_path && Storage::disk('public')->exists($meme->image_path)) {
                Storage::disk('public')->delete($meme->image_path);
            }

            $meme->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => true,
                    'message' => 'Mème supprimé.',
                ]);
            }

            return redirect()->route('gallery')->with('success', 'Mème supprimé.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Delete meme failed', ['error' => $e->getMessage()]);

            if ($request->expectsJson()) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Erreur serveur lors de la suppression.',
                    'details' => $e->getMessage(),
                ], 500);
            }

            return redirect()->route('gallery')->with('error', 'Erreur serveur lors de la suppression.');
        }
    }
}
