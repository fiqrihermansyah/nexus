@extends('layouts.app')
@section('title', 'Detail Job Schedule')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('job-schedule.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $jobSchedule->job_name }}</h1>
                <p class="text-sm text-gray-400">{{ $jobSchedule->division }} — {{ $jobSchedule->delivery_type }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="badge {{ $jobSchedule->getStatusColorClass() }} text-sm px-4 py-1.5">{{ $jobSchedule->status }}</span>
            <a href="{{ route('job-schedule.edit', $jobSchedule) }}" class="btn-secondary text-sm flex items-center gap-1.5">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- Main -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Info Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 mb-4">Informasi Job</h3>
                <div class="space-y-4">
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                        <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wide mb-1">Request / Subject Email</p>
                        <p class="text-gray-800 text-sm leading-relaxed">{{ $jobSchedule->request_subject }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        @php $fields = [
                            ['label'=>'Divisi',          'value'=>$jobSchedule->division],
                            ['label'=>'Jenis Pengiriman','value'=>$jobSchedule->delivery_type],
                            ['label'=>'Email PIC',       'value'=>$jobSchedule->email_pic ?? '—'],
                            ['label'=>'CC / REQ',        'value'=>$jobSchedule->cc_req ?? '—'],
                            ['label'=>'File / Table',    'value'=>$jobSchedule->file_table_name ?? '—', 'mono'=>true],
                            ['label'=>'PIC DTM',         'value'=>$jobSchedule->pic_dtm ?? '—'],
                        ]; @endphp
                        @foreach($fields as $f)
                        <div>
                            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">{{ $f['label'] }}</p>
                            <p class="text-sm font-semibold text-gray-800 {{ isset($f['mono']) ? 'mono' : '' }} break-all">{{ $f['value'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 mb-4">Konfigurasi Schedule</h3>
                <div class="grid grid-cols-3 gap-4 mb-5">
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Schedule</p>
                        <p class="text-sm font-semibold text-gray-800 mono">{{ $jobSchedule->schedule ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Jam</p>
                        <p class="text-sm font-semibold text-gray-800 mono">{{ $jobSchedule->jam ? \Carbon\Carbon::parse($jobSchedule->jam)->format('H:i') : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Day</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $jobSchedule->day ?? '—' }}</p>
                    </div>
                </div>

                <!-- Frequency visual -->
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Frekuensi Pengiriman</p>
                <div class="flex items-center gap-4">
                    @php $freqs = [
                        ['label'=>'Daily',   'active'=>$jobSchedule->is_daily,   'on'=>'bg-orange-400 text-white border-orange-400','off'=>'bg-gray-50 text-gray-300 border-gray-200'],
                        ['label'=>'Weekly',  'active'=>$jobSchedule->is_weekly,  'on'=>'bg-purple-400 text-white border-purple-400', 'off'=>'bg-gray-50 text-gray-300 border-gray-200'],
                        ['label'=>'Monthly', 'active'=>$jobSchedule->is_monthly, 'on'=>'bg-indigo-400 text-white border-indigo-400', 'off'=>'bg-gray-50 text-gray-300 border-gray-200'],
                    ]; @endphp
                    @foreach($freqs as $f)
                    <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl border {{ $f['active'] ? $f['on'] : $f['off'] }} transition-all">
                        @if($f['active'])
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        @endif
                        <span class="text-sm font-bold">{{ $f['label'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Query -->
            @if($jobSchedule->query)
            <div class="bg-white rounded-xl border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Query</h3>
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded mono">SQL</span>
                </div>
                <div class="query-block">{{ $jobSchedule->query }}</div>
            </div>
            @endif

        </div>

        <!-- Sidebar -->
        <div class="space-y-4">

            <!-- Quick Stats -->
            <div class="bg-white rounded-xl border border-gray-100 p-5">
                <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-4">Ringkasan</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Status</span>
                        <span class="badge {{ $jobSchedule->getStatusColorClass() }}">{{ $jobSchedule->status }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Pengiriman</span>
                        <span class="text-xs font-semibold text-gray-700">{{ $jobSchedule->delivery_type }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Frekuensi</span>
                        <div class="flex gap-1">
                            @if($jobSchedule->is_daily)   <span class="freq-badge freq-badge-on text-orange-700" style="background:#fff7ed;border-color:#fed7aa">D</span>@endif
                            @if($jobSchedule->is_weekly)  <span class="freq-badge freq-badge-on text-purple-700" style="background:#faf5ff;border-color:#d8b4fe">W</span>@endif
                            @if($jobSchedule->is_monthly) <span class="freq-badge freq-badge-on text-indigo-700" style="background:#eef2ff;border-color:#c7d2fe">M</span>@endif
                            @if(!$jobSchedule->is_daily && !$jobSchedule->is_weekly && !$jobSchedule->is_monthly)
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </div>
                    </div>
                    <div class="pt-2 border-t border-gray-50">
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">Dibuat</span>
                            <span class="text-xs text-gray-600">{{ $jobSchedule->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-400">Update</span>
                            <span class="text-xs text-gray-600">{{ $jobSchedule->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center justify-between mt-1">
                            <span class="text-xs text-gray-400">Dibuat oleh</span>
                            <span class="text-xs font-semibold text-gray-700">{{ $jobSchedule->creator?->name ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-xl border border-gray-100 p-5 space-y-2">
                <a href="{{ route('job-schedule.edit', $jobSchedule) }}" class="btn-primary w-full flex items-center justify-center gap-2 text-sm">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    Edit Job
                </a>
                <form action="{{ route('job-schedule.destroy', $jobSchedule) }}" method="POST"
                      onsubmit="return confirm('Hapus job ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full flex items-center justify-center gap-2 text-sm py-2 px-4 rounded-lg border border-red-100 text-red-500 hover:bg-red-50 transition-colors font-medium">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6"/></svg>
                        Hapus Job
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
