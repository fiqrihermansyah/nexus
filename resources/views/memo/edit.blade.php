@extends('layouts.app')
@section('title', 'Edit Memo')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('memo.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Memo Request</h1>
            <p class="text-sm text-gray-400 mono">{{ $memo->memo_number }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('memo.update', $memo) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')

        {{-- Informasi Memo --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Informasi Memo</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Nomor Memo Request <span class="text-red-500">*</span></label>
                    <input type="text" name="memo_number" value="{{ old('memo_number', $memo->memo_number) }}" required class="form-input mono">
                    @error('memo_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Kategori <span class="text-red-500">*</span></label>
                    <select name="category" required class="form-input">
                        @foreach(['Quarterly','Monthly','Ad-Hoc'] as $c)
                            <option value="{{ $c }}" {{ old('category',$memo->category) == $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Deskripsi Permintaan <span class="text-red-500">*</span></label>
                <textarea name="request_description" rows="3" required class="form-input resize-none">{{ old('request_description',$memo->request_description) }}</textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="division" value="{{ old('division',$memo->division) }}" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">PIC DTM <span class="text-red-500">*</span></label>
                    <input type="text" name="pic_dtm" value="{{ old('pic_dtm',$memo->pic_dtm) }}" required class="form-input">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Tanggal Diterima <span class="text-red-500">*</span></label>
                    <input type="date" name="received_date" value="{{ old('received_date',$memo->received_date?->format('Y-m-d')) }}" required class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Status</label>
                    <select name="status" class="form-input">
                        @foreach(['Pending','On Progress','Done','Discard'] as $s)
                            <option value="{{ $s }}" {{ old('status',$memo->status) == $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Lampiran --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4"
             x-data="fileUploadEdit({{ $memo->hasAttachment() ? 'true' : 'false' }}, '{{ $memo->attachment_name }}', '{{ $memo->attachment_mime }}', '{{ $memo->hasAttachment() ? Storage::disk('public')->url($memo->attachment_path) : '' }}')">

            <h3 class="text-xs font-semibold uppercase tracking-widest text-emerald-700 pb-3 border-b border-gray-50">Lampiran File Memo</h3>

            <!-- Existing attachment -->
            <template x-if="hasExisting && !removeExisting && !newFileName">
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <!-- icon -->
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                         :class="existingIsImage ? 'bg-emerald-50' : existingIsPdf ? 'bg-red-50' : 'bg-blue-50'">
                        <template x-if="existingIsImage">
                            <img :src="existingUrl" class="w-10 h-10 rounded-lg object-cover">
                        </template>
                        <template x-if="!existingIsImage && existingIsPdf">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </template>
                        <template x-if="!existingIsImage && !existingIsPdf">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        </template>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-800 truncate" x-text="existingName"></p>
                        <p class="text-xs text-gray-400">File lampiran saat ini</p>
                    </div>
                    <button type="button" @click="removeExisting = true"
                            class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        Hapus
                    </button>
                </div>
            </template>

            <!-- Remove confirmation -->
            <template x-if="removeExisting && !newFileName">
                <div class="p-3 bg-red-50 rounded-xl border border-red-100 flex items-center gap-3">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <p class="text-xs text-red-600 flex-1">Lampiran akan dihapus saat disimpan</p>
                    <button type="button" @click="removeExisting = false" class="text-xs text-gray-500 hover:text-gray-700 underline">Batalkan</button>
                </div>
            </template>

            <input type="hidden" name="remove_attachment" :value="removeExisting && !newFileName ? '1' : '0'">

            <!-- Upload new -->
            <div class="relative"
                 @dragover.prevent="dragging = true"
                 @dragleave.prevent="dragging = false"
                 @drop.prevent="handleDrop($event)">

                <label for="attachment_edit"
                       :class="dragging ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 bg-gray-50 hover:border-emerald-300'"
                       class="flex flex-col items-center justify-center gap-3 border-2 border-dashed rounded-xl p-6 cursor-pointer transition-all">

                    <template x-if="!newFileName">
                        <div class="flex flex-col items-center gap-2 text-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.8"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <p class="text-xs text-gray-500">
                                <span x-text="hasExisting && !removeExisting ? 'Upload file baru untuk mengganti lampiran' : 'Upload lampiran baru'"></span>
                            </p>
                            <p class="text-xs text-gray-400">PDF, JPG, PNG, DOC, XLSX — Maks. 10MB</p>
                        </div>
                    </template>

                    <template x-if="newFileName && newIsImage">
                        <div class="flex flex-col items-center gap-2 w-full">
                            <img :src="newPreview" class="max-h-40 rounded-lg object-contain border border-gray-100">
                            <p class="text-xs text-gray-500" x-text="newFileName"></p>
                        </div>
                    </template>

                    <template x-if="newFileName && !newIsImage">
                        <div class="flex items-center gap-3 w-full px-2">
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                 :class="newIsPdf ? 'bg-red-50' : 'bg-blue-50'">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" :stroke="newIsPdf ? '#ef4444' : '#3b82f6'" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate" x-text="newFileName"></p>
                                <p class="text-xs text-gray-400" x-text="newFileSize"></p>
                            </div>
                        </div>
                    </template>

                    <input type="file" id="attachment_edit" name="attachment" class="hidden"
                           accept=".pdf,.jpg,.jpeg,.png,.gif,.webp,.doc,.docx,.xls,.xlsx"
                           @change="handleFile($event)">
                </label>

                <button type="button" x-show="newFileName" @click.prevent="clearNew()"
                        class="absolute top-3 right-3 w-7 h-7 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 shadow-sm">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            @error('attachment')<p class="text-red-500 text-xs">{{ $message }}</p>@enderror
        </div>

        {{-- Penyerahan --}}
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-5">
            <h3 class="text-xs font-semibold uppercase tracking-widest text-gray-400 pb-3 border-b border-gray-50">Informasi Penyerahan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Tanggal Diserahkan</label>
                    <input type="date" name="submitted_date" value="{{ old('submitted_date',$memo->submitted_date?->format('Y-m-d')) }}" class="form-input">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Nomor Memo Penyerahan</label>
                    <input type="text" name="handover_memo_number" value="{{ old('handover_memo_number',$memo->handover_memo_number) }}" class="form-input mono">
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1.5 block">Keterangan</label>
                <textarea name="notes" rows="3" class="form-input resize-none">{{ old('notes',$memo->notes) }}</textarea>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-1">
            <a href="{{ route('memo.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Perbarui Memo
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function fileUploadEdit(hasExisting, existingName, existingMime, existingUrl) {
    return {
        hasExisting, existingName, existingMime, existingUrl,
        removeExisting: false,
        dragging: false,
        newFileName: '', newFileSize: '', newPreview: null, newIsImage: false, newIsPdf: false,

        get existingIsImage() { return this.existingMime && this.existingMime.startsWith('image/'); },
        get existingIsPdf()   { return this.existingMime === 'application/pdf'; },

        handleFile(e) { const f = e.target.files[0]; if (f) this.processNew(f); },
        handleDrop(e) {
            this.dragging = false;
            const f = e.dataTransfer.files[0]; if (!f) return;
            const dt = new DataTransfer(); dt.items.add(f);
            document.getElementById('attachment_edit').files = dt.files;
            this.processNew(f);
        },
        processNew(file) {
            this.newFileName = file.name;
            this.newFileSize = this.fmt(file.size);
            this.newIsImage  = file.type.startsWith('image/');
            this.newIsPdf    = file.type === 'application/pdf';
            if (this.newIsImage) {
                const r = new FileReader();
                r.onload = e => { this.newPreview = e.target.result; };
                r.readAsDataURL(file);
            } else { this.newPreview = null; }
        },
        clearNew() {
            this.newFileName = ''; this.newFileSize = ''; this.newPreview = null;
            this.newIsImage = false; this.newIsPdf = false;
            document.getElementById('attachment_edit').value = '';
        },
        fmt(b) {
            if (b < 1024) return b + ' B';
            if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
            return (b/1048576).toFixed(1) + ' MB';
        }
    }
}
</script>
@endpush
