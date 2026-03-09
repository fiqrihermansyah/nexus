@extends('layouts.app')
@section('title', 'Memo Request')

@section('content')
<div class="p-6 space-y-5" x-data="memoTable()">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Memo Request</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola semua permintaan data memo</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('memo.export', request()->query()) }}" class="btn-secondary flex items-center gap-2 text-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="{{ route('memo.create') }}" class="btn-primary flex items-center gap-2 text-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Memo
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('memo.index') }}">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nomor memo, divisi..." class="form-input pl-8">
                    </div>
                </div>
                <div class="w-40">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Semua Status</option>
                        @foreach(['Pending','On Progress','Done','Discard'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-36">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Kategori</label>
                    <select name="category" class="form-input">
                        <option value="">Semua</option>
                        @foreach(['Quarterly','Monthly','Ad-Hoc'] as $c)
                            <option value="{{ $c }}" {{ request('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-40">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                </div>
                <div class="w-40">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary text-sm px-4 py-[9px]">Filter</button>
                    @if(request()->hasAny(['search','status','category','date_from','date_to']))
                        <a href="{{ route('memo.index') }}" class="btn-secondary text-sm px-4 py-[9px]">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400">Menampilkan <span class="font-semibold text-gray-700">{{ $memos->firstItem() }}–{{ $memos->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $memos->total() }}</span> data</p>
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <span>Urut:</span>
                <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'received_date', 'sort_dir' => request('sort_dir') == 'asc' ? 'desc' : 'asc']) }}" class="font-semibold text-emerald-700">Tanggal {{ request('sort_dir') == 'asc' ? '↑' : '↓' }}</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full data-table text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b border-gray-100">
                        <th class="px-5 py-3 text-left w-10">#</th>
                        <th class="px-5 py-3 text-left">Nomor Memo</th>
                        <th class="px-5 py-3 text-left">Deskripsi</th>
                        <th class="px-5 py-3 text-left">Kategori</th>
                        <th class="px-5 py-3 text-left">Divisi</th>
                        <th class="px-5 py-3 text-left">PIC DTM</th>
                        <th class="px-5 py-3 text-left">Tgl Diterima</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-left">Tgl Diserahkan</th>
                        <th class="px-5 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($memos as $i => $memo)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-5 py-3.5 text-gray-400 text-xs mono">{{ $memos->firstItem() + $i }}</td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('memo.show', $memo) }}" class="font-bold text-emerald-700 hover:text-emerald-800 mono text-xs">{{ $memo->memo_number }}</a>
                        </td>
                        <td class="px-5 py-3.5 max-w-[200px]">
                            <div class="truncate text-gray-700" title="{{ $memo->request_description }}">{{ $memo->request_description }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-full px-2.5 py-0.5 font-medium">{{ $memo->category }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-700 font-medium">{{ $memo->division }}</td>
                        <td class="px-5 py-3.5 text-gray-700">{{ $memo->pic_dtm }}</td>
                        <td class="px-5 py-3.5 text-gray-500 mono text-xs">{{ $memo->received_date?->format('d/m/Y') }}</td>
                        <td class="px-5 py-3.5">
                            <span class="badge {{ $memo->getStatusColorClass() }}">{{ $memo->status }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-gray-500 mono text-xs">{{ $memo->submitted_date?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('memo.show', $memo) }}" class="p-1.5 rounded-lg hover:bg-emerald-50 text-gray-400 hover:text-emerald-700 transition-colors" title="Detail">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('memo.edit', $memo) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition-colors" title="Edit">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <button @click="confirmDelete({{ $memo->id }}, '{{ $memo->memo_number }}')"
                                        class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors" title="Hapus">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-gray-400 text-sm">Tidak ada data memo ditemukan</p>
                                <a href="{{ route('memo.create') }}" class="btn-primary text-xs">+ Buat Memo Baru</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($memos->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">
            {{ $memos->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    <!-- Delete Confirm Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4" @click.stop>
            <div class="flex items-center gap-4 mb-4">
                <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Hapus Memo</h3>
                    <p class="text-sm text-gray-500 mt-0.5">Memo <span class="font-semibold mono text-gray-800" x-text="deleteMemoNumber"></span> akan dihapus permanen.</p>
                </div>
            </div>
            <div class="flex gap-2 mt-5">
                <button @click="showDeleteModal = false" class="btn-secondary flex-1">Batal</button>
                <form :action="'/memo/' + deleteMemoId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-semibold transition-colors">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function memoTable() {
    return {
        showDeleteModal: false,
        deleteMemoId: null,
        deleteMemoNumber: '',
        confirmDelete(id, number) {
            this.deleteMemoId = id;
            this.deleteMemoNumber = number;
            this.showDeleteModal = true;
        }
    }
}
</script>
@endpush
