{{-- Reusable form partial for create & edit --}}
@php $isEdit = isset($jobSchedule); @endphp
@php $j = $jobSchedule ?? null; @endphp

<div class="space-y-5">

    {{-- Basic Info --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Informasi Job</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Divisi <span class="text-red-500">*</span></label>
                <input type="text" name="division" value="{{ old('division', $j?->division) }}" required class="form-input" placeholder="CMG, RMG, SME...">
                @error('division')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Nama Job <span class="text-red-500">*</span></label>
                <input type="text" name="job_name" value="{{ old('job_name', $j?->job_name) }}" required class="form-input" placeholder="Nama job/proses...">
                @error('job_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Request Data / Subject Email <span class="text-red-500">*</span></label>
            <input type="text" name="request_subject" value="{{ old('request_subject', $j?->request_subject) }}" required class="form-input" placeholder="Subjek email atau deskripsi request data...">
            @error('request_subject')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Jenis Pengiriman <span class="text-red-500">*</span></label>
                <select name="delivery_type" required class="form-input">
                    @foreach(['CSV','PDF','DOCS','TXT','ODT'] as $t)
                        <option value="{{ $t }}" {{ old('delivery_type', $j?->delivery_type ?? 'Email') == $t ? 'selected':'' }}>{{ $t }}</option>
                    @endforeach
                </select>
                @error('delivery_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="form-input">
                    @foreach(['Active','Inactive','Pending'] as $s)
                        <option value="{{ $s }}" {{ old('status', $j?->status ?? 'Active') == $s ? 'selected':'' }}>{{ $s }}</option>
                    @endforeach
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Email PIC</label>
                <input type="text" name="email_pic" value="{{ old('email_pic', $j?->email_pic) }}" class="form-input" placeholder="email@perusahaan.com">
                @error('email_pic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">CC / REQ</label>
                <input type="text" name="cc_req" value="{{ old('cc_req', $j?->cc_req) }}" class="form-input" placeholder="cc@perusahaan.com; ...">
                @error('cc_req')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Name File / Table</label>
                <input type="text" name="file_table_name" value="{{ old('file_table_name', $j?->file_table_name) }}" class="form-input mono" placeholder="nama_file.xlsx atau schema.table_name">
                @error('file_table_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">PIC DTM</label>
                <input type="text" name="pic_dtm" value="{{ old('pic_dtm', $j?->pic_dtm) }}" class="form-input" placeholder="Nama PIC DTM">
                @error('pic_dtm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    {{-- Schedule Config --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Konfigurasi Schedule</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Schedule</label>
                <input type="text" name="schedule" value="{{ old('schedule', $j?->schedule) }}" class="form-input mono" placeholder="0 8 * * 1  atau  Daily">
                <p class="text-xs text-gray-400 mt-1">Cron expression atau deskripsi</p>
                @error('schedule')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Jam</label>
                <input type="time" name="jam" value="{{ old('jam', $j?->jam ? \Carbon\Carbon::parse($j->jam)->format('H:i') : '') }}" class="form-input mono">
                @error('jam')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Day</label>
                <input type="text" name="day" value="{{ old('day', $j?->day) }}" class="form-input" placeholder="Senin, 1, Last Day...">
                @error('day')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        {{-- Frequency Checkboxes --}}
        <div>
            <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-3 block">Frekuensi Pengiriman</label>
            <div class="flex items-center gap-6">
                {{-- Daily --}}
                <label class="flex items-center gap-3 cursor-pointer group" x-data>
                    <div class="relative">
                        <input type="checkbox" name="is_daily" value="1"
                               {{ old('is_daily', $j?->is_daily) ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-orange-400 rounded-full transition-all peer-focus:ring-2 peer-focus:ring-orange-300"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700 peer-checked:text-orange-600 group-has-[:checked]:text-orange-600">Daily</p>
                        <p class="text-xs text-gray-400">Setiap hari</p>
                    </div>
                </label>

                {{-- Weekly --}}
                <label class="flex items-center gap-3 cursor-pointer group" x-data>
                    <div class="relative">
                        <input type="checkbox" name="is_weekly" value="1"
                               {{ old('is_weekly', $j?->is_weekly) ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-purple-400 rounded-full transition-all peer-focus:ring-2 peer-focus:ring-purple-300"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Weekly</p>
                        <p class="text-xs text-gray-400">Setiap minggu</p>
                    </div>
                </label>

                {{-- Monthly --}}
                <label class="flex items-center gap-3 cursor-pointer group" x-data>
                    <div class="relative">
                        <input type="checkbox" name="is_monthly" value="1"
                               {{ old('is_monthly', $j?->is_monthly) ? 'checked' : '' }}
                               class="peer sr-only">
                        <div class="w-11 h-6 bg-gray-200 peer-checked:bg-indigo-400 rounded-full transition-all peer-focus:ring-2 peer-focus:ring-indigo-300"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-all peer-checked:translate-x-5"></div>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-700">Monthly</p>
                        <p class="text-xs text-gray-400">Setiap bulan</p>
                    </div>
                </label>
            </div>
        </div>
    </div>

    {{-- Query --}}
    <div class="bg-white rounded-xl border border-gray-100 p-6">
        <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50 mb-4">Query (Opsional)</h3>
        <div class="relative">
            <div class="flex items-center justify-between mb-2">
                <label class="text-xs font-semibold text-gray-500">SQL / Query</label>
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded mono">SQL</span>
            </div>
            <textarea name="query" rows="6"
                      class="form-input resize-y font-mono text-xs bg-gray-900 text-green-400 border-gray-700 rounded-xl"
                      style="font-family:'JetBrains Mono',monospace; line-height:1.7; color:#a8ff78; background:#0f1117;"
                      placeholder="SELECT * FROM table_name WHERE ...">{{ old('query', $j?->query) }}</textarea>
            @error('query')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

</div>
