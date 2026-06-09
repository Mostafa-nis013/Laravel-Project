@extends('layouts.admin')

@section('title', 'My Profile')
@section('topbar-title', 'Profile')
@section('breadcrumb')
    <span class="crumb-current">My Profile</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>My Profile</h2>
        <p>Manage your account details and password.</p>
    </div>
</div>

<div class="grid-2" style="align-items:start;gap:20px">

    {{-- Left column --}}
    <div style="display:flex;flex-direction:column;gap:20px">

        {{-- Avatar + identity card --}}
        <div class="card">
            <div class="card-body" style="display:flex;align-items:center;gap:18px;padding:24px">
                <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,rgba(232,200,122,0.25),rgba(232,200,122,0.08));border:2px solid rgba(232,200,122,0.3);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:700;color:var(--accent);flex-shrink:0;font-family:'DM Serif Display',serif">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div style="font-size:1.1rem;font-weight:600;margin-bottom:4px">{{ $user->name }}</div>
                    <div style="color:var(--muted);font-size:0.875rem;margin-bottom:8px">{{ $user->email }}</div>
                    <div style="display:flex;gap:6px;flex-wrap:wrap">
                        @foreach($user->roles as $role)
                        @php
                            $cls = match($role->name) {
                                'admin'     => 'badge-yellow',
                                'editor'    => 'badge-blue',
                                'moderator' => 'badge-purple',
                                default     => 'badge-green',
                            };
                        @endphp
                        <span class="badge {{ $cls }}">{{ $role->label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            <div style="border-top:1px solid var(--border)">
                <table style="width:100%">
                    <tr>
                        <td style="padding:11px 20px;color:var(--muted);font-size:0.83rem;border-bottom:1px solid var(--border)">Member since</td>
                        <td style="padding:11px 20px;text-align:right;font-size:0.83rem;border-bottom:1px solid var(--border)">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                    <tr>
                        <td style="padding:11px 20px;color:var(--muted);font-size:0.83rem;border-bottom:1px solid var(--border)">Status</td>
                        <td style="padding:11px 20px;text-align:right;border-bottom:1px solid var(--border)">
                            <span class="badge {{ $user->is_active ? 'badge-green' : 'badge-red' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:11px 20px;color:var(--muted);font-size:0.83rem">Last updated</td>
                        <td style="padding:11px 20px;text-align:right;font-size:0.83rem;color:var(--muted)">{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Update profile --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Update Profile</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div style="display:flex;justify-content:flex-end">
                        <button type="submit" class="btn btn-primary">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change password --}}
        <div class="card">
            <div class="card-header"><span class="card-title">Change Password</span></div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Current Password <span class="required">*</span></label>
                        <input type="password" name="current_password" required>
                        @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>New Password <span class="required">*</span></label>
                        <input type="password" name="password" id="new-pw" required oninput="checkPwStrength(this.value)">
                        <div class="pw-bars" style="display:flex;gap:4px;margin-top:7px">
                            <div class="pw-bar" id="pb1" style="height:3px;flex:1;border-radius:2px;background:var(--border);transition:background 0.2s"></div>
                            <div class="pw-bar" id="pb2" style="height:3px;flex:1;border-radius:2px;background:var(--border);transition:background 0.2s"></div>
                            <div class="pw-bar" id="pb3" style="height:3px;flex:1;border-radius:2px;background:var(--border);transition:background 0.2s"></div>
                            <div class="pw-bar" id="pb4" style="height:3px;flex:1;border-radius:2px;background:var(--border);transition:background 0.2s"></div>
                        </div>
                        <div id="pw-label" class="form-hint">Enter new password</div>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password <span class="required">*</span></label>
                        <input type="password" name="password_confirmation" required>
                    </div>
                    <div style="display:flex;justify-content:flex-end">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Right: recent activity --}}
    <div class="card">
        <div class="card-header">
            <span class="card-title">My Recent Activity</span>
            <a href="{{ route('admin.activity', ['user_id' => $user->id]) }}" class="btn btn-secondary btn-sm">Full log</a>
        </div>

        @if($recentActivity->isEmpty())
        <div style="padding:40px;text-align:center;color:var(--muted);font-size:0.875rem">No activity recorded yet.</div>
        @else
        <div style="padding:0">
            @foreach($recentActivity as $log)
            @php
                $iconMap = [
                    'created'  => ['icon' => '+', 'bg' => 'rgba(82,192,122,0.12)',  'color' => 'var(--success)'],
                    'updated'  => ['icon' => '✎', 'bg' => 'rgba(82,130,224,0.12)', 'color' => 'var(--info)'],
                    'deleted'  => ['icon' => '✕', 'bg' => 'rgba(224,82,82,0.12)',  'color' => 'var(--danger)'],
                    'restored' => ['icon' => '↩', 'bg' => 'rgba(232,200,122,0.12)','color' => 'var(--accent)'],
                    'login'    => ['icon' => '→', 'bg' => 'rgba(150,100,220,0.12)','color' => '#a878e8'],
                    'logout'   => ['icon' => '←', 'bg' => 'rgba(122,120,128,0.12)','color' => 'var(--muted)'],
                    'status'   => ['icon' => '◷', 'bg' => 'rgba(82,130,224,0.12)', 'color' => 'var(--info)'],
                ];
                $icon = $iconMap[$log->action] ?? ['icon' => '·', 'bg' => 'var(--surface2)', 'color' => 'var(--muted)'];
            @endphp
            <div style="display:flex;align-items:flex-start;gap:12px;padding:14px 20px;border-bottom:1px solid var(--border)">
                <div style="width:32px;height:32px;border-radius:50%;background:{{ $icon['bg'] }};color:{{ $icon['color'] }};display:flex;align-items:center;justify-content:center;font-size:0.85rem;font-weight:700;flex-shrink:0;margin-top:1px">
                    {{ $icon['icon'] }}
                </div>
                <div style="flex:1;min-width:0">
                    <div style="font-size:0.875rem;color:var(--text);line-height:1.4">{{ $log->description }}</div>
                    @if($log->model_label)
                        <div style="font-size:0.78rem;color:var(--muted);margin-top:2px">{{ class_basename($log->model_type ?? '') }}: {{ $log->model_label }}</div>
                    @endif
                    <div style="font-size:0.75rem;color:var(--muted);margin-top:4px">{{ $log->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge badge-{{ $log->action_color }}" style="flex-shrink:0;font-size:0.68rem">{{ $log->action }}</span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
function checkPwStrength(pw) {
    const bars   = [1,2,3,4].map(i => document.getElementById('pb' + i));
    const label  = document.getElementById('pw-label');
    const colors = ['#e05252','#e0a452','#5282e0','#52c07a'];
    const labels = ['Too weak','Fair','Good','Strong'];

    let score = 0;
    if (pw.length >= 8)             score++;
    if (/[A-Z]/.test(pw))          score++;
    if (/[0-9]/.test(pw))          score++;
    if (/[^a-zA-Z0-9]/.test(pw))  score++;

    bars.forEach((b, i) => b.style.background = i < score ? colors[score - 1] : 'var(--border)');
    label.textContent  = pw.length ? labels[score - 1] || 'Too weak' : 'Enter new password';
    label.style.color  = score > 0 ? colors[score - 1] : 'var(--muted)';
}
</script>
@endpush
