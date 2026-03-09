@extends('layouts.app')
@section('title', 'Detail Memo')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('memo.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">Detail Memo</h1>
                <p class="text-sm text-gray-400 mono">{{ $memo->memo_number }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge {{ $memo->getStatusColorClass() }} text-sm px-4 py-1.5">{{ $memo->status }}</span>
            <a href="{{ route('memo.edit', $memo) }}" class="btn-secondary text-sm flex items-center gap-1.5">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 mb-4">Informasi Memo</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-1">Deskripsi Permintaan</p>
                        <p class="text-gray-800 text-sm leading-relaxed">{{ $memo->request_description }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        @php $fields = [
                            ['label'=>'Nomor Memo',       'value'=>$memo->memo_number,                           'mono'=>true],
                            ['label'=>'Kategori',          'value'=>$memo->category],
                            ['label'=>'User Divisi',       'value'=>$memo->division],
                            ['label'=>'PIC DTM',           'value'=>$memo->pic_dtm],
                            ['label'=>'Tanggal Diterima',  'value'=>$memo->received_date?->format('d/m/Y')],
                            ['label'=>'Tanggal Diserahkan','value'=>$memo->submitted_date?->format('d/m/Y') ?? '—'],
                        ]; @endphp
                        @foreach($fields as $f)
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">{{ $f['label'] }}</p>
                            <p class="text-sm font-semibold text-gray-800 {{ isset($f['mono']) ? 'mono' : '' }}">{{ $f['value'] }}</p>
                        </div>
                        @endforeach
                    </div>
                    @if($memo->handover_memo_number)
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Nomor Memo Penyerahan</p>
                        <p class="text-sm font-semibold text-gray-800 mono">{{ $memo->handover_memo_number }}</p>
                    </div>
                    @endif
                    @if($memo->notes)
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Keterangan</p>
                        <p class="text-sm text-gray-600">{{ $memo->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ===== ATTACHMENT PREVIEW ===== -->
            @if($memo->hasAttachment())
            <div class="bg-white rounded-xl border border-gray-100 p-6" x-data="{ lightbox: false }">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Lampiran File</h3>
                    <a href="{{ route('memo.attachment.download', $memo) }}"
                       class="flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition-colors">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download
                    </a>
                </div>

                @if($memo->isImage())
                    {{-- IMAGE PREVIEW --}}
                    <div class="relative group cursor-zoom-in rounded-xl overflow-hidden border border-gray-100"
                         @click="lightbox = true">
                        <img src="{{ Storage::disk('public')->url($memo->attachment_path) }}"
                             alt="{{ $memo->attachment_name }}"
                             class="w-full max-h-80 object-contain bg-gray-50 transition-transform group-hover:scale-[1.01]">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all flex items-center justify-center">
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity bg-white/90 backdrop-blur rounded-full px-4 py-2 text-xs font-semibold text-gray-700 flex items-center gap-2">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                Klik untuk perbesar
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">{{ $memo->attachment_name }}</p>

                    {{-- LIGHTBOX --}}
                    <div x-show="lightbox"
                         x-transition
                         class="fixed inset-0 z-50 flex items-center justify-center modal-backdrop p-4"
                         @click.self="lightbox = false"
                         @keydown.escape.window="lightbox = false">
                        <div class="relative max-w-5xl w-full">
                            <img src="{{ Storage::disk('public')->url($memo->attachment_path) }}"
                                 alt="{{ $memo->attachment_name }}"
                                 class="w-full max-h-[85vh] object-contain rounded-xl shadow-2xl">
                            <button @click="lightbox = false"
                                    class="absolute -top-3 -right-3 w-8 h-8 bg-white rounded-full shadow-lg flex items-center justify-center text-gray-600 hover:text-red-500 transition-colors">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                            <p class="text-center text-white/70 text-xs mt-3">{{ $memo->attachment_name }}</p>
                        </div>
                    </div>

                @elseif($memo->isPdf())
                    {{-- PDF PREVIEW --}}
                    <div class="rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                        <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border-b border-red-100">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            <span class="text-xs font-semibold text-red-700">{{ $memo->attachment_name }}</span>
                        </div>
                        <iframe src="{{ Storage::disk('public')->url($memo->attachment_path) }}"
                                class="w-full h-96 bg-white"
                                frameborder="0"
                                title="{{ $memo->attachment_name }}">
                            <p class="p-4 text-sm text-gray-500">Browser tidak mendukung preview PDF.
                                <a href="{{ route('memo.attachment.download', $memo) }}" class="text-emerald-700 underline">Download file</a>
                            </p>
                        </iframe>
                    </div>

                @else
                    {{-- OTHER FILE --}}
                    <div class="flex items-center gap-4 p-5 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $memo->attachment_name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $memo->attachment_mime }}</p>
                        </div>
                        <a href="{{ route('memo.attachment.download', $memo) }}"
                           class="btn-primary text-xs flex items-center gap-1.5 flex-shrink-0">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Download
                        </a>
                    </div>
                @endif
            </div>
            @endif
            {{-- ===== END ATTACHMENT ===== --}}

            <!-- Meta -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Metadata</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Dibuat oleh</p>
                        <p class="font-semibold text-gray-800">{{ $memo->creator?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Dibuat pada</p>
                        <p class="font-semibold text-gray-800">{{ $memo->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Terakhir diupdate</p>
                        <p class="font-semibold text-gray-800">{{ $memo->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl border border-gray-100 p-6 h-fit">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-5">Timeline Aktivitas</h3>
            @php $icons = ['created'=>'bg-emerald-500','status_changed'=>'bg-blue-500','updated'=>'bg-yellow-500']; @endphp
            @if($memo->activities->count() > 0)
            <div class="space-y-4">
                @foreach($memo->activities as $act)
                <div class="flex gap-3">
                    <div class="flex flex-col items-center">
                        <div class="w-2.5 h-2.5 rounded-full {{ $icons[$act->action] ?? 'bg-gray-400' }} flex-shrink-0 mt-0.5"></div>
                        @if(!$loop->last)<div class="w-0.5 bg-gray-100 flex-1 my-1"></div>@endif
                    </div>
                    <div class="pb-3 flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-800">{{ ['created'=>'Dibuat','status_changed'=>'Status Berubah','updated'=>'Diperbarui'][$act->action] ?? $act->action }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $act->description }}</p>
                        @if($act->old_status && $act->new_status)
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="text-xs text-gray-400">{{ $act->old_status }}</span>
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2"><path d="M5 12h14"/><polyline points="12 5 19 12 12 19"/></svg>
                            <span class="text-xs font-semibold text-emerald-700">{{ $act->new_status }}</span>
                        </div>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $act->user?->name }} · {{ $act->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-xs text-gray-400 text-center py-4">Belum ada aktivitas</p>
            @endif
        </div>
    </div>
</div>
@endsection
