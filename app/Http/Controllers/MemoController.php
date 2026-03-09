<?php

namespace App\Http\Controllers;

use App\Models\MemoRequest;
use App\Models\MemoActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MemoController extends Controller
{
    public function index(Request $request)
    {
        $query = MemoRequest::with('creator');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('memo_number', 'like', "%{$search}%")
                  ->orWhere('request_description', 'like', "%{$search}%")
                  ->orWhere('division', 'like', "%{$search}%")
                  ->orWhere('pic_dtm', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('category')) $query->where('category', $request->category);
        if ($request->filled('date_from')) $query->where('received_date', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->where('received_date', '<=', $request->date_to);

        $sortBy  = in_array($request->get('sort_by'), ['memo_number','received_date','status','division','pic_dtm','created_at'])
                   ? $request->get('sort_by') : 'received_date';
        $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $memos = $query->paginate(10)->withQueryString();
        return view('memo.index', compact('memos'));
    }

    public function create()
    {
        return view('memo.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'memo_number'          => 'required|string|unique:memo_requests,memo_number|max:100',
            'request_description'  => 'required|string',
            'category'             => 'required|in:Quarterly,Monthly,Ad-Hoc',
            'division'             => 'required|string|max:100',
            'pic_dtm'              => 'required|string|max:100',
            'received_date'        => 'required|date',
            'status'               => 'required|in:Pending,On Progress,Done,Discard',
            'submitted_date'       => 'nullable|date',
            'handover_memo_number' => 'nullable|string|max:100',
            'notes'                => 'nullable|string',
            'attachment'           => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx',
        ]);

        $validated['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('memo-attachments', 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
            $validated['attachment_mime'] = $file->getMimeType();
        }

        unset($validated['attachment']);
        $memo = MemoRequest::create($validated);

        MemoActivity::create([
            'memo_request_id' => $memo->id,
            'user_id'         => Auth::id(),
            'action'          => 'created',
            'description'     => 'Memo request dibuat' . ($memo->attachment_name ? ' dengan lampiran: ' . $memo->attachment_name : ''),
            'new_status'      => $memo->status,
        ]);

        return redirect()->route('memo.index')->with('success', 'Memo request berhasil dibuat!');
    }

    public function show(MemoRequest $memo)
    {
        $memo->load('creator', 'activities.user');
        return view('memo.show', compact('memo'));
    }

    public function edit(MemoRequest $memo)
    {
        return view('memo.edit', compact('memo'));
    }

    public function update(Request $request, MemoRequest $memo)
    {
        $validated = $request->validate([
            'memo_number'          => 'required|string|unique:memo_requests,memo_number,' . $memo->id . '|max:100',
            'request_description'  => 'required|string',
            'category'             => 'required|in:Quarterly,Monthly,Ad-Hoc',
            'division'             => 'required|string|max:100',
            'pic_dtm'              => 'required|string|max:100',
            'received_date'        => 'required|date',
            'status'               => 'required|in:Pending,On Progress,Done,Discard',
            'submitted_date'       => 'nullable|date',
            'handover_memo_number' => 'nullable|string|max:100',
            'notes'                => 'nullable|string',
            'attachment'           => 'nullable|file|max:10240|mimes:pdf,jpg,jpeg,png,gif,webp,doc,docx,xls,xlsx',
            'remove_attachment'    => 'nullable|boolean',
        ]);

        $oldStatus = $memo->status;

        // Remove attachment if requested
        if ($request->boolean('remove_attachment') && $memo->attachment_path) {
            Storage::disk('public')->delete($memo->attachment_path);
            $validated['attachment_path'] = null;
            $validated['attachment_name'] = null;
            $validated['attachment_mime'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('attachment')) {
            // Delete old file
            if ($memo->attachment_path) {
                Storage::disk('public')->delete($memo->attachment_path);
            }
            $file = $request->file('attachment');
            $path = $file->store('memo-attachments', 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $file->getClientOriginalName();
            $validated['attachment_mime'] = $file->getMimeType();
        }

        unset($validated['attachment'], $validated['remove_attachment']);
        $memo->update($validated);

        if ($oldStatus !== $validated['status']) {
            MemoActivity::create([
                'memo_request_id' => $memo->id,
                'user_id'         => Auth::id(),
                'action'          => 'status_changed',
                'description'     => "Status diubah dari {$oldStatus} ke {$validated['status']}",
                'old_status'      => $oldStatus,
                'new_status'      => $validated['status'],
            ]);
        } else {
            MemoActivity::create([
                'memo_request_id' => $memo->id,
                'user_id'         => Auth::id(),
                'action'          => 'updated',
                'description'     => 'Memo request diperbarui',
            ]);
        }

        return redirect()->route('memo.index')->with('success', 'Memo request berhasil diperbarui!');
    }

    public function destroy(MemoRequest $memo)
    {
        if ($memo->attachment_path) {
            Storage::disk('public')->delete($memo->attachment_path);
        }
        $memo->delete();
        return redirect()->route('memo.index')->with('success', 'Memo request berhasil dihapus!');
    }

    public function downloadAttachment(MemoRequest $memo)
    {
        if (!$memo->hasAttachment()) abort(404);
        return Storage::disk('public')->download($memo->attachment_path, $memo->attachment_name);
    }

    public function export(Request $request)
    {
        $query = MemoRequest::with('creator');
        if ($request->filled('status'))    $query->where('status', $request->status);
        if ($request->filled('category'))  $query->where('category', $request->category);
        if ($request->filled('date_from')) $query->where('received_date', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->where('received_date', '<=', $request->date_to);

        $memos = $query->orderBy('received_date', 'desc')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="memo_requests_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($memos) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['No','Nomor Memo','Deskripsi','Kategori','Divisi','PIC DTM','Tgl Diterima','Status','Tgl Diserahkan','No Memo Penyerahan','Keterangan','Lampiran']);
            foreach ($memos as $i => $memo) {
                fputcsv($file, [
                    $i + 1,
                    $memo->memo_number,
                    $memo->request_description,
                    $memo->category,
                    $memo->division,
                    $memo->pic_dtm,
                    $memo->received_date?->format('d/m/Y'),
                    $memo->status,
                    $memo->submitted_date?->format('d/m/Y'),
                    $memo->handover_memo_number,
                    $memo->notes,
                    $memo->attachment_name ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
