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
        <div class="watermark-overlay-grid watermark-overlay-grid-primary">
            @foreach(range(1, 20) as $index)
                <div class="watermark-tile">
                    @if(filled($watermarkImagePath))
                        <img
                            src="{{ asset('storage/'.$watermarkImagePath) }}"
                            alt=""
                            class="watermark-tile-image"
                            draggable="false"
                        >
                    @endif

                    @if($watermarkText !== '')
                        <span class="watermark-tile-text">{{ $watermarkText }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="watermark-overlay-grid watermark-overlay-grid-secondary" aria-hidden="true">
            @foreach(range(1, 20) as $index)
                <div class="watermark-tile watermark-tile-secondary">
                    @if(filled($watermarkImagePath))
                        <img
                            src="{{ asset('storage/'.$watermarkImagePath) }}"
                            alt=""
                            class="watermark-tile-image"
                            draggable="false"
                        >
                    @endif

                    @if($watermarkText !== '')
                        <span class="watermark-tile-text">{{ $watermarkText }}</span>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="watermark-center-badge" aria-hidden="true">
            @if(filled($watermarkImagePath))
                <img
                    src="{{ asset('storage/'.$watermarkImagePath) }}"
                    alt=""
                    class="watermark-center-image"
                    draggable="false"
                >
            @endif

            @if($watermarkText !== '')
                <span class="watermark-center-text">{{ $watermarkText }}</span>
            @endif
        </div>
    </div>
@endif
