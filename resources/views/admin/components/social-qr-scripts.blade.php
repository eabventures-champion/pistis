{{-- Include Local QRCode.js with CDN fallback --}}
<script src="{{ asset('js/qrcode.min.js') }}"></script>
<script>
if (typeof QRCode === 'undefined') {
    document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"><\/script>');
}
</script>

<script>
(function() {
    // Registry of QR Code instances by prefix
    window.socialQrInstances = window.socialQrInstances || {};
    window.socialQrCurrentUrls = window.socialQrCurrentUrls || {};

    window.initSocialQr = function(prefix, targetUrl) {
        const box = document.getElementById(prefix + '_qrcode_box');
        if (!box) return;

        box.innerHTML = '';
        window.socialQrCurrentUrls[prefix] = targetUrl;

        try {
            window.socialQrInstances[prefix] = new QRCode(box, {
                text: targetUrl,
                width: 180,
                height: 180,
                colorDark: "#09090b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        } catch (e) {
            console.error('Error generating QR code:', e);
        }
    };

    window.setQrMode = function(prefix, mode, url) {
        window.socialQrCurrentUrls[prefix] = url;

        // Update URL display
        const display = document.getElementById(prefix + '_url_display');
        if (display) {
            display.textContent = url;
            display.title = url;
        }

        // Update Preview link
        const previewLink = document.getElementById(prefix + '_preview_link');
        if (previewLink) {
            previewLink.href = url;
        }

        // Update mode button styles
        const card = document.getElementById(prefix + '_qr_card');
        if (card) {
            const buttons = card.querySelectorAll('.' + prefix + '_mode_btn');
            buttons.forEach(btn => {
                btn.style.background = '#ffffff';
                btn.style.color = '#334155';
                btn.style.borderColor = '#cbd5e1';
            });
            if (event && event.currentTarget) {
                event.currentTarget.style.background = '#0f172a';
                event.currentTarget.style.color = '#ffffff';
                event.currentTarget.style.borderColor = '#0f172a';
            }
        }

        // Re-generate QR
        const box = document.getElementById(prefix + '_qrcode_box');
        if (box) {
            box.innerHTML = '';
            window.socialQrInstances[prefix] = new QRCode(box, {
                text: url,
                width: 180,
                height: 180,
                colorDark: "#09090b",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    };

    window.downloadQrCodeImage = function(prefix, baseName) {
        const box = document.getElementById(prefix + '_qrcode_box');
        if (!box) return;

        const sourceCanvas = box.querySelector('canvas');
        const sourceImg = box.querySelector('img');

        // Create high-res 1200x1200px print-ready canvas
        const exportCanvas = document.createElement('canvas');
        const exportSize = 1200;
        exportCanvas.width = exportSize;
        exportCanvas.height = exportSize;
        const ctx = exportCanvas.getContext('2d');

        // Fill background white
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, exportSize, exportSize);

        const drawImageOntoCanvas = (imgSource) => {
            // Add padding of 80px
            const padding = 80;
            const qrDrawSize = exportSize - (padding * 2);
            
            // Disable image smoothing for ultra-crisp vector-like QR dots
            ctx.imageSmoothingEnabled = false;
            ctx.drawImage(imgSource, padding, padding, qrDrawSize, qrDrawSize);

            // Trigger download
            const link = document.createElement('a');
            link.download = (baseName || 'Pistis-Social-QR') + '-HighRes.png';
            link.href = exportCanvas.toDataURL('image/png');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };

        if (sourceCanvas) {
            drawImageOntoCanvas(sourceCanvas);
        } else if (sourceImg && sourceImg.src) {
            const tempImg = new Image();
            tempImg.crossOrigin = 'anonymous';
            tempImg.onload = function() {
                drawImageOntoCanvas(tempImg);
            };
            tempImg.src = sourceImg.src;
        } else {
            alert('QR code image is still generating. Please try again in a moment.');
        }
    };

    window.copyTargetUrl = function(prefix) {
        const url = window.socialQrCurrentUrls[prefix] || '{{ route("social.connect") }}';
        const label = document.getElementById(prefix + '_copy_label');

        const doCopy = () => {
            if (label) {
                const oldText = label.textContent;
                label.textContent = 'Copied!';
                setTimeout(() => {
                    label.textContent = oldText;
                }, 2000);
            }
        };

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(doCopy).catch(() => {
                prompt('Copy link below:', url);
            });
        } else {
            prompt('Copy link below:', url);
        }
    };

    window.printEventStandee = function(prefix) {
        const url = window.socialQrCurrentUrls[prefix] || '{{ route("social.connect") }}';
        const box = document.getElementById(prefix + '_qrcode_box');
        if (!box) return;

        let qrDataUrl = '';
        const sourceCanvas = box.querySelector('canvas');
        const sourceImg = box.querySelector('img');

        if (sourceCanvas) {
            qrDataUrl = sourceCanvas.toDataURL('image/png');
        } else if (sourceImg && sourceImg.src) {
            qrDataUrl = sourceImg.src;
        }

        const handle = document.getElementById(prefix + '_display_handle')?.textContent.trim() || '@wearpistis';
        const store = 'PISTIS';

        const printWin = window.open('', '_blank', 'width=850,height=1000');
        if (!printWin) {
            alert('Please allow popups to open the printable event standee.');
            return;
        }

        printWin.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Pistis Collections — Event Social Display Standee</title>
                <link rel="preconnect" href="https://fonts.googleapis.com">
                <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
                <style>
                    @page {
                        size: A5 portrait;
                        margin: 15mm;
                    }
                    * { box-sizing: border-box; margin:0; padding:0; }
                    body {
                        font-family: 'Plus Jakarta Sans', sans-serif;
                        background: #ffffff;
                        color: #09090b;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        min-height: 100vh;
                        padding: 20px;
                        -webkit-print-color-adjust: exact;
                        print-color-adjust: exact;
                    }
                    .standee-card {
                        width: 100%;
                        max-width: 480px;
                        border: 2px solid #09090b;
                        border-radius: 20px;
                        padding: 40px 32px 32px 32px;
                        text-align: center;
                        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
                        position: relative;
                        background: #ffffff;
                    }
                    .standee-card::after {
                        content: '';
                        position: absolute;
                        top: 8px; left: 8px; right: 8px; bottom: 8px;
                        border: 1px solid #d4af37;
                        border-radius: 14px;
                        pointer-events: none;
                    }
                    .brand-crown {
                        font-size: 1.5rem;
                        color: #d4af37;
                        margin-bottom: 8px;
                    }
                    .brand-name {
                        font-family: 'Cinzel', serif;
                        font-size: 2rem;
                        font-weight: 800;
                        letter-spacing: 0.22em;
                        color: #09090b;
                        text-transform: uppercase;
                        margin-bottom: 4px;
                    }
                    .brand-sub {
                        font-size: 0.76rem;
                        font-weight: 700;
                        letter-spacing: 0.16em;
                        text-transform: uppercase;
                        color: #71717a;
                        margin-bottom: 24px;
                    }
                    .qr-wrapper {
                        background: #ffffff;
                        border: 2px solid #09090b;
                        border-radius: 16px;
                        padding: 18px;
                        display: inline-block;
                        margin-bottom: 20px;
                        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
                    }
                    .qr-wrapper img {
                        width: 210px;
                        height: 210px;
                        display: block;
                    }
                    .cta-heading {
                        font-family: 'Cinzel', serif;
                        font-size: 1.15rem;
                        font-weight: 700;
                        letter-spacing: 0.12em;
                        text-transform: uppercase;
                        color: #09090b;
                        margin-bottom: 6px;
                    }
                    .cta-sub {
                        font-size: 0.82rem;
                        color: #52525b;
                        max-width: 320px;
                        margin: 0 auto 18px auto;
                        line-height: 1.45;
                    }
                    .handles-pill-row {
                        display: flex;
                        justify-content: center;
                        gap: 10px;
                        flex-wrap: wrap;
                        margin-bottom: 24px;
                    }
                    .handle-badge {
                        background: #f4f4f5;
                        border: 1px solid #e4e4e7;
                        border-radius: 999px;
                        padding: 6px 14px;
                        font-size: 0.8rem;
                        font-weight: 700;
                        color: #18181b;
                    }
                    .standee-footer {
                        border-top: 1px solid #e4e4e7;
                        padding-top: 14px;
                        font-size: 0.72rem;
                        color: #a1a1aa;
                        letter-spacing: 0.08em;
                        text-transform: uppercase;
                    }
                    .print-controls {
                        position: fixed;
                        top: 20px;
                        right: 20px;
                        display: flex;
                        gap: 10px;
                        z-index: 100;
                    }
                    .print-btn {
                        background: #09090b;
                        color: #ffffff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        font-size: 0.85rem;
                        font-weight: 700;
                        cursor: pointer;
                        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                    }
                    @media print {
                        .print-controls { display: none !important; }
                        body { padding: 0; min-height: auto; }
                        .standee-card { box-shadow: none; border-width: 2px; }
                    }
                </style>
            </head>
            <body>
                <div class="print-controls">
                    <button class="print-btn" onclick="window.print()">Print Standee Card</button>
                    <button class="print-btn" style="background:#71717a;" onclick="window.close()">Close</button>
                </div>

                <div class="standee-card">
                    <div class="brand-crown">✦</div>
                    <h1 class="brand-name">${store}</h1>
                    <div class="brand-sub">Official Atelier & Runway Archive</div>

                    <div class="qr-wrapper">
                        <img src="${qrDataUrl}" alt="Pistis Connect QR Code">
                    </div>

                    <h2 class="cta-heading">Scan to Connect & Follow</h2>
                    <p class="cta-sub">Point your mobile camera to access our Instagram drops, TikTok motion archives & concierge liaison.</p>

                    <div class="handles-pill-row">
                        <span class="handle-badge">Instagram: ${handle}</span>
                        <span class="handle-badge">TikTok: ${handle}</span>
                    </div>

                    <div class="standee-footer">
                        Official Capsule Releases • www.pistiscollections.com
                    </div>
                </div>

                <script>
                    window.onload = function() {
                        setTimeout(function() {
                            window.print();
                        }, 400);
                    };
                <\/script>
            </body>
            </html>
        `);
        printWin.document.close();
    };
})();
</script>
