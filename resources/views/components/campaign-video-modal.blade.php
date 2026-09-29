@if(!empty($campaignVideo['enabled']) && !empty($campaignVideo['video_url']))
    @php
        $isHomePage = request()->routeIs('home');
        $shouldShowOnCurrentPage = ($campaignVideo['target_page'] === 'all') || ($campaignVideo['target_page'] === 'homepage' && $isHomePage);
    @endphp

    @if($shouldShowOnCurrentPage)
        {{-- Luxury Editorial Campaign Video Modal --}}
        <div id="pistisCampaignModal" class="campaign-video-backdrop" role="dialog" aria-modal="true" aria-labelledby="campaignVideoHeading" style="display:none;">
            <div id="campaignVideoDialog" class="campaign-video-dialog">
                
                {{-- Top Editorial Bar --}}
                <div class="campaign-modal-header">
                    <div class="campaign-header-meta">
                        <span class="campaign-badge-tag">{{ $campaignVideo['badge'] ?: 'PISTIS EDITORIAL · RUNWAY PREMIERE' }}</span>
                        <h2 id="campaignVideoHeading" class="campaign-editorial-title">{{ $campaignVideo['title'] ?: 'THE NEW SILHOUETTE IN MOTION' }}</h2>
                    </div>
                    <button type="button" class="campaign-close-btn" onclick="closeCampaignVideoModal()" aria-label="Close campaign video">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                {{-- Cinematic Video Player Container (Adaptive & Uncropped) --}}
                <div class="campaign-video-frame">
                    <video id="pistisIntroVideo" 
                           src="{{ $campaignVideo['video_url'] }}" 
                           playsinline 
                           loop 
                           muted 
                           preload="auto"
                           class="campaign-video-element"
                           style="display:block; margin:0 auto; max-width:100%; max-height:100%; object-fit:contain;">
                    </video>

                    {{-- Floating In-Video Luxury Audio & Playback Controls --}}
                    <div class="campaign-video-controls-overlay">
                        {{-- Unmute / Sound Pill --}}
                        <button type="button" id="campaignAudioToggleBtn" class="campaign-audio-pill" onclick="toggleCampaignVideoAudio()">
                            <span class="audio-equalizer-bars">
                                <span></span><span></span><span></span>
                            </span>
                            <span id="campaignAudioText" class="audio-text-label">UNMUTE SOUND</span>
                        </button>

                        {{-- Play/Pause Pill --}}
                        <button type="button" id="campaignPlayPauseBtn" class="campaign-play-pill" onclick="toggleCampaignVideoPlayback()" aria-label="Play or Pause video">
                            <svg id="playIconSvg" style="display:none;" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <polygon points="5 3 19 12 5 21 5 3"></polygon>
                            </svg>
                            <svg id="pauseIconSvg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="6" y="4" width="4" height="16"></rect>
                                <rect x="14" y="4" width="4" height="16"></rect>
                            </svg>
                        </button>
                    </div>

                    {{-- Slim Timeline Progress Bar --}}
                    <div class="campaign-progress-bar-wrap">
                        <div id="campaignProgressBarFill" class="campaign-progress-bar-fill"></div>
                    </div>
                </div>

                {{-- Bottom Editorial Details & Call To Action --}}
                <div class="campaign-modal-footer">
                    <div class="campaign-footer-text">
                        @if(!empty($campaignVideo['description']))
                            <p class="campaign-desc">{{ $campaignVideo['description'] }}</p>
                        @else
                            <p class="campaign-desc">Experience the fluid drape, textured wools, and tailored monochrome tailoring of our latest collection.</p>
                        @endif
                    </div>
                    <div class="campaign-footer-actions">
                        <button type="button" class="campaign-continue-link" onclick="closeCampaignVideoModal()">
                            Continue Browsing
                        </button>
                        <a href="{{ $campaignVideo['cta_url'] ?: route('shop.index') }}" class="campaign-cta-btn">
                            <span>{{ $campaignVideo['cta_text'] ?: 'SHOP THE COLLECTION' }}</span>
                            <span class="cta-arrow">→</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        {{-- Floating Campaign Film Trigger (Allows re-watching at any time) --}}
        <button type="button" id="pistisFloatingCampaignBtn" class="floating-campaign-film-btn" onclick="openCampaignVideoModal(true)" aria-label="Watch Runway Campaign Film">
            <span class="film-icon">🎬</span>
            <span class="film-label">Campaign Film</span>
        </button>

        {{-- Styles for Campaign Video Modal --}}
        <style>
            .campaign-video-backdrop {
                position: fixed;
                inset: 0;
                z-index: 999999;
                background: rgba(0, 0, 0, 0.90);
                backdrop-filter: blur(24px) saturate(180%);
                -webkit-backdrop-filter: blur(24px) saturate(180%);
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 16px;
                box-sizing: border-box;
                opacity: 0;
                visibility: hidden;
                overflow-y: auto;
                transition: opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.4s ease;
            }

            .campaign-video-backdrop.is-active {
                opacity: 1;
                visibility: visible;
            }

            /* Adaptive Dialog: Wraps around video without stretching or cropping */
            .campaign-video-dialog {
                width: auto;
                min-width: 320px;
                max-width: min(860px, 94vw);
                max-height: min(90vh, 780px);
                background: #000000;
                border: 1px solid rgba(255, 255, 255, 0.16);
                border-radius: 14px;
                overflow: hidden;
                box-shadow: 0 30px 100px -10px rgba(0, 0, 0, 0.95), 0 0 0 1px rgba(255, 255, 255, 0.08);
                transform: scale(0.96) translateY(8px);
                transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), max-width 0.3s ease;
                color: #ffffff;
                display: flex;
                flex-direction: column;
                margin: auto;
                box-sizing: border-box;
            }

            /* Dedicated styling when vertical/portrait video (9:16 Reel/Lookbook) is loaded */
            .campaign-video-dialog.is-portrait {
                max-width: min(440px, 92vw);
            }

            .campaign-video-backdrop.is-active .campaign-video-dialog {
                transform: scale(1) translateY(0);
            }

            /* Header */
            .campaign-modal-header {
                flex-shrink: 0;
                padding: 14px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                background: linear-gradient(180deg, #141414 0%, #000000 100%);
                border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            }

            .campaign-header-meta {
                overflow: hidden;
                padding-right: 12px;
            }

            .campaign-badge-tag {
                font-size: 0.68rem;
                letter-spacing: 0.2em;
                text-transform: uppercase;
                color: #a3a3a3;
                font-weight: 600;
                display: block;
                margin-bottom: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .campaign-editorial-title {
                margin: 0;
                font-family: 'Cormorant Garamond', Georgia, serif;
                font-size: 1.2rem;
                font-weight: 600;
                letter-spacing: 0.03em;
                color: #ffffff;
                line-height: 1.2;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .campaign-close-btn {
                flex-shrink: 0;
                background: rgba(255, 255, 255, 0.08);
                border: 1px solid rgba(255, 255, 255, 0.15);
                color: #e5e5e5;
                width: 32px;
                height: 32px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.25s ease;
            }

            .campaign-close-btn:hover {
                background: #ffffff;
                color: #000000;
                transform: rotate(90deg);
            }

            /* Video Frame - Center, Uncropped & Sized to Natural Video Aspect Ratio */
            .campaign-video-frame {
                flex: 1 1 auto;
                min-height: 240px;
                height: 52vh;
                max-height: calc(88vh - 140px);
                position: relative;
                width: 100%;
                background: #000000;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
            }

            .campaign-video-dialog.is-portrait .campaign-video-frame {
                height: 60vh;
                max-height: calc(90vh - 140px);
            }

            /* CRITICAL: object-fit: contain ensures 100% of the person, head, and feet are shown */
            .campaign-video-element {
                max-width: 100% !important;
                max-height: 100% !important;
                width: auto !important;
                height: 100% !important;
                object-fit: contain !important;
                object-position: center center !important;
                display: block !important;
                background: #000000 !important;
                margin: 0 auto !important;
            }

            /* Video Controls Overlay */
            .campaign-video-controls-overlay {
                position: absolute;
                bottom: 14px;
                left: 16px;
                right: 16px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 10;
                pointer-events: none;
            }

            .campaign-audio-pill,
            .campaign-play-pill {
                pointer-events: auto;
                background: rgba(10, 10, 10, 0.82);
                backdrop-filter: blur(12px);
                -webkit-backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #ffffff;
                border-radius: 30px;
                padding: 7px 14px;
                font-size: 0.74rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.2s ease;
            }

            .campaign-audio-pill:hover,
            .campaign-play-pill:hover {
                background: #ffffff;
                color: #000000;
                border-color: #ffffff;
            }

            /* Sound wave animation */
            .audio-equalizer-bars {
                display: inline-flex;
                align-items: flex-end;
                gap: 2px;
                height: 12px;
            }

            .audio-equalizer-bars span {
                width: 2px;
                background: currentColor;
                border-radius: 1px;
                height: 4px;
                transition: height 0.2s ease;
            }

            .audio-equalizer-bars.is-playing span:nth-child(1) {
                animation: soundWave 0.8s infinite ease-in-out alternate;
            }
            .audio-equalizer-bars.is-playing span:nth-child(2) {
                animation: soundWave 0.8s 0.2s infinite ease-in-out alternate;
            }
            .audio-equalizer-bars.is-playing span:nth-child(3) {
                animation: soundWave 0.8s 0.4s infinite ease-in-out alternate;
            }

            @keyframes soundWave {
                0% { height: 3px; }
                100% { height: 12px; }
            }

            /* Progress Bar */
            .campaign-progress-bar-wrap {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: rgba(255, 255, 255, 0.15);
                z-index: 12;
            }

            .campaign-progress-bar-fill {
                height: 100%;
                width: 0%;
                background: #ffffff;
                transition: width 0.15s linear;
            }

            /* Footer */
            .campaign-modal-footer {
                flex-shrink: 0;
                padding: 14px 20px;
                background: #080808;
                border-top: 1px solid rgba(255, 255, 255, 0.08);
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 14px;
                box-sizing: border-box;
            }

            .campaign-video-dialog.is-portrait .campaign-modal-footer {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }

            .campaign-footer-text {
                flex: 1;
                min-width: 0;
            }

            .campaign-desc {
                margin: 0;
                font-size: 0.82rem;
                color: #a3a3a3;
                line-height: 1.4;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .campaign-video-dialog.is-portrait .campaign-desc {
                text-align: center;
                font-size: 0.78rem;
            }

            .campaign-footer-actions {
                display: flex;
                align-items: center;
                gap: 12px;
                flex-shrink: 0;
            }

            .campaign-video-dialog.is-portrait .campaign-footer-actions {
                flex-direction: column-reverse;
                width: 100%;
                gap: 6px;
            }

            .campaign-continue-link {
                background: transparent;
                border: none;
                color: #737373;
                font-size: 0.82rem;
                cursor: pointer;
                padding: 6px 10px;
                transition: color 0.2s ease;
                white-space: nowrap;
            }

            .campaign-continue-link:hover {
                color: #ffffff;
            }

            .campaign-cta-btn {
                background: #ffffff;
                color: #000000;
                padding: 10px 18px;
                font-size: 0.78rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                text-decoration: none;
                border-radius: 4px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 6px;
                transition: all 0.25s ease;
                white-space: nowrap;
            }

            .campaign-video-dialog.is-portrait .campaign-cta-btn {
                width: 100%;
            }

            .campaign-cta-btn:hover {
                background: #e5e5e5;
                color: #000000;
                transform: translateY(-1px);
                box-shadow: 0 6px 20px rgba(255, 255, 255, 0.15);
            }

            .campaign-cta-btn .cta-arrow {
                transition: transform 0.25s ease;
            }

            .campaign-cta-btn:hover .cta-arrow {
                transform: translateX(3px);
            }

            /* Floating Launcher Button */
            .floating-campaign-film-btn {
                position: fixed;
                bottom: 24px;
                left: 24px;
                z-index: 9999;
                background: #000000;
                border: 1px solid rgba(255, 255, 255, 0.25);
                color: #ffffff;
                padding: 10px 18px;
                border-radius: 30px;
                font-size: 0.8rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                cursor: pointer;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5), 0 0 14px rgba(255, 255, 255, 0.2);
                display: inline-flex;
                align-items: center;
                gap: 8px;
                transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
                animation: campaignPulseGlow 3s infinite ease-in-out;
            }

            .film-icon {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                filter: drop-shadow(0 0 6px rgba(255, 255, 255, 0.8)) drop-shadow(0 0 12px rgba(255, 255, 255, 0.5));
                animation: filmIconGlow 2.5s infinite ease-in-out;
                transition: transform 0.3s ease;
            }

            @keyframes filmIconGlow {
                0%, 100% {
                    filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.7)) drop-shadow(0 0 10px rgba(255, 255, 255, 0.35));
                    transform: scale(1);
                }
                50% {
                    filter: drop-shadow(0 0 10px rgba(255, 255, 255, 1)) drop-shadow(0 0 18px rgba(255, 255, 255, 0.8));
                    transform: scale(1.1);
                }
            }

            @keyframes campaignPulseGlow {
                0%, 100% {
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5), 0 0 10px rgba(255, 255, 255, 0.15);
                    border-color: rgba(255, 255, 255, 0.25);
                }
                50% {
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6), 0 0 20px rgba(255, 255, 255, 0.4), 0 0 35px rgba(255, 255, 255, 0.15);
                    border-color: rgba(255, 255, 255, 0.6);
                }
            }

            .floating-campaign-film-btn:hover {
                background: #ffffff;
                color: #000000;
                border-color: #ffffff;
                transform: translateY(-2px);
                box-shadow: 0 14px 40px rgba(0, 0, 0, 0.6), 0 0 25px rgba(255, 255, 255, 0.6);
            }

            .floating-campaign-film-btn:hover .film-icon {
                filter: drop-shadow(0 0 8px rgba(0, 0, 0, 0.6));
            }

            @media (max-width: 768px) {
                .floating-campaign-film-btn {
                    bottom: 16px;
                    left: 16px;
                    width: 48px;
                    height: 48px;
                    padding: 0;
                    border-radius: 50%;
                    justify-content: center;
                }
                .floating-campaign-film-btn .film-label {
                    display: none !important;
                }
                .floating-campaign-film-btn .film-icon {
                    font-size: 1.35rem;
                }
            }

            @media (max-width: 640px) {
                .campaign-video-backdrop {
                    padding: 8px;
                }
                .campaign-video-dialog {
                    max-height: 96vh;
                    border-radius: 12px;
                }
                .campaign-modal-header {
                    padding: 10px 14px;
                }
                .campaign-editorial-title {
                    font-size: 1.05rem;
                }
                .campaign-video-frame {
                    min-height: 200px;
                    height: 58vh;
                }
                .campaign-modal-footer {
                    padding: 10px 14px;
                    flex-direction: column;
                    align-items: stretch;
                    gap: 10px;
                }
                .campaign-footer-actions {
                    flex-direction: column-reverse;
                    width: 100%;
                    gap: 6px;
                }
                .campaign-cta-btn {
                    width: 100%;
                    justify-content: center;
                    padding: 9px 14px;
                }
                .campaign-continue-link {
                    text-align: center;
                    padding: 4px;
                }
            }
        </style>

        {{-- Script for Timing, Frequency, Adaptive Orientation & Interactive Playback --}}
        <script>
            (function() {
                const modal = document.getElementById('pistisCampaignModal');
                const dialog = document.getElementById('campaignVideoDialog');
                const video = document.getElementById('pistisIntroVideo');
                const audioBtn = document.getElementById('campaignAudioToggleBtn');
                const audioText = document.getElementById('campaignAudioText');
                const equalizer = document.querySelector('.audio-equalizer-bars');
                const progressBar = document.getElementById('campaignProgressBarFill');
                const playBtn = document.getElementById('campaignPlayPauseBtn');
                const playIcon = document.getElementById('playIconSvg');
                const pauseIcon = document.getElementById('pauseIconSvg');
                const floatingBtn = document.getElementById('pistisFloatingCampaignBtn');

                const delaySeconds = {{ max(1, (int)$campaignVideo['delay']) }};
                const frequency = '{{ $campaignVideo['frequency'] }}';

                let hasAutoTriggered = false;

                // Detect video orientation (portrait reel vs landscape widescreen) and adjust dialog shape
                function checkVideoOrientation() {
                    if (video && video.videoWidth && video.videoHeight && dialog) {
                        if (video.videoHeight > video.videoWidth) {
                            dialog.classList.add('is-portrait');
                        } else {
                            dialog.classList.remove('is-portrait');
                        }
                    }
                }

                if (video) {
                    video.addEventListener('loadedmetadata', checkVideoOrientation);
                    if (video.readyState >= 1) {
                        checkVideoOrientation();
                    }
                }

                // Frequency verification
                function shouldAutoOpen() {
                    if (frequency === 'always') return true;

                    if (frequency === 'once_per_day') {
                        const lastShown = localStorage.getItem('pistis_campaign_video_day');
                        const today = new Date().toDateString();
                        return lastShown !== today;
                    }

                    // Default: once_per_session
                    return sessionStorage.getItem('pistis_campaign_video_session') !== 'seen';
                }

                function markAsSeen() {
                    sessionStorage.setItem('pistis_campaign_video_session', 'seen');
                    localStorage.setItem('pistis_campaign_video_day', new Date().toDateString());
                }

                // Open Modal Function
                window.openCampaignVideoModal = function(userInitiated = false) {
                    if (!modal) return;
                    checkVideoOrientation();
                    modal.style.display = 'flex';
                    // Trigger reflow for CSS animation
                    modal.offsetHeight;
                    modal.classList.add('is-active');
                    document.body.style.overflow = 'hidden';

                    if (video) {
                        video.play().catch(err => {
                            console.log('Autoplay muted attempt prevented by browser:', err);
                        });
                    }

                    if (!userInitiated) {
                        markAsSeen();
                    }
                };

                // Close Modal Function
                window.closeCampaignVideoModal = function() {
                    if (!modal) return;
                    modal.classList.remove('is-active');
                    document.body.style.overflow = '';
                    setTimeout(() => {
                        modal.style.display = 'none';
                        if (video) {
                            video.pause();
                        }
                    }, 400);
                };

                // Audio Mute/Unmute Toggle
                window.toggleCampaignVideoAudio = function() {
                    if (!video) return;
                    if (video.muted) {
                        video.muted = false;
                        if (audioText) audioText.textContent = 'MUTE SOUND';
                        if (equalizer) equalizer.classList.add('is-playing');
                    } else {
                        video.muted = true;
                        if (audioText) audioText.textContent = 'UNMUTE SOUND';
                        if (equalizer) equalizer.classList.remove('is-playing');
                    }
                };

                // Play / Pause Toggle
                window.toggleCampaignVideoPlayback = function() {
                    if (!video) return;
                    if (video.paused) {
                        video.play();
                        if (playIcon) playIcon.style.display = 'none';
                        if (pauseIcon) pauseIcon.style.display = 'block';
                    } else {
                        video.pause();
                        if (playIcon) playIcon.style.display = 'block';
                        if (pauseIcon) pauseIcon.style.display = 'none';
                    }
                };

                // Progress update
                if (video && progressBar) {
                    video.addEventListener('timeupdate', () => {
                        if (video.duration) {
                            const percent = (video.currentTime / video.duration) * 100;
                            progressBar.style.width = percent + '%';
                        }
                    });
                }

                // Keyboard escape listener
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && modal && modal.classList.contains('is-active')) {
                        closeCampaignVideoModal();
                    }
                });

                // Backdrop click to close
                if (modal) {
                    modal.addEventListener('click', (e) => {
                        if (e.target === modal) {
                            closeCampaignVideoModal();
                        }
                    });
                }

                // Auto-trigger timer based on admin delay
                if (shouldAutoOpen()) {
                    setTimeout(() => {
                        if (!hasAutoTriggered) {
                            hasAutoTriggered = true;
                            openCampaignVideoModal(false);
                        }
                    }, delaySeconds * 1000);
                }
            })();
        </script>
    @endif
@endif
