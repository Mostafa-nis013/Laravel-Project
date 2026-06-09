@extends('layouts.admin')

@section('title', 'Activity Log')
@section('topbar-title', 'Activity Log')
@section('breadcrumb')
    <span class="crumb-current">Activity Log</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Activity Log</h2>
        <p>A full audit trail of every action taken in the admin panel.</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.activity') }}">
    <div class="filters-bar">
        <input type="text" name="search" placeholder="Search descriptions…" value="{{ request('search') }}" style="flex:2">
        <select name="user_id">
            <option value="">All Users</option>
            @foreach($users as $u)
                <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
            @endforeach
        </select>
        <select name="action">
            <option value="">All Actions</option>
            @foreach(['created','updated','deleted','restored','login','logout','status'] as $act)
                <option value="{{ $act }}" {{ request('action') == $act ? 'selected' : '' }}>{{ ucfirst($act) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary">Filter</button>
        @if(request()->hasAny(['search','user_id','action']))
            <a href="{{ route('admin.activity') }}" class="btn btn-secondary">Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Description</th>
                    <th>User</th>
                    <th>Resource</th>
                    <th>IP</th>
                    <th>When</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                @php
                    $actionColors = [
                        'created'  => 'badge-green',
                        'updated'  => 'badge-blue',
                        'deleted'  => 'badge-red',
                        'restored' => 'badge-yellow',
                        'login'    => 'badge-purple',
                        'logout'   => 'badge-gray',
                        'status'   => 'badge-blue',
                    ];
                    $cls = $actionColors[$log->action] ?? 'badge-gray';
                @endphp
                <tr>
                    <td><span class="badge {{ $cls }}">{{ $log->action }}</span></td>
                    <td style="max-width:320px">
                        <div style="font-size:0.875rem;color:var(--text)">{{ $log->description }}</div>
                    </td>
                    <td>
                        @if($log->user)
                        <div style="display:flex;align-items:center;gap:8px">
                            <div style="width:26px;height:26px;border-radius:50%;background:var(--surface2);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:700;color:var(--accent);flex-shrink:0">
                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                            </div>
                            <span style="font-size:0.82rem">{{ $log->user->name }}</span>
                        </div>
                        @else
                            <span style="color:var(--muted);font-size:0.82rem">System</span>
                        @endif
                    </td>
                    <td style="color:var(--muted);font-size:0.8rem">
                        @if($log->model_type)
                            <span style="font-weight:500;color:var(--text)">{{ class_basename($log->model_type) }}</span>
                            @if($log->model_label)
                                <br><span>{{ Str::limit($log->model_label, 24) }}</span>
                            @endif
                        @else
                            —
                        @endif
                    </td>
                    <td style="font-family:monospace;font-size:0.78rem;color:var(--muted)">{{ $log->ip_address ?? '—' }}</td>
                    <td style="white-space:nowrap">
                        <div style="font-size:0.82rem;color:var(--text)">{{ $log->created_at->format('M d, Y') }}</div>
                        <div style="font-size:0.75rem;color:var(--muted)">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No activity recorded yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="pagination">
        @if($logs->onFirstPage())
            <span class="page-link" style="opacity:0.3">←</span>
        @else
            <a href="{{ $logs->previousPageUrl() }}" class="page-link">←</a>
        @endif
        @foreach($logs->getUrlRange(max(1,$logs->currentPage()-2), min($logs->lastPage(),$logs->currentPage()+2)) as $page => $url)
            <a href="{{ $url }}" class="page-link {{ $page == $logs->currentPage() ? 'active' : '' }}">{{ $page }}</a>
        @endforeach
        @if($logs->hasMorePages())
            <a href="{{ $logs->nextPageUrl() }}" class="page-link">→</a>
        @else
            <span class="page-link" style="opacity:0.3">→</span>
        @endif
        <span style="margin-left:auto;color:var(--muted);font-size:0.85rem">
            {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}
        </span>
    </div>
    @endif
</div>
@endsection
