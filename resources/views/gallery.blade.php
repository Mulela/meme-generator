@extends('layouts.app')

@section('content')
<div class="flex items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Galerie</h1>
        <p class="mt-1 text-sm text-zinc-400">Tes mèmes enregistrés.</p>
    </div>

    <a href="{{ route('editor') }}" class="rounded-xl bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-white">
        Nouveau mème
    </a>
</div>

@if (session('success'))
<div class="mt-6 rounded-xl border border-zinc-800 bg-zinc-900/30 p-3 text-sm text-zinc-200">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="mt-6 rounded-xl border border-red-900/40 bg-red-950/30 p-3 text-sm text-red-200">
    {{ session('error') }}
</div>
@endif

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($memes as $meme)
    @php
    $imgUrl = asset('storage/' . $meme->image_path);
    @endphp

    <div class="rounded-2xl border border-zinc-800 bg-zinc-950 p-3">
        <div class="aspect-video rounded-xl border border-zinc-800 bg-zinc-900/20 overflow-hidden flex items-center justify-center">
            <img src="{{ $imgUrl }}" alt="Mème #{{ $meme->id }}" class="w-full h-full object-contain" loading="lazy" />
        </div>

        <div class="mt-3 flex items-center justify-between gap-2">
            <span class="text-xs text-zinc-500">#{{ $meme->id }}</span>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('memes.download', $meme) }}" class="rounded-lg border border-zinc-800 px-3 py-2 text-xs text-zinc-200 hover:bg-zinc-900">
                    Télécharger
                </a>

                <button type="button" class="shareBtn rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800" data-link="{{ $imgUrl }}" data-title="Mon mème #{{ $meme->id }}">
                    Partager
                </button>

                <button type="button" class="copyLinkBtn rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800" data-link="{{ $imgUrl }}">
                    Copier lien
                </button>

                <form method="POST" action="{{ route('memes.destroy', $meme) }}" class="inline deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-lg border border-red-900/60 px-3 py-2 text-xs text-red-200 hover:bg-red-950/30">
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="text-sm text-zinc-400">
        Aucun mème pour l’instant. Clique sur <span class="text-zinc-200">“Nouveau mème”</span> pour commencer.
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $memes->links() }}
</div>

<script>
    document.addEventListener('click', async (e) => {
        const copyBtn = e.target.closest('.copyLinkBtn');
        if (copyBtn) {
            const link = copyBtn.getAttribute('data-link');
            try {
                await navigator.clipboard.writeText(link);
                copyBtn.textContent = 'Copié !';
                setTimeout(() => copyBtn.textContent = 'Copier lien', 1200);
            } catch {
                alert('Impossible de copier. Lien : ' + link);
            }
            return;
        }

        const shareBtn = e.target.closest('.shareBtn');
        if (shareBtn) {
            const link = shareBtn.getAttribute('data-link');
            const title = shareBtn.getAttribute('data-title') || 'Mème';
            try {
                if (navigator.share) {
                    await navigator.share({
                        title
                        , text: 'Regarde ce mème 😄'
                        , url: link
                    });
                } else {
                    // Fallback simple
                    await navigator.clipboard.writeText(link);
                    alert('Partage non supporté ici. Lien copié : ' + link);
                }
            } catch (err) {
                // Si l’utilisateur annule, pas grave
                console.log('share canceled/failed', err);
            }
            return;
        }
    });

    document.addEventListener('submit', (e) => {
        const form = e.target.closest('.deleteForm');
        if (!form) return;

        const ok = confirm('Supprimer ce mème ? (action irréversible)');
        if (!ok) e.preventDefault();
    });

</script>
@endsection
