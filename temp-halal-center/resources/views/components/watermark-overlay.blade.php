@php
    $watermarkEnabled = (bool) data_get($setting, 'watermark_enabled');
    $watermarkText = trim((string) data_get($setting, 'watermark_text', ''));
    $watermarkImagePath = data_get($setting, 'watermark_image_path');
    $watermarkOpacity = (float) data_get($setting, 'watermark_opacity', 0.18);
    $showWatermark = $watermarkEnabled && ($watermarkText !== '' || filled($watermarkImagePath));
@endphp

@if($showWatermark)
    <div
        id="globalWatermarkOverlay"
        class="watermark-overlay"
        data-watermark-root="true"
        data-watermark-text="{{ $watermarkText }}"
        data-watermark-image="{{ filled($watermarkImagePath) ? asset('storage/'.$watermarkImagePath) : '' }}"
        data-watermark-opacity="{{ max(0.05, min(1, $watermarkOpacity)) }}"
        style="--watermark-opacity: {{ max(0.05, min(1, $watermarkOpacity)) }};"
        aria-hidden="true"
    >
        <div class="watermark-marquee watermark-marquee-text watermark-marquee-diagonal watermark-marquee-top" aria-hidden="true">
            <div class="watermark-marquee-track">
                @foreach(range(1, 10) as $index)
                    @if($watermarkText !== '')
                        <span class="watermark-tile-text watermark-tile-text-small">{{ $watermarkText }}</span>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="watermark-marquee watermark-marquee-image watermark-marquee-diagonal watermark-marquee-upper" aria-hidden="true">
            <div class="watermark-marquee-track">
                @foreach(range(1, 14) as $index)
                    @if(filled($watermarkImagePath))
                        <img
                            src="{{ asset('storage/'.$watermarkImagePath) }}"
                            alt=""
                            class="watermark-tile-image watermark-tile-image-small"
                            draggable="false"
                        >
                    @endif
                @endforeach
            </div>
        </div>

        <div class="watermark-band watermark-band-text" aria-hidden="true">
            @if($watermarkText !== '')
                @foreach(range(1, 4) as $index)
                    <span class="watermark-center-text">{{ $watermarkText }}</span>
                @endforeach
            @endif
        </div>

        <div class="watermark-band watermark-band-image" aria-hidden="true">
            @if(filled($watermarkImagePath))
                <img
                    src="{{ asset('storage/'.$watermarkImagePath) }}"
                    alt=""
                    class="watermark-center-image watermark-center-image-hero"
                    draggable="false"
                >
            @endif
        </div>

        <div class="watermark-marquee watermark-marquee-text watermark-marquee-diagonal watermark-marquee-lower" aria-hidden="true">
            <div class="watermark-marquee-track watermark-marquee-track-reverse">
                @foreach(range(1, 10) as $index)
                    @if($watermarkText !== '')
                        <span class="watermark-tile-text watermark-tile-text-small">{{ $watermarkText }}</span>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="watermark-marquee watermark-marquee-image watermark-marquee-diagonal watermark-marquee-bottom" aria-hidden="true">
            <div class="watermark-marquee-track watermark-marquee-track-reverse">
                @foreach(range(1, 14) as $index)
                    @if(filled($watermarkImagePath))
                        <img
                            src="{{ asset('storage/'.$watermarkImagePath) }}"
                            alt=""
                            class="watermark-tile-image watermark-tile-image-small"
                            draggable="false"
                        >
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@endif
