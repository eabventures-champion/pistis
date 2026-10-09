@extends('layouts.admin')

@section('title', 'Send Lookbook Campaign')

@section('content')
<div class="admin-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
    <div>
        <div style="margin-bottom:6px;">
            <a href="{{ route('admin.subscribers.index') }}" class="text-muted" style="font-size:0.8rem;text-decoration:none;">
                ← Back to Inner Circle
            </a>
        </div>
        <h1 style="margin:0 0 6px 0;letter-spacing:0.04em;">COMPOSE EDITORIAL LOOKBOOK CAMPAIGN</h1>
        <p class="text-muted" style="font-size:0.85rem;margin:0;">
            Draft and dispatch private capsule previews and lookbooks directly to your enrolled subscribers.
        </p>
    </div>
</div>

@if(session('success'))
    <div style="background:#dcfce7;color:#15803d;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #bbf7d0;">
        ✓ {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div style="background:#fee2e2;color:#b91c1c;padding:12px 18px;border-radius:6px;margin-bottom:20px;font-size:0.85rem;border:1px solid #fecaca;">
        <ul style="margin:0;padding-left:18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">
    {{-- Form Column --}}
    <div class="card" style="padding:24px;">
        <form action="{{ route('admin.subscribers.campaign.send') }}" method="POST" id="campaign-form">
            @csrf
            <input type="hidden" name="mode" id="campaign-mode" value="test">

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                    Email Subject Line <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="subject" class="form-control" 
                       value="{{ old('subject', 'Pistis Archive — Autumn / Winter 2026 Private Lookbook') }}" 
                       placeholder="e.g. Pistis Archive — Autumn / Winter 2026 Private Lookbook" required
                       style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                    Inbox Preheader Preview Text
                </label>
                <input type="text" name="preheader" class="form-control" 
                       value="{{ old('preheader', 'Your private preview of our unreleased editorial collection is now ready.') }}" 
                       placeholder="Short teaser shown in Gmail / Apple Mail before opening"
                       style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                    Lookbook Headline <span style="color:#ef4444;">*</span>
                </label>
                <input type="text" name="headline" class="form-control" 
                       value="{{ old('headline', 'THE ART OF SIMPLICITY · PRIVATE CAPSULE') }}" 
                       placeholder="e.g. THE ART OF SIMPLICITY · PRIVATE CAPSULE" required
                       style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                    Optional Banner Image URL
                </label>
                <input type="url" name="banner_url" class="form-control" 
                       value="{{ old('banner_url') }}" 
                       placeholder="https://pistiscollections.com.au/storage/hero/... (or leave blank)"
                       style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                    Editorial Message Body <span style="color:#ef4444;">*</span>
                </label>
                <textarea name="content" rows="8" class="form-control" required
                          style="width:100%;padding:12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;line-height:1.6;font-family:inherit;">{{ old('content', "We are pleased to unveil our latest collection to the Pistis Inner Circle.\n\nCrafted from heavyweight organic textiles and precision tailoring, this release embraces architectural silhouettes designed for modern permanence.\n\nAs an enrolled member, you have exclusive early access to shop this capsule before public availability.") }}</textarea>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:28px;">
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                        Button Call to Action Text
                    </label>
                    <input type="text" name="cta_text" class="form-control" 
                           value="{{ old('cta_text', 'Explore Private Capsule →') }}"
                           style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
                </div>
                <div>
                    <label style="display:block;font-size:0.75rem;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;color:#3f3f46;margin-bottom:6px;">
                        Button Destination URL
                    </label>
                    <input type="text" name="cta_url" class="form-control" 
                           value="{{ old('cta_url', url('/shop')) }}"
                           style="width:100%;padding:10px 12px;font-size:0.9rem;border:1px solid #d4d4d8;border-radius:6px;">
                </div>
            </div>

            <hr style="border:none;border-top:1px solid #e4e4e7;margin:24px 0;">

            {{-- Dispatch Actions --}}
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="email" name="test_email" id="test-email-input" 
                           value="{{ old('test_email', $adminEmail) }}" 
                           placeholder="admin@pistis.com.au"
                           style="padding:8px 12px;font-size:0.85rem;border:1px solid #d4d4d8;border-radius:6px;width:220px;">
                    <button type="button" onclick="submitTestCampaign()" class="btn btn-secondary" style="font-size:0.8rem;white-space:nowrap;">
                        ✉️ Send Test Preview
                    </button>
                </div>

                <div>
                    <button type="button" onclick="submitLiveCampaign()" class="btn btn-primary" style="font-size:0.85rem;padding:10px 22px;">
                        🚀 Dispatch To All ({{ $activeCount }} Subscribers)
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Audience & Info Column --}}
    <div>
        <div class="card" style="padding:20px;margin-bottom:20px;">
            <h4 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 12px 0;color:#71717a;">
                Campaign Target Audience
            </h4>
            <div style="font-size:2rem;font-weight:800;color:#15803d;margin-bottom:4px;">
                {{ number_format($activeCount) }}
            </div>
            <div class="text-muted" style="font-size:0.8rem;line-height:1.5;">
                Active subscribers eligible to receive this lookbook release.
            </div>
        </div>

        <div class="card" style="padding:20px;background:#fafafa;">
            <h4 style="font-size:0.8rem;text-transform:uppercase;letter-spacing:0.06em;margin:0 0 10px 0;color:#18181b;">
                💡 Delivery Guidelines
            </h4>
            <ul style="margin:0;padding-left:16px;font-size:0.78rem;color:#525252;line-height:1.6;">
                <li style="margin-bottom:6px;"><strong>Test First:</strong> Always click <em>Send Test Preview</em> to check how the email renders in your inbox before broadcasting.</li>
                <li style="margin-bottom:6px;"><strong>Editorial Tone:</strong> Keep lookbook text elevated, concise, and focused on design inspirations, cuts, and craftsmanship.</li>
                <li><strong>Safe Delivery:</strong> Emails are delivered directly using your configured store mailer.</li>
            </ul>
        </div>
    </div>
</div>

<script>
function submitTestCampaign() {
    const testInput = document.getElementById('test-email-input');
    if (!testInput.value.trim()) {
        alert('Please enter an email address for the test preview.');
        testInput.focus();
        return;
    }
    document.getElementById('campaign-mode').value = 'test';
    document.getElementById('campaign-form').submit();
}

function submitLiveCampaign() {
    const count = {{ $activeCount }};
    if (count <= 0) {
        alert('You have 0 active subscribers. You cannot broadcast to an empty list.');
        return;
    }

    if (confirm(`Are you sure you want to DISPATCH this Lookbook campaign to all ${count} active subscribers?`)) {
        document.getElementById('campaign-mode').value = 'broadcast';
        document.getElementById('campaign-form').submit();
    }
}
</script>
@endsection
