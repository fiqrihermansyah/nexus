@extends('layouts.app')
@section('title', 'Job Schedule List')

@section('content')
<div class="p-6 space-y-5" x-data="jobTable()">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-gray-900">Job Schedule List</h1>
            <p class="text-sm text-gray-400 mt-0.5">Kelola semua jadwal pengiriman data otomatis</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('job-schedule.export', request()->query()) }}" class="btn-secondary flex items-center gap-2 text-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Export CSV
            </a>
            <a href="{{ route('job-schedule.create') }}" class="btn-primary flex items-center gap-2 text-sm">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Tambah Job
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-100 p-4">
        <form method="GET" action="{{ route('job-schedule.index') }}">
            <div class="flex flex-wrap gap-3 items-end">
                <div class="flex-1 min-w-[180px]">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Job name, divisi, PIC..." class="form-input pl-8">
                    </div>
                </div>
                <div class="w-36">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Semua</option>
                        @foreach(['Active','Inactive','Pending'] as $s)
                            <option value="{{ $s }}" {{ request('status') == $s ? 'selected':'' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-36">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Divisi</label>
                    <select name="division" class="form-input">
                        <option value="">Semua</option>
                        @foreach($divisions as $div)
                            <option value="{{ $div }}" {{ request('division') == $div ? 'selected':'' }}>{{ $div }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-36">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Pengiriman</label>
                    <select name="delivery_type" class="form-input">
                        <option value="">Semua</option>
                        @foreach(['CSV','PDF','DOCX','TXT','ODT'] as $t)
                            <option value="{{ $t }}" {{ request('delivery_type') == $t ? 'selected':'' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="w-36">
                    <label class="text-xs font-semibold text-gray-500 mb-1 block uppercase tracking-wide">Frekuensi</label>
                    <select name="frequency" class="form-input">
                        <option value="">Semua</option>
                        <option value="daily"   {{ request('frequency')=='daily'   ? 'selected':'' }}>Daily</option>
                        <option value="weekly"  {{ request('frequency')=='weekly'  ? 'selected':'' }}>Weekly</option>
                        <option value="monthly" {{ request('frequency')=='monthly' ? 'selected':'' }}>Monthly</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="btn-primary text-sm px-4 py-[9px]">Filter</button>
                    @if(request()->hasAny(['search','status','division','delivery_type','frequency']))
                        <a href="{{ route('job-schedule.index') }}" class="btn-secondary text-sm px-4 py-[9px]">Reset</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-50 flex items-center justify-between">
            <p class="text-xs text-gray-400">Menampilkan <span class="font-semibold text-gray-700">{{ $jobs->firstItem() }}–{{ $jobs->lastItem() }}</span> dari <span class="font-semibold text-gray-700">{{ $jobs->total() }}</span> job</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full data-table text-sm">
                <thead>
                    <tr class="bg-gray-50/60 border-b border-gray-100">
                        <th class="px-4 py-3 text-left w-8">#</th>
                        <th class="px-4 py-3 text-left">Divisi</th>
                        <th class="px-4 py-3 text-left">Nama Job</th>
                        <th class="px-4 py-3 text-left">Request/Subject</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-left">Email PIC</th>
                        <th class="px-4 py-3 text-left">File/Table</th>
                        <th class="px-4 py-3 text-left">Schedule</th>
                        <th class="px-4 py-3 text-left">Jam</th>
                        <th class="px-4 py-3 text-left">Day</th>
                        <th class="px-4 py-3 text-left">PIC DTM</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <!-- Frequency columns -->
                        <th class="px-3 py-3 text-center bg-orange-50/60 border-l border-orange-100">
                            <span class="text-orange-600 text-xs font-bold">D</span>
                        </th>
                        <th class="px-3 py-3 text-center bg-purple-50/60">
                            <span class="text-purple-600 text-xs font-bold">W</span>
                        </th>
                        <th class="px-3 py-3 text-center bg-indigo-50/60 border-r border-indigo-100">
                            <span class="text-indigo-600 text-xs font-bold">M</span>
                        </th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($jobs as $i => $job)
                    <tr class="transition-colors hover:bg-gray-50/50">
                        <td class="px-4 py-3 text-gray-400 mono text-xs">{{ $jobs->firstItem() + $i }}</td>
                        <td class="px-4 py-3 text-xs font-semibold text-gray-700">{{ $job->division }}</td>
                        <td class="px-4 py-3 max-w-[160px]">
                            <a href="{{ route('job-schedule.show', $job) }}" class="text-gray-800 font-semibold text-xs hover:text-emerald-700 block truncate" title="{{ $job->job_name }}">{{ $job->job_name }}</a>
                        </td>
                        <td class="px-4 py-3 max-w-[140px]">
                            <span class="text-xs text-gray-500 block truncate" title="{{ $job->request_subject }}">{{ $job->request_subject }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @php $typeColors = ['CSV'=>'bg-blue-50 text-blue-700 border-blue-100','PDF'=>'bg-purple-50 text-purple-700 border-purple-100','DOCX'=>'bg-orange-50 text-orange-700 border-orange-100','TXT'=>'bg-gray-50 text-gray-600 border-gray-200','ODT'=>'bg-gray-50 text-gray-600 border-gray-200']; @endphp
                            <span class="text-xs px-2 py-0.5 rounded-full border font-medium {{ $typeColors[$job->delivery_type] ?? 'bg-gray-50 text-gray-600 border-gray-200' }}">{{ $job->delivery_type }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 max-w-[120px]">
                            <span class="block truncate" title="{{ $job->email_pic }}">{{ $job->email_pic ?? '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-600 mono max-w-[120px]">
                            <span class="block truncate" title="{{ $job->file_table_name }}">{{ $job->file_table_name ?? '—' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-gray-500 mono">{{ $job->schedule ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500 mono">{{ $job->jam ? \Carbon\Carbon::parse($job->jam)->format('H:i') : '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ $job->day ?? '—' }}</td>
                        <td class="px-4 py-3 text-xs text-gray-700 font-medium">{{ $job->pic_dtm ?? '—' }}</td>
                        <td class="px-4 py-3"><span class="badge {{ $job->getStatusColorClass() }}">{{ $job->status }}</span></td>

                        <!-- Frequency cells -->
                        <td class="px-3 py-3 text-center bg-orange-50/30">
                            @if($job->is_daily)
                                <svg class="mx-auto text-orange-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                <span class="text-gray-200 text-lg leading-none">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center bg-purple-50/30">
                            @if($job->is_weekly)
                                <svg class="mx-auto text-purple-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                <span class="text-gray-200 text-lg leading-none">—</span>
                            @endif
                        </td>
                        <td class="px-3 py-3 text-center bg-indigo-50/30">
                            @if($job->is_monthly)
                                <svg class="mx-auto text-indigo-500" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                            @else
                                <span class="text-gray-200 text-lg leading-none">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('job-schedule.show', $job) }}" class="p-1.5 rounded-lg hover:bg-emerald-50 text-gray-400 hover:text-emerald-700 transition-colors">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                </a>
                                <a href="{{ route('job-schedule.edit', $job) }}" class="p-1.5 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </a>
                                <button @click="confirmDelete({{ $job->id }}, '{{ addslashes($job->job_name) }}')"
                                        class="p-1.5 rounded-lg hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="17" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gray-50 flex items-center justify-center">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <p class="text-gray-400 text-sm">Tidak ada job schedule ditemukan</p>
                                <a href="{{ route('job-schedule.create') }}" class="btn-primary text-xs">+ Tambah Job Baru</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jobs->hasPages())
        <div class="px-5 py-4 border-t border-gray-50">
            {{ $jobs->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4" @click.stop>
            <div class="flex items-center gap-4 mb-5">
                <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Hapus Job Schedule</h3>
                    <p class="text-sm text-gray-500 mt-0.5"><span class="font-semibold text-gray-800" x-text="deleteJobName"></span> akan dihapus permanen.</p>
                </div>
            </div>
            <div class="flex gap-2">
                <button @click="showDeleteModal=false" class="btn-secondary flex-1">Batal</button>
                <form :action="'/job-schedule/' + deleteJobId" method="POST" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white rounded-lg px-4 py-2 text-sm font-semibold transition-colors">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function jobTable() {
    return {
        showDeleteModal: false,
        deleteJobId: null,
        deleteJobName: '',
        confirmDelete(id, name) { this.deleteJobId = id; this.deleteJobName = name; this.showDeleteModal = true; }
    }
}
</script>
@endpush
