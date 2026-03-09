<?php

namespace App\Http\Controllers;

use App\Models\JobSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = JobSchedule::with('creator');

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('job_name', 'like', "%{$s}%")
                  ->orWhere('division', 'like', "%{$s}%")
                  ->orWhere('request_subject', 'like', "%{$s}%")
                  ->orWhere('pic_dtm', 'like', "%{$s}%")
                  ->orWhere('email_pic', 'like', "%{$s}%");
            });
        }

        if ($request->filled('status'))        $query->where('status', $request->status);
        if ($request->filled('division'))      $query->where('division', $request->division);
        if ($request->filled('delivery_type')) $query->where('delivery_type', $request->delivery_type);

        if ($request->filled('frequency')) {
            match($request->frequency) {
                'daily'   => $query->where('is_daily', true),
                'weekly'  => $query->where('is_weekly', true),
                'monthly' => $query->where('is_monthly', true),
                default   => null,
            };
        }

        $sortBy  = in_array($request->get('sort_by'), ['job_name','division','status','created_at'])
                   ? $request->get('sort_by') : 'created_at';
        $sortDir = $request->get('sort_dir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $jobs      = $query->paginate(15)->withQueryString();
        $divisions = JobSchedule::distinct()->pluck('division')->filter()->sort()->values();

        return view('job-schedule.index', compact('jobs', 'divisions'));
    }

    public function create()
    {
        return view('job-schedule.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'division'        => 'required|string|max:100',
            'job_name'        => 'required|string|max:200',
            'request_subject' => 'required|string|max:255',
            'delivery_type'   => 'required|in:CSV,PDF,DOCX,TXT,ODT',
            'email_pic'       => 'nullable|string|max:255',
            'cc_req'          => 'nullable|string|max:255',
            'file_table_name' => 'nullable|string|max:255',
            'schedule'        => 'nullable|string|max:100',
            'jam'             => 'nullable|date_format:H:i',
            'day'             => 'nullable|string|max:100',
            'pic_dtm'         => 'nullable|string|max:100',
            'query'           => 'nullable|string',
            'status'          => 'required|in:Active,Inactive,Pending',
            'is_monthly'      => 'nullable|boolean',
            'is_weekly'       => 'nullable|boolean',
            'is_daily'        => 'nullable|boolean',
        ]);

        $validated['is_monthly']  = $request->boolean('is_monthly');
        $validated['is_weekly']   = $request->boolean('is_weekly');
        $validated['is_daily']    = $request->boolean('is_daily');
        $validated['created_by']  = Auth::id();

        JobSchedule::create($validated);

        return redirect()->route('job-schedule.index')
            ->with('success', 'Job schedule berhasil ditambahkan!');
    }

    public function show(JobSchedule $jobSchedule)
    {
        return view('job-schedule.show', compact('jobSchedule'));
    }

    public function edit(JobSchedule $jobSchedule)
    {
        return view('job-schedule.edit', compact('jobSchedule'));
    }

    public function update(Request $request, JobSchedule $jobSchedule)
    {
        $validated = $request->validate([
            'division'        => 'required|string|max:100',
            'job_name'        => 'required|string|max:200',
            'request_subject' => 'required|string|max:255',
            'delivery_type'   => 'required|in:CSV,PDF,DOCX,TXT,ODT',
            'email_pic'       => 'nullable|string|max:255',
            'cc_req'          => 'nullable|string|max:255',
            'file_table_name' => 'nullable|string|max:255',
            'schedule'        => 'nullable|string|max:100',
            'jam'             => 'nullable|date_format:H:i',
            'day'             => 'nullable|string|max:100',
            'pic_dtm'         => 'nullable|string|max:100',
            'query'           => 'nullable|string',
            'status'          => 'required|in:Active,Inactive,Pending',
            'is_monthly'      => 'nullable|boolean',
            'is_weekly'       => 'nullable|boolean',
            'is_daily'        => 'nullable|boolean',
        ]);

        $validated['is_monthly'] = $request->boolean('is_monthly');
        $validated['is_weekly']  = $request->boolean('is_weekly');
        $validated['is_daily']   = $request->boolean('is_daily');

        $jobSchedule->update($validated);

        return redirect()->route('job-schedule.index')
            ->with('success', 'Job schedule berhasil diperbarui!');
    }

    public function destroy(JobSchedule $jobSchedule)
    {
        $jobSchedule->delete();
        return redirect()->route('job-schedule.index')
            ->with('success', 'Job schedule berhasil dihapus!');
    }

    public function export(Request $request)
    {
        $query = JobSchedule::with('creator');
        if ($request->filled('status'))        $query->where('status', $request->status);
        if ($request->filled('division'))      $query->where('division', $request->division);
        if ($request->filled('delivery_type')) $query->where('delivery_type', $request->delivery_type);

        $jobs = $query->orderBy('division')->orderBy('job_name')->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="job_schedules_' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($jobs) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['No','Divisi','Nama Job','Request/Subject','Jenis Pengiriman','Email PIC','CC/REQ','File/Table','Schedule','Jam','Day','PIC DTM','Status','Monthly','Weekly','Daily']);
            foreach ($jobs as $i => $j) {
                fputcsv($f, [
                    $i + 1, $j->division, $j->job_name, $j->request_subject,
                    $j->delivery_type, $j->email_pic, $j->cc_req, $j->file_table_name,
                    $j->schedule, $j->jam, $j->day, $j->pic_dtm, $j->status,
                    $j->is_monthly ? '✓' : '', $j->is_weekly ? '✓' : '', $j->is_daily ? '✓' : '',
                ]);
            }
            fclose($f);
        };

        return response()->stream($callback, 200, $headers);
    }
}
