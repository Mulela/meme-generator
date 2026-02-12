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

<div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @for ($i = 0; $i < 6; $i++) <div class="rounded-2xl border border-zinc-800 bg-zinc-950 p-3">
        <div class="aspect-video rounded-xl bg-zinc-900/30 border border-zinc-800 flex items-center justify-center">
            <span class="text-xs text-zinc-500">Meme placeholder</span>
        </div>
        <div class="mt-3 flex items-center justify-between">
            <span class="text-xs text-zinc-500">—</span>
            <button disabled class="rounded-lg border border-zinc-800 px-3 py-2 text-xs text-zinc-400 cursor-not-allowed">
                Download
            </button>
        </div>
</div>
@endfor
</div>
@endsection
