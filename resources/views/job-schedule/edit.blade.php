@extends('layouts.app')
@section('title', 'Edit Job Schedule')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6 flex items-center gap-3">
        <a href="{{ route('job-schedule.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5"/><polyline points="12 19 5 12 12 5"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900">Edit Job Schedule</h1>
            <p class="text-sm text-gray-400">{{ $jobSchedule->job_name }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('job-schedule.update', $jobSchedule) }}">
        @csrf @method('PUT')

        @include('job-schedule.partials.form')

        <div class="flex items-center justify-end gap-3 pt-5">
            <a href="{{ route('job-schedule.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary flex items-center gap-2">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Perbarui Job
            </button>
        </div>
    </form>
</div>
@endsection
