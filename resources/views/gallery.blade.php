@extends('layouts.app')

@section('content')
<div class="flex items-end justify-between gap-4">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Gallery</h1>
        <p class="mt-1 text-sm text-zinc-400">Previously created memes.</p>
    </div>

    <a href="{{ route('editor') }}" class="rounded-xl bg-zinc-100 px-4 py-2 text-sm font-medium text-zinc-900 hover:bg-white">
        Create new
    </a>
</div>

@if (session('success'))
    <div class="mt-6 rounded-xl border border-zinc-800 bg-zinc-900/30 p-3 text-sm text-zinc-200">
        {{ session('success') }}
    </div>
@endif

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse ($memes as $meme)
        <div class="rounded-2xl border border-zinc-800 bg-zinc-950 p-3">
            <div class="aspect-video rounded-xl border border-zinc-800 bg-zinc-900/20 overflow-hidden flex items-center justify-center">
                <img
                    src="{{ asset('storage/' . $meme->image_path) }}"
                    alt="Meme #{{ $meme->id }}"
                    class="w-full h-full object-contain"
                    loading="lazy"
                />
            </div>

            <div class="mt-3 flex items-center justify-between">
                <span class="text-xs text-zinc-500">#{{ $meme->id }}</span>

                <div class="flex items-center gap-2">
                    <a href="{{ route('memes.download', $meme) }}"
                       class="rounded-lg border border-zinc-800 px-3 py-2 text-xs text-zinc-200 hover:bg-zinc-900">
                        Download
                    </a>
                    <button type="button"
                            class="copyLinkBtn rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800"
                            data-link="{{ asset('storage/' . $meme->image_path) }}">
                        Copy link
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="text-sm text-zinc-400">No memes yet.</div>
    @endforelse
</div>

<div class="mt-6">
    {{ $memes->links() }}
</div>

<script>
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('.copyLinkBtn');
    if (!btn) return;
    const link = btn.getAttribute('data-link');
    try {
        await navigator.clipboard.writeText(link);
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy link', 1200);
    } catch {
        alert('Copy failed. Link: ' + link);
    }
});
</script>
@endsection
