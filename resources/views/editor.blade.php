@extends('layouts.app')

@section('content')
<div class="flex flex-col gap-6">
    <div>
        <h1 class="text-2xl font-semibold tracking-tight">Create a meme</h1>
        <p class="mt-1 text-sm text-zinc-400">
            Upload an image, add top/bottom text, preview in real-time, then download and save to the gallery.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Controls (FORM) -->
        <form method="POST" action="{{ route('memes.store') }}"
              class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 shadow-sm">
            @csrf

            <h2 class="text-sm font-medium text-zinc-200">Controls</h2>

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
                    <div class="mt-2 flex items-center gap-3">
                        <input type="file" accept="image/png,image/jpeg"
                               class="block w-full text-sm text-zinc-300
                               file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-sm file:text-zinc-100 hover:file:bg-zinc-800" />
                    </div>
                    <p class="mt-2 text-xs text-zinc-500">PNG/JPG, max 5MB. (Canvas step will use this)</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Top text</label>
                        <input name="top_text" type="text" maxlength="100" placeholder="TOP TEXT"
                               value="{{ old('top_text') }}"
                               class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Bottom text</label>
                        <input name="bottom_text" type="text" maxlength="100" placeholder="BOTTOM TEXT"
                               value="{{ old('bottom_text') }}"
                               class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Text size</label>
                        <input type="range" min="20" max="80" value="48" class="mt-3 w-full" disabled />
                        <p class="mt-1 text-xs text-zinc-500">Enabled after Canvas step.</p>
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-zinc-800 bg-zinc-900/30 px-3 py-2">
                        <div>
                            <p class="text-xs text-zinc-400">Outline</p>
                            <p class="text-sm text-zinc-200">Enabled</p>
                        </div>
                        <button type="button" disabled class="rounded-lg bg-zinc-900 px-3 py-2 text-xs text-zinc-400 cursor-not-allowed">
                            Toggle
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button type="button" disabled class="rounded-xl bg-zinc-800 px-4 py-2 text-sm text-zinc-400 cursor-not-allowed">
                        Download
                    </button>

                    <button type="submit"
                            class="rounded-xl border border-zinc-800 px-4 py-2 text-sm text-zinc-200 hover:bg-zinc-900">
                        Save to gallery
                    </button>

                    <button type="reset" class="ml-auto rounded-xl px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-900">
                        Reset
                    </button>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/20 p-3 text-xs text-zinc-400">
                    DB smoke test: Save will create a placeholder meme record (image will be generated in the next step).
                </div>
            </div>
        </form>

        <!-- Preview -->
        <section class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-sm font-medium text-zinc-200">Live preview</h2>
                <span class="text-xs text-zinc-500">Canvas (client-side)</span>
            </div>

            <div class="mt-4 aspect-video rounded-xl border border-dashed border-zinc-800 bg-zinc-900/20 flex items-center justify-center">
                <p class="text-sm text-zinc-500">Preview area (canvas will appear here)</p>
            </div>
        </section>
    </div>
</div>
@endsection
