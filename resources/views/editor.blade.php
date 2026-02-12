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
        <!-- Controls -->
        <section class="rounded-2xl border border-zinc-800 bg-zinc-950 p-5 shadow-sm">
            <h2 class="text-sm font-medium text-zinc-200">Controls</h2>

            <div class="mt-4 space-y-4">
                <div>
                    <label class="text-xs text-zinc-400">Image</label>
                    <div class="mt-2 flex items-center gap-3">
                        <input type="file" accept="image/png,image/jpeg" class="block w-full text-sm text-zinc-300
                            file:mr-4 file:rounded-lg file:border-0 file:bg-zinc-900 file:px-4 file:py-2 file:text-sm file:text-zinc-100 hover:file:bg-zinc-800" />
                    </div>
                    <p class="mt-2 text-xs text-zinc-500">PNG/JPG, max 5MB.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Top text</label>
                        <input type="text" maxlength="100" placeholder="TOP TEXT"
                               class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                    <div>
                        <label class="text-xs text-zinc-400">Bottom text</label>
                        <input type="text" maxlength="100" placeholder="BOTTOM TEXT"
                               class="mt-2 w-full rounded-xl border border-zinc-800 bg-zinc-900/40 px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-zinc-600" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs text-zinc-400">Text size</label>
                        <input type="range" min="20" max="80" value="48"
                               class="mt-3 w-full" />
                    </div>
                    <div class="flex items-center justify-between rounded-xl border border-zinc-800 bg-zinc-900/30 px-3 py-2">
                        <div>
                            <p class="text-xs text-zinc-400">Outline</p>
                            <p class="text-sm text-zinc-200">Enabled</p>
                        </div>
                        <button type="button" class="rounded-lg bg-zinc-900 px-3 py-2 text-xs hover:bg-zinc-800">
                            Toggle
                        </button>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button disabled class="rounded-xl bg-zinc-800 px-4 py-2 text-sm text-zinc-400 cursor-not-allowed">
                        Download
                    </button>
                    <button disabled class="rounded-xl border border-zinc-800 px-4 py-2 text-sm text-zinc-400 cursor-not-allowed">
                        Save to gallery
                    </button>
                    <button type="button" class="ml-auto rounded-xl px-4 py-2 text-sm text-zinc-300 hover:bg-zinc-900">
                        Reset
                    </button>
                </div>

                <div class="rounded-xl border border-zinc-800 bg-zinc-900/20 p-3 text-xs text-zinc-400">
                    Preview will be enabled once an image is selected.
                </div>
            </div>
        </section>

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
