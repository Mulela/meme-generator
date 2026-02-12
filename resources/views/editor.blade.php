@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Créer un mème</h1>
        <p class="mt-1 text-sm text-zinc-400">
            Télécharge une image, ajoute du texte, prévisualise en direct, puis télécharge ou enregistre dans la galerie.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <form id="memeForm" method="POST" action="{{ route('memes.store') }}" enctype="multipart/form-data" class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 shadow-sm">
            @csrf

            <h2 class="text-sm font-medium text-zinc-200">Contrôles</h2>

            @if ($errors->any())
            <div class="mt-4 rounded-xl border border-red-900/40 bg-red-950/30 p-3 text-sm text-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mt-4 space-y-4">
                <div>
                    <label class="text-xs text-zinc-400">Image</label>

                    <div id="dropZone" class="mt-2 rounded-xl border border-dashed border-zinc-800 bg-zinc-900/20 p-4 hover:bg-zinc-900/30 transition cursor-pointer">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-zinc-200">Cliquer pour choisir ou glisser-déposer</p>
                                <p class="mt-1 text-xs text-zinc-500">PNG/JPG · max 5MB</p>
                            </div>
                            <button type="button" id="pickImageBtn" class="rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800">
                                Choisir
                            </button>
                        </div>
                    </div>

                    <input id="imageInput" type="file" accept="image/png,image/jpeg" class="hidden" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Texte du haut</label>
                        <input id="topText" name="top_text" type="text" maxlength="100" placeholder="TEXTE EN HAUT" class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Texte du bas</label>
                        <input id="bottomText" name="bottom_text" type="text" maxlength="100" placeholder="TEXTE EN BAS" class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Taille du texte</label>
                        <input id="textSize" type="range" min="20" max="80" value="48" class="mt-3 w-full" />
                        <p class="mt-1 text-xs text-zinc-500"><span id="textSizeLabel">48</span> px</p>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-zinc-800 bg-zinc-900/30 px-3 py-2">
                        <div>
                            <p class="text-xs text-zinc-400">Contour</p>
                            <p class="text-sm text-zinc-200"><span id="outlineLabel">Activé</span></p>
                        </div>
                        <button type="button" id="toggleOutline" class="rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800">
                            Basculer
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="button" id="downloadBtn" class="rounded-xl bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-white disabled:cursor-not-allowed disabled:opacity-50" disabled>
                        Télécharger
                    </button>

                    <button type="button" id="saveBtn" class="rounded-xl border border-zinc-800 px-4 py-2 text-sm text-zinc-200 hover:bg-zinc-900 disabled:cursor-not-allowed disabled:opacity-50" disabled>
                        Enregistrer
                    </button>

                    <button type="button" id="resetBtn" class="ml-auto rounded-xl px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-900">
                        Réinitialiser
                    </button>
                </div>

                <div id="statusBox" class="rounded-xl border border-zinc-800 bg-zinc-900/20 p-3 text-xs text-zinc-400">
                    Sélectionne une image pour activer l’aperçu.
                </div>
            </div>
        </form>

        <section class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-medium text-zinc-200">Aperçu en direct</h2>
                <span class="text-xs text-zinc-500">Canvas</span>
            </div>

            <div class="mt-4 rounded-xl border border-zinc-800 bg-zinc-900/20 p-3">
                <canvas id="memeCanvas" class="w-full rounded-lg bg-black"></canvas>
            </div>

            <p class="mt-2 text-xs text-zinc-500">
                Police : Impact (fallback Arial Black / Arial).
            </p>
        </section>
    </div>
</div>
@endsection
