@extends('layouts.admin')

@section('title', $user->name)
@section('topbar-title', 'Users / Detail')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->email }}</p>
    </div>
    <div style="display:flex;gap:10px">
        <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit User</a>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="grid-2" style="align-items:start">

    {{-- Profile card --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card">
            <div class="card-body" style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:32px">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--surface2);border:2px solid var(--border);display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:600;color:var(--accent);margin-bottom:16px">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h3 style="font-size:1.2rem;font-weight:600;margin-bottom:4px">{{ $user->name }}</h3>
                <p style="color:var(--muted);font-size:0.9rem;margin-bottom:16px">{{ $user->email }}</p>

                <div style="display:flex;gap:6px;flex-wrap:wrap;justify-content:center">
                    @foreach($user->roles as $role)
                        @php
                            $cls = match($role->name) {
                                'admin'     => 'badge-yellow',
                                'editor'    => 'badge-blue',
                                'moderator' => 'badge-purple',
                                default     => 'badge-green',
                            };
                        @endphp
                        <span class="badge {{ $cls }}" style="font-size:0.8rem;padding:5px 12px">{{ $role->label }}</span>
                    @endforeach
                </div>
            </div>

            <div style="border-top:1px solid var(--border)">
                <table style="width:100%">
                    <tr>
                        <td style="padding:12px 20px;color:var(--muted);font-size:0.875rem;border-bottom:1px solid var(--border)">Status</td>
                        <td style="padding:12px 20px;text-align:right;border-bottom:1px solid var(--border)">
                            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;color:var(--muted);font-size:0.875rem;border-bottom:1px solid var(--border)">Joined</td>
                        <td style="padding:12px 20px;text-align:right;color:var(--muted);font-size:0.875rem;border-bottom:1px solid var(--border)">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:12px 20px;color:var(--muted);font-size:0.875rem">Last updated</td>
                        <td style="padding:12px 20px;text-align:right;color:var(--muted);font-size:0.875rem">{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Roles detail + permissions --}}
    <div style="display:flex;flex-direction:column;gap:20px">
        <div class="card">
            <div class="card-header"><span class="card-title">Assigned Roles</span></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:10px">
                @forelse($user->roles as $role)
                @php
                    $colors = [
                        'admin'     => ['border' => 'rgba(232,200,122,0.3)', 'text' => '#e8c87a', 'bg' => 'rgba(232,200,122,0.06)'],
                        'editor'    => ['border' => 'rgba(82,130,224,0.3)',  'text' => '#7aabf0', 'bg' => 'rgba(82,130,224,0.06)'],
                        'moderator' => ['border' => 'rgba(150,100,220,0.3)','text' => '#c090f0', 'bg' => 'rgba(150,100,220,0.06)'],
                        'user'      => ['border' => 'rgba(82,192,122,0.3)', 'text' => '#7ad4a0', 'bg' => 'rgba(82,192,122,0.06)'],
                    ];
                    $c = $colors[$role->name] ?? ['border'=>'var(--border)','text'=>'var(--text)','bg'=>'var(--surface2)'];
                @endphp
                <div style="border:1px solid {{ $c['border'] }};background:{{ $c['bg'] }};border-radius:var(--radius);padding:14px 16px">
                    <div style="font-weight:600;color:{{ $c['text'] }};margin-bottom:4px">{{ $role->label }}</div>
                    <div style="font-size:0.82rem;color:var(--muted)">{{ $role->description }}</div>
                </div>
                @empty
                <p style="color:var(--muted);font-size:0.9rem">No roles assigned.</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="card-header"><span class="card-title">Access Level</span></div>
            <div class="card-body">
                @php
                    $perms = [
                        ['label' => 'View Admin Panel',     'allowed' => $user->hasRole(['admin','editor','moderator'])],
                        ['label' => 'Manage Products',      'allowed' => $user->hasRole(['admin','editor'])],
                        ['label' => 'Manage Categories',    'allowed' => $user->hasRole(['admin','editor'])],
                        ['label' => 'Manage Orders',        'allowed' => $user->hasRole(['admin','editor','moderator'])],
                        ['label' => 'Manage Users',         'allowed' => $user->isAdmin()],
                        ['label' => 'Storefront Access',    'allowed' => true],
                    ];
                @endphp
                <div style="display:flex;flex-direction:column;gap:0">
                    @foreach($perms as $i => $perm)
                    <div style="display:flex;align-items:center;justify-content:space-between;padding:11px 0;{{ $i < count($perms)-1 ? 'border-bottom:1px solid var(--border)' : '' }}">
                        <span style="font-size:0.875rem;color:var(--muted)">{{ $perm['label'] }}</span>
                        @if($perm['allowed'])
                            <span style="color:var(--success);font-size:1rem">✓</span>
                        @else
                            <span style="color:var(--border);font-size:1rem">✕</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if($user->id !== auth()->id())
        <div class="card" style="border-color:rgba(224,82,82,0.3)">
            <div class="card-header"><span class="card-title" style="color:var(--danger)">Danger Zone</span></div>
            <div class="card-body">
                <p style="font-size:0.875rem;color:var(--muted);margin-bottom:14px">This will permanently delete the user account and all associated role assignments.</p>
                <form method="POST" action="{{ route('users.destroy', $user) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger" data-confirm="Permanently delete '{{ $user->name }}'?">Delete User</button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => { if (!confirm(el.dataset.confirm)) e.preventDefault(); });
});
</script>
@endpush
