@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<div class="admin-header">
    <h1>Settings</h1>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST">
    @csrf
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
        {{-- Store Settings --}}
        <div class="card">
            <div class="card-header"><h3 style="font-size:1rem;">Store Settings</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="store_name" class="form-control" value="{{ $settings['store_name'] }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Email</label>
                    <input type="email" name="store_email" class="form-control" value="{{ $settings['store_email'] }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Phone</label>
                    <input type="text" name="store_phone" class="form-control" value="{{ $settings['store_phone'] }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Store Address</label>
                    <textarea name="store_address" class="form-control" rows="2">{{ $settings['store_address'] }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" value="{{ $settings['currency_symbol'] }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Currency Code</label>
                        <input type="text" name="currency_code" class="form-control" value="{{ $settings['currency_code'] }}">
                    </div>
                </div>
            </div>
        </div>

        <div>
            {{-- Payment Gateway --}}
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Payment Gateway</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Active Gateway</label>
                        <select name="active_payment_gateway" class="form-control">
                            <option value="paypal" selected>PayPal (Default)</option>
                        </select>

                        <p class="text-muted mt-1" style="font-size:0.8rem;">API keys are configured in the .env file</p>
                    </div>
                </div>
            </div>

            {{-- Shopify Status --}}
            <div class="card mb-4">
                <div class="card-header"><h3 style="font-size:1rem;">Shopify Connection</h3></div>
                <div class="card-body">
                    @if($settings['shopify_store_url'])
                        <div class="d-flex align-center gap-2 mb-3">
                            <span class="badge badge-success">Connected</span>
                            <span class="text-muted" style="font-size:0.85rem;">{{ $settings['shopify_store_url'] }}</span>
                        </div>
                    @else
                        <div class="d-flex align-center gap-2 mb-3">
                            <span class="badge badge-warning">Not Configured</span>
                        </div>
                    @endif
                    <p class="text-muted" style="font-size:0.8rem;">Shopify credentials are configured in the .env file:<br>
                        <code>SHOPIFY_API_KEY</code>, <code>SHOPIFY_API_SECRET</code>, <code>SHOPIFY_ACCESS_TOKEN</code>, <code>SHOPIFY_STORE_URL</code>
                    </p>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg w-100">Save Settings</button>
        </div>
    </div>
</form>
@endsection



