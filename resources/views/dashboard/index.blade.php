@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="p-6 space-y-6">

    <!-- Page Header -->
    <div class="page-header rounded-2xl p-6 border border-emerald-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-emerald-700 text-xs font-semibold uppercase tracking-widest mb-1">Overview</p>
                <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
                <p class="text-gray-500 text-sm mt-0.5">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('job-schedule.create') }}" class="btn-secondary flex items-center gap-2 text-sm">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    Tambah Job
                </a>
                <a href="{{ route('memo.create') }}" class="btn-primary flex items-center gap-2 text-sm">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Buat Memo
                </a>
            </div>
        </div>
    </div>

    <!-- ===== MEMO SECTION ===== -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-1 h-5 bg-emerald-600 rounded-full"></div>
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Memo Request</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @php $memoCards = [
                ['label'=>'Total Memo',  'value'=>$memoStats['total'],       'icon'=>'<path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>', 'border'=>'border-emerald-100', 'bg'=>'bg-emerald-50', 'clr'=>'text-emerald-700'],
                ['label'=>'Pending',     'value'=>$memoStats['pending'],     'icon'=>'<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',                  'border'=>'border-gray-200',    'bg'=>'bg-gray-50',    'clr'=>'text-gray-600'],
                ['label'=>'Selesai',     'value'=>$memoStats['done'],        'icon'=>'<polyline points="20 6 9 17 4 12"/>',                                                                                                    'border'=>'border-green-100',   'bg'=>'bg-green-50',   'clr'=>'text-green-700'],
                ['label'=>'Discard',     'value'=>$memoStats['discard'],     'icon'=>'<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a1 1 0 011-1h4a1 1 0 011 1v2"/>',               'border'=>'border-red-100',     'bg'=>'bg-red-50',     'clr'=>'text-red-600'],
            ]; @endphp
            @foreach($memoCards as $c)
            <div class="stat-card rounded-xl p-5 border {{ $c['border'] }}">
                <div class="flex items-start justify-between mb-3">
                    <div class="p-2 rounded-lg {{ $c['bg'] }}">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="{{ $c['clr'] }}">{!! $c['icon'] !!}</svg>
                    </div>
                </div>
                <div class="text-3xl font-bold text-gray-900 mono">{{ $c['value'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-1">{{ $c['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- ===== JOB SCHEDULE SECTION ===== -->
    <div>
        <div class="flex items-center gap-2 mb-3">
            <div class="w-1 h-5 bg-blue-500 rounded-full"></div>
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Job Schedule</h2>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-4">
            @php $jobCards = [
                ['label'=>'Total Jobs',  'value'=>$jobStats['total'],    'bg'=>'bg-blue-50',   'clr'=>'text-blue-700',   'border'=>'border-blue-100',   'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>'],
                ['label'=>'Active',      'value'=>$jobStats['active'],   'bg'=>'bg-emerald-50','clr'=>'text-emerald-700','border'=>'border-emerald-100','icon'=>'<polyline points="20 6 9 17 4 12"/>'],
                ['label'=>'Inactive',    'value'=>$jobStats['inactive'], 'bg'=>'bg-red-50',    'clr'=>'text-red-600',    'border'=>'border-red-100',    'icon'=>'<circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>'],
                ['label'=>'Daily',       'value'=>$jobStats['daily'],    'bg'=>'bg-orange-50', 'clr'=>'text-orange-700', 'border'=>'border-orange-100', 'icon'=>'<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>'],
                ['label'=>'Weekly',      'value'=>$jobStats['weekly'],   'bg'=>'bg-purple-50', 'clr'=>'text-purple-700', 'border'=>'border-purple-100', 'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>'],
                ['label'=>'Monthly',     'value'=>$jobStats['monthly'],  'bg'=>'bg-indigo-50', 'clr'=>'text-indigo-700', 'border'=>'border-indigo-100', 'icon'=>'<rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="8" y1="14" x2="8" y2="14"/><line x1="12" y1="14" x2="12" y2="14"/>'],
            ]; @endphp
            @foreach($jobCards as $c)
            <div class="stat-card rounded-xl p-4 border {{ $c['border'] }}">
                <div class="p-1.5 rounded-lg {{ $c['bg'] }} w-fit mb-3">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="{{ $c['clr'] }}">{!! $c['icon'] !!}</svg>
                </div>
                <div class="text-2xl font-bold text-gray-900 mono">{{ $c['value'] }}</div>
                <div class="text-xs text-gray-500 font-medium mt-0.5">{{ $c['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Memo Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Aktivitas Memo</h3>
                    <p class="text-xs text-gray-400 mt-0.5">12 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 text-xs">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600 inline-block"></span>Total</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-200 inline-block"></span>Selesai</span>
                </div>
            </div>
            <canvas id="memoChart" height="80"></canvas>
        </div>

        <!-- Job Frequency Donut -->
        <div class="bg-white rounded-xl border border-gray-100 p-6">
            <div class="mb-5">
                <h3 class="font-bold text-gray-900 text-sm">Job Frequency</h3>
                <p class="text-xs text-gray-400 mt-0.5">Distribusi frekuensi job</p>
            </div>
            <div class="flex justify-center mb-4">
                <canvas id="freqChart" width="160" height="160"></canvas>
            </div>
            <div class="space-y-2">
                @php $freqData = [
                    ['label'=>'Daily',   'value'=>$jobStats['daily'],   'color'=>'bg-orange-400'],
                    ['label'=>'Weekly',  'value'=>$jobStats['weekly'],  'color'=>'bg-purple-400'],
                    ['label'=>'Monthly', 'value'=>$jobStats['monthly'], 'color'=>'bg-indigo-400'],
                ]; @endphp
                @foreach($freqData as $f)
                <div class="flex items-center justify-between text-xs">
                    <span class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full {{ $f['color'] }} inline-block"></span>{{ $f['label'] }}</span>
                    <span class="font-bold text-gray-700 mono">{{ $f['value'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Tables Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        <!-- Recent Memos -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Recent Memo Requests</h3>
                    <p class="text-xs text-gray-400">5 terbaru</p>
                </div>
                <a href="{{ route('memo.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full data-table">
                    <thead>
                        <tr class="bg-gray-50/60">
                            <th class="px-4 py-3 text-left">Nomor Memo</th>
                            <th class="px-4 py-3 text-left">Divisi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentMemos as $memo)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('memo.show', $memo) }}" class="text-emerald-700 font-bold mono text-xs hover:underline">{{ $memo->memo_number }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">{{ $memo->division }}</td>
                            <td class="px-4 py-3"><span class="badge {{ $memo->getStatusColorClass() }}">{{ $memo->status }}</span></td>
                            <td class="px-4 py-3 text-xs text-gray-400 mono">{{ $memo->received_date?->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-xs text-gray-400">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">Recent Job Schedules</h3>
                    <p class="text-xs text-gray-400">5 terbaru</p>
                </div>
                <a href="{{ route('job-schedule.index') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full data-table">
                    <thead>
                        <tr class="bg-gray-50/60">
                            <th class="px-4 py-3 text-left">Job Name</th>
                            <th class="px-4 py-3 text-left">Divisi</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-center">Freq</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentJobs as $job)
                        <tr>
                            <td class="px-4 py-3">
                                <a href="{{ route('job-schedule.show', $job) }}" class="text-gray-800 font-semibold text-xs hover:text-emerald-700 truncate block max-w-[140px]">{{ $job->job_name }}</a>
                            </td>
                            <td class="px-4 py-3 text-xs text-gray-600">{{ $job->division }}</td>
                            <td class="px-4 py-3"><span class="badge {{ $job->getStatusColorClass() }}">{{ $job->status }}</span></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <span class="freq-badge {{ $job->is_daily ? 'freq-badge-on' : 'freq-badge-off' }}" title="Daily">D</span>
                                    <span class="freq-badge {{ $job->is_weekly ? 'freq-badge-on' : 'freq-badge-off' }}" title="Weekly">W</span>
                                    <span class="freq-badge {{ $job->is_monthly ? 'freq-badge-on' : 'freq-badge-off' }}" title="Monthly">M</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-8 text-center text-xs text-gray-400">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Divisions by Job -->
    @if($jobsByDivision->count() > 0)
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="font-bold text-gray-900 text-sm mb-4">Top Divisi — Job Schedule</h3>
        <div class="space-y-3">
            @php $maxDiv = $jobsByDivision->first()->total ?? 1; @endphp
            @foreach($jobsByDivision as $div)
            <div class="flex items-center gap-3">
                <span class="text-xs font-semibold text-gray-600 w-24 flex-shrink-0">{{ $div->division }}</span>
                <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all" style="width:{{ round($div->total / $maxDiv * 100) }}%"></div>
                </div>
                <span class="text-xs font-bold text-gray-700 mono w-6 text-right">{{ $div->total }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
// Memo chart
const monthlyData = @json($monthlyData);
const memoCtx = document.getElementById('memoChart').getContext('2d');
const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
new Chart(memoCtx, {
    type: 'bar',
    data: {
        labels: monthlyData.map(d => months[d.month-1] + ' ' + d.year),
        datasets: [
            { label:'Total', data: monthlyData.map(d=>d.total), backgroundColor:'rgba(0,103,71,0.85)', borderRadius:5, borderSkipped:false },
            { label:'Selesai', data: monthlyData.map(d=>d.done), backgroundColor:'rgba(0,103,71,0.2)', borderRadius:5, borderSkipped:false }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { display:false } },
        scales: {
            y: { grid:{color:'#f3f4f6'}, ticks:{font:{size:11}}, beginAtZero:true },
            x: { grid:{display:false}, ticks:{font:{size:11}} }
        }
    }
});

// Frequency donut chart
const freqCtx = document.getElementById('freqChart').getContext('2d');
new Chart(freqCtx, {
    type: 'doughnut',
    data: {
        labels: ['Daily', 'Weekly', 'Monthly'],
        datasets: [{
            data: [{{ $jobStats['daily'] }}, {{ $jobStats['weekly'] }}, {{ $jobStats['monthly'] }}],
            backgroundColor: ['#fb923c','#a78bfa','#818cf8'],
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        responsive: false,
        cutout: '65%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
