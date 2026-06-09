@extends('layouts.admin')

@section('title', 'Settings')
@section('topbar-title', 'Settings')
@section('breadcrumb')
    <span class="crumb-current">Settings</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Settings</h2>
        <p>Configure your store preferences and behaviour.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf @method('PUT')

    {{-- Tab bar --}}
    <div data-tabs>
        <div class="tab-bar">
            <button type="button" class="tab-btn active" data-tab="tab-general">⊞ General</button>
            <button type="button" class="tab-btn"        data-tab="tab-store">◈ Store</button>
            <button type="button" class="tab-btn"        data-tab="tab-email">✉ Email</button>
            <button type="button" class="tab-btn"        data-tab="tab-appearance">◉ Appearance</button>
        </div>

        {{-- General --}}
        <div id="tab-general" class="tab-panel active">
            <div class="grid-2" style="align-items:start">
                <div class="card">
                    <div class="card-header"><span class="card-title">Store Identity</span></div>
                    <div class="card-body">
                        @foreach($settings->get('general', collect()) as $setting)
                        <div class="form-group">
                            <label>{{ $setting->label }}</label>
                            <input type="text" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}">
                            @if($setting->description)
                                <div class="form-hint">{{ $setting->description }}</div>
                            @endif
                            @error($setting->key)<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">About</span></div>
                    <div class="card-body" style="color:var(--muted);font-size:0.875rem;line-height:1.7">
                        <p style="margin-bottom:14px">These settings control your store's identity across invoices, emails, and the storefront header.</p>
                        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:14px">
                            <div style="font-size:0.72rem;letter-spacing:1.5px;text-transform:uppercase;font-weight:700;color:var(--muted);margin-bottom:10px">Current values</div>
                            @foreach($settings->get('general', collect()) as $s)
                                <div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--border);font-size:0.82rem">
                                    <span style="color:var(--muted)">{{ $s->label }}</span>
                                    <span style="color:var(--text);font-weight:500">{{ $s->value ?: '—' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Store --}}
        <div id="tab-store" class="tab-panel">
            <div class="grid-2" style="align-items:start">
                <div class="card">
                    <div class="card-header"><span class="card-title">Shipping &amp; Pricing</span></div>
                    <div class="card-body">
                        @foreach($settings->get('store', collect()) as $setting)
                            @if($setting->type === 'boolean')
                                {{-- handled in toggles card --}}
                            @else
                            <div class="form-group">
                                <label>{{ $setting->label }}</label>
                                <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}">
                                @if($setting->description)
                                    <div class="form-hint">{{ $setting->description }}</div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">Store Toggles</span></div>
                    <div class="card-body" style="padding:4px 22px">
                        @foreach($settings->get('store', collect()) as $setting)
                            @if($setting->type === 'boolean')
                            <div class="toggle-wrap">
                                <div>
                                    <div class="toggle-label">{{ $setting->label }}</div>
                                    @if($setting->description)
                                        <div class="toggle-hint">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="{{ $setting->key }}" {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Email --}}
        <div id="tab-email" class="tab-panel">
            <div class="grid-2" style="align-items:start">
                <div class="card">
                    <div class="card-header"><span class="card-title">Mail Configuration</span></div>
                    <div class="card-body">
                        @foreach($settings->get('email', collect()) as $setting)
                            @if($setting->type !== 'boolean')
                            <div class="form-group">
                                <label>{{ $setting->label }}</label>
                                <input type="{{ str_contains($setting->key, 'address') ? 'email' : 'text' }}" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}">
                                @if($setting->description)
                                    <div class="form-hint">{{ $setting->description }}</div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">Email Notifications</span></div>
                    <div class="card-body" style="padding:4px 22px">
                        @foreach($settings->get('email', collect()) as $setting)
                            @if($setting->type === 'boolean')
                            <div class="toggle-wrap">
                                <div>
                                    <div class="toggle-label">{{ $setting->label }}</div>
                                    @if($setting->description)
                                        <div class="toggle-hint">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="{{ $setting->key }}" {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Appearance --}}
        <div id="tab-appearance" class="tab-panel">
            <div class="grid-2" style="align-items:start">
                <div class="card">
                    <div class="card-header"><span class="card-title">Display Settings</span></div>
                    <div class="card-body">
                        @foreach($settings->get('appearance', collect()) as $setting)
                            @if($setting->type !== 'boolean')
                            <div class="form-group">
                                <label>{{ $setting->label }}</label>
                                <input type="{{ $setting->type === 'integer' ? 'number' : 'text' }}" name="{{ $setting->key }}" value="{{ old($setting->key, $setting->value) }}">
                                @if($setting->description)
                                    <div class="form-hint">{{ $setting->description }}</div>
                                @endif
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="card">
                    <div class="card-header"><span class="card-title">Storefront Toggles</span></div>
                    <div class="card-body" style="padding:4px 22px">
                        @foreach($settings->get('appearance', collect()) as $setting)
                            @if($setting->type === 'boolean')
                            <div class="toggle-wrap">
                                <div>
                                    <div class="toggle-label">{{ $setting->label }}</div>
                                    @if($setting->description)
                                        <div class="toggle-hint">{{ $setting->description }}</div>
                                    @endif
                                </div>
                                <label class="toggle">
                                    <input type="checkbox" name="{{ $setting->key }}" {{ old($setting->key, $setting->value) == '1' ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- /data-tabs --}}

    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px">
        <button type="reset" class="btn btn-secondary">Reset Changes</button>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>
@endsection
