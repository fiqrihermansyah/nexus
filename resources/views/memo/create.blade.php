@extends('layouts.app')
@section('title', 'Tambah Memo')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('memo.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Tambah Memo Request</h1>
            <p class="text-sm text-gray-400">Isi form di bawah untuk membuat permintaan memo baru</p>
        </div>
    </div>

    <form method="POST" action="{{ route('memo.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf

        {{-- Informasi Memo --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Informasi Memo</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Nomor Memo Request <span class="text-red-500">*</span></label>
                    <input type="text" name="memo_number" value="{{ old('memo_number') }}" required class="form-input mono" placeholder="001/M/CMG/I/2026">
                    @error('memo_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="form-input">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Quarterly','Monthly','Ad-Hoc'] as $c)
                            <option value="{{ $c }}" {{ old('category') == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Deskripsi Permintaan Data <span class="text-red-500">*</span></label>
                <textarea name="request_description" rows="3" required class="form-input resize-none" placeholder="Jelaskan permintaan data secara detail...">{{ old('request_description') }}</textarea>
                @error('request_description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">User / Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="division" value="{{ old('division') }}" required class="form-input" placeholder="CMG, RMG, dll...">
                    @error('division')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">PIC DTM <span class="text-red-500">*</span></label>
                    <input type="text" name="pic_dtm" value="{{ old('pic_dtm') }}" required class="form-input" placeholder="Nama PIC...">
                    @error('pic_dtm')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Tanggal Diterima <span class="text-red-500">*</span></label>
                    <input type="date" name="received_date" value="{{ old('received_date', date('Y-m-d')) }}" required class="form-input">
                    @error('received_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Status</label>
                    <select name="status" class="form-input">
                        @foreach(['Pending','On Progress','Done','Discard'] as $s)
                            <option value="{{ $s }}" {{ old('status','Pending') == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Lampiran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4" x-data="fileUpload()">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Lampiran File Memo</h3>

            <!-- Drop Zone -->
            <div class="relative"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="handleDrop($event)">

                <label for="attachment"
                       :class="dragging ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 bg-gray-50 hover:border-emerald-300 hover:bg-emerald-50/30'"
                       class="flex flex-col items-center justify-center gap-3 border-2 border-dashed rounded-xl p-8 cursor-pointer transition-all">

                    <template x-if="!previewUrl && !fileName">
                        <div class="flex flex-col items-center gap-2 text-center">
                            <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#006747" stroke-width="1.8">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Klik untuk upload atau drag & drop</p>
                                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG, DOC, XLSX — Maks. 10MB</p>
                            </div>
                        </div>
                    </template>

                    <!-- Image Preview -->
                    <template x-if="previewUrl && isImage">
                        <div class="flex flex-col items-center gap-3 w-full">
                            <img :src="previewUrl" class="max-h-48 rounded-lg object-contain shadow-sm border border-gray-100">
                            <p class="text-xs text-gray-500" x-text="fileName"></p>
                        </div>
                    </template>

                    <!-- File Preview (non-image) -->
                    <template x-if="fileName && !isImage">
                        <div class="flex items-center gap-4 w-full px-2">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                 :class="isPdf ? 'bg-red-50' : 'bg-blue-50'">
                                <svg x-show="isPdf" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                <svg x-show="!isPdf" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.8"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="fileName"></p>
                                <p class="text-xs text-gray-400" x-text="fileSize"></p>
                            </div>
                        </div>
                    </template>

                    <input type="file" id="attachment" name="attachment" class="hidden"
                           accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.doc,.docx,.xls,.xlsx"
                           @change="handleFile($event)">
                </label>

                <!-- Remove button -->
                <button type="button" x-show="fileName"
                        @click.prevent="clearFile()"
                        class="absolute top-3 right-3 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-200 transition-all shadow-sm">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            @error('attachment')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
        </div>

        {{-- Penyerahan --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 pb-3 border-b border-gray-50">Informasi Penyerahan (Opsional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Tanggal Diserahkan</label>
                    <input type="date" name="submitted_date" value="{{ old('submitted_date') }}" class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Nomor Memo Penyerahan</label>
                    <input type="text" name="handover_memo_number" value="{{ old('handover_memo_number') }}" class="form-input mono">
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Keterangan</label>
                <textarea name="notes" rows="3" class="form-input resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-1">
            <a href="{{ route('memo.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Simpan Memo
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function fileUpload() {
    return {
        dragging: false,
        fileName: '',
        fileSize: '',
        previewUrl: null,
        isImage: false,
        isPdf: false,

        handleFile(event) {
            const file = event.target.files[0];
            if (file) this.processFile(file);
        },

        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (!file) return;
            // Inject into file input
            const dt = new DataTransfer();
            dt.items.add(file);
            document.getElementById('attachment').files = dt.files;
            this.processFile(file);
        },

        processFile(file) {
            this.fileName = file.name;
            this.fileSize = this.formatSize(file.size);
            this.isImage = file.type.startsWith('image/');
            this.isPdf   = file.type === 'application/pdf';

            if (this.isImage) {
                const reader = new FileReader();
                reader.onload = (e) => { this.previewUrl = e.target.result; };
                reader.readAsDataURL(file);
            } else {
                this.previewUrl = null;
            }
        },

        clearFile() {
            this.fileName = '';
            this.fileSize = '';
            this.previewUrl = null;
            this.isImage = false;
            this.isPdf = false;
            document.getElementById('attachment').value = '';
        },

        formatSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }
    }
}
</script>
@endpush
