import './bootstrap';

import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

import markerIcon2x from 'leaflet/dist/images/marker-icon-2x.png';
import markerIcon from 'leaflet/dist/images/marker-icon.png';
import markerShadow from 'leaflet/dist/images/marker-shadow.png';

delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: markerIcon2x,
    iconUrl: markerIcon,
    shadowUrl: markerShadow,
});

window.Alpine = Alpine;
window.L = L;

Alpine.start();

const createIcons = () => {
    if (window.lucide?.createIcons) {
        window.lucide.createIcons();
    }
};

const escapeHtml = (value) => {
    if (value === null || value === undefined) return '';

    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
};

const publicDetailUrl = (type, slug) => {
    if (!slug) return '#';

    const routes = {
        articles: `/articles/${slug}`,
        products: `/products/${slug}`,
        resources: `/resources/${slug}`,
        regulations: `/regulations/${slug}`,
        locations: `/locations/${slug}`,
    };

    return routes[type] || '#';
};

window.toggleMobileMenu = function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    if (!menu) return;

    if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        window.setTimeout(() => {
            menu.classList.remove('translate-x-full');
            menu.classList.add('translate-x-0');
        }, 10);
    } else {
        menu.classList.remove('translate-x-0');
        menu.classList.add('translate-x-full');
        window.setTimeout(() => menu.classList.add('hidden'), 300);
    }
};

window.toggleSidebar = function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarBackdrop = document.getElementById('sidebarBackdrop');

    if (!sidebar || !sidebarBackdrop) return;

    if (sidebar.classList.contains('-translate-x-full')) {
        sidebar.classList.remove('-translate-x-full');
        sidebarBackdrop.classList.remove('hidden');
    } else {
        sidebar.classList.add('-translate-x-full');
        sidebarBackdrop.classList.add('hidden');
    }
};

window.openModal = function openModal(id) {
    const modal = document.getElementById(id);
    const backdrop = document.getElementById(id.replace('Modal', 'Backdrop'));
    const content = document.getElementById(id.replace('Modal', 'Content'));

    if (!modal || !backdrop || !content) return;

    modal.classList.remove('hidden');
    void modal.offsetWidth;
    backdrop.classList.remove('opacity-0');
    backdrop.classList.add('opacity-100');
    content.classList.remove('opacity-0', 'scale-95', 'translate-y-4');
    content.classList.add('opacity-100', 'scale-100', 'translate-y-0');

    const focusInput = content.querySelector('input');
    if (focusInput) {
        window.setTimeout(() => focusInput.focus(), 120);
    }

    const pickers = content.querySelectorAll('[data-map-picker]');
    pickers.forEach(picker => {
        const canvas = picker.querySelector('[data-map-canvas]');
        if (canvas && canvas._leaflet_map) {
            window.setTimeout(() => {
                canvas._leaflet_map.invalidateSize();
                const latInput = document.getElementById(picker.dataset.latitudeTarget);
                const lngInput = document.getElementById(picker.dataset.longitudeTarget);
                if (latInput && lngInput && latInput.value && lngInput.value) {
                    canvas._leaflet_map.setView([latInput.value, lngInput.value], 15);
                } else {
                    canvas._leaflet_map.setView([-0.502106, 117.153709], 7);
                }
            }, 350);
        }
    });
};

window.closeModal = function closeModal(id) {
    const modal = document.getElementById(id);
    const backdrop = document.getElementById(id.replace('Modal', 'Backdrop'));
    const content = document.getElementById(id.replace('Modal', 'Content'));

    if (!modal || !backdrop || !content) return;

    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('opacity-100', 'scale-100', 'translate-y-0');
    content.classList.add('opacity-0', 'scale-95', 'translate-y-4');

    window.setTimeout(() => modal.classList.add('hidden'), 300);
};

const initNavbarScroll = () => {
    const navbar = document.getElementById('navbar');
    if (!navbar) return;

    const updateNavbar = () => {
        if (window.scrollY > 10) {
            navbar.classList.add('bg-white/90', 'border-b', 'border-slate-100', 'shadow-sm', 'py-3');
            navbar.classList.remove('border-b-0', 'py-4');
        } else {
            navbar.classList.remove('bg-white/90', 'border-b', 'border-slate-100', 'shadow-sm', 'py-3');
            navbar.classList.add('border-b-0', 'py-4');
        }
    };

    updateNavbar();
    window.addEventListener('scroll', updateNavbar);
};

const initCounters = () => {
    const counters = document.querySelectorAll('.counter');
    const heroSection = document.getElementById('hero');

    if (!counters.length || !heroSection) return;

    const animateCounters = () => {
        counters.forEach((counter) => {
            const target = Number(counter.dataset.target || 0);
            let current = 0;
            const increment = Math.max(1, Math.ceil(target / 50));

            const timer = window.setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    window.clearInterval(timer);
                }

                counter.textContent = new Intl.NumberFormat('id-ID').format(current);
            }, 30);
        });
    };

    const observer = new IntersectionObserver((entries) => {
        if (entries[0]?.isIntersecting) {
            animateCounters();
            observer.disconnect();
        }
    });

    observer.observe(heroSection);
};

const initSearchModal = () => {
    const input = document.getElementById('globalSearchInput');
    const results = document.getElementById('searchResults');

    if (!input || !results) return;

    let timer = null;
    const searchUrl = '/search';

    input.addEventListener('input', () => {
        const keyword = input.value.trim();
        if (timer) window.clearTimeout(timer);

        if (!keyword) {
            results.innerHTML = '';
            return;
        }

        timer = window.setTimeout(async () => {
            try {
                const response = await fetch(`${searchUrl}?keyword=${encodeURIComponent(keyword)}`);
                const payload = await response.json();

                results.innerHTML = ['articles', 'products', 'resources', 'regulations'].map((type) => {
                    const items = payload[type] || [];

                    return `
                        <div class="rounded-2xl border border-slate-200 bg-white/90 p-4 shadow-sm">
                            <p class="text-[10px] font-extrabold uppercase tracking-[0.28em] text-slate-400">${escapeHtml(type)}</p>
                            <div class="mt-3 space-y-2">
                                ${items.length
                                    ? items.map((item) => `
                                        <a href="${publicDetailUrl(type, item.slug)}" class="block rounded-xl border border-slate-100 px-3 py-2 text-sm font-semibold text-slate-800 transition hover:border-emerald-200 hover:bg-emerald-50 hover:text-emerald-600">
                                            ${escapeHtml(item.title ?? item.name)}
                                        </a>
                                    `).join('')
                                    : '<p class="text-sm text-slate-400">Tidak ada hasil</p>'}
                            </div>
                        </div>
                    `;
                }).join('');
            } catch (error) {
                results.innerHTML = '<div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-600">Pencarian sedang tidak tersedia. Coba lagi beberapa saat.</div>';
            }
        }, 250);
    });
};

const initMap = () => {
    const mapElement = document.getElementById('leafletKaltim');
    if (!mapElement || mapElement._leaflet_id) return;

    const normalizeResourceCollection = (value) => {
        if (Array.isArray(value)) return value;
        if (Array.isArray(value?.data)) return value.data;

        return [];
    };

    const map = L.map(mapElement, { zoomControl: false }).setView([-0.502, 117.153], 6);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OSM</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
    }).addTo(map);

    const aggregateIcon = L.divIcon({
        className: 'leaflet-custom-marker',
        iconSize: [20, 20],
        iconAnchor: [10, 10],
        popupAnchor: [0, -10],
    });

    const businessIcon = L.divIcon({
        className: 'leaflet-business-marker',
        iconSize: [12, 12],
        iconAnchor: [6, 6],
        popupAnchor: [0, -8],
    });

    const cityFilter = document.getElementById('mapCityFilter');
    const typeFilter = document.getElementById('mapTypeFilter');
    const partnerFilter = document.getElementById('mapPartnerFilter');
    const categoryFilter = document.getElementById('mapCategoryFilter');
    const searchInput = document.getElementById('mapSearchInput');
    const categoryButtons = document.querySelectorAll('[data-map-category]');
    const zoomButtons = document.querySelectorAll('[data-map-zoom]');
    const markersLayer = L.layerGroup().addTo(map);

    let state = {
        category: '',
        city: '',
        location_type: '',
        lph_partner_id: '',
        keyword: '',
    };

    const renderMap = async () => {
        const query = new URLSearchParams();
        Object.entries(state).forEach(([key, value]) => {
            if (value) query.set(key, value);
        });

        try {
            const response = await fetch(`${mapElement.dataset.mapUrl}?${query.toString()}`);
            const result = await response.json();
            const regions = normalizeResourceCollection(result?.data?.regions);
            const locations = normalizeResourceCollection(result?.data?.locations)
                .filter((location) => Number.isFinite(Number(location.latitude)) && Number.isFinite(Number(location.longitude)));

            markersLayer.clearLayers();

            regions.forEach((region) => {
                const regionLocations = locations.filter((location) => location.region?.id === region.id);
                if (!regionLocations.length) return;

                const popupContent = `
                    <div class="text-left">
                        <h4 class="font-heading mb-1 text-sm font-extrabold text-slate-900">${escapeHtml(region.name)}</h4>
                        <p class="mb-2 border-b border-slate-100 pb-2 text-[10px] font-semibold text-slate-500">Sebaran usaha dan layanan halal</p>
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-[9px] font-bold uppercase text-slate-400">Titik Tersedia</p>
                                <p class="text-sm font-extrabold text-emerald-600">${new Intl.NumberFormat('id-ID').format(regionLocations.length)}</p>
                            </div>
                            <a href="/products?keyword=${encodeURIComponent(region.name)}" class="rounded bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-600">Lihat Produk</a>
                        </div>
                    </div>
                `;

                L.marker([region.latitude, region.longitude], { icon: aggregateIcon })
                    .bindPopup(popupContent)
                    .addTo(markersLayer);
            });

            locations.forEach((location) => {
                const popupContent = `
                    <div class="min-w-[180px] text-left">
                        <span class="mb-1.5 inline-block rounded border border-cyan-100 bg-cyan-50 px-2 py-0.5 text-[8px] font-bold uppercase text-cyan-600">${escapeHtml(location.category)}</span>
                        <h4 class="font-heading mb-0.5 text-sm font-extrabold text-slate-900">${escapeHtml(location.name)}</h4>
                        <p class="mb-1 text-[9px] font-medium text-slate-500">${escapeHtml(location.city_name ?? location.region?.name ?? '')}</p>
                        <p class="mb-1 text-[9px] font-medium text-slate-500">${escapeHtml(location.lph_partner?.name ?? 'Mitra belum diatur')}</p>
                        <p class="mb-2 text-[9px] font-medium text-slate-500">No. ID: ${escapeHtml(location.certificate_number ?? '-')}</p>
                        <a href="${publicDetailUrl('locations', location.slug)}" class="block w-full rounded border border-slate-200 bg-slate-100 px-2 py-1.5 text-center text-[10px] font-bold text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600">Lihat Detail</a>
                    </div>
                `;

                L.marker([location.latitude, location.longitude], { icon: businessIcon })
                    .bindPopup(popupContent)
                    .addTo(markersLayer);
            });
        } catch (error) {
            // Don't wipe the map completely; keep last successful render if API fails.
            console.error('Map render failed:', error);
        }
    };

    cityFilter?.addEventListener('change', () => {
        state.city = cityFilter.value;
        renderMap();
    });

    typeFilter?.addEventListener('change', () => {
        state.location_type = typeFilter.value;
        renderMap();
    });

    partnerFilter?.addEventListener('change', () => {
        state.lph_partner_id = partnerFilter.value;
        renderMap();
    });

    categoryFilter?.addEventListener('change', () => {
        state.category = categoryFilter.value;
        renderMap();
    });

    let searchTimer = null;
    searchInput?.addEventListener('input', () => {
        if (searchTimer) window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => {
            state.keyword = searchInput.value.trim();
            renderMap();
        }, 250);
    });

    categoryButtons.forEach((button) => {
        button.addEventListener('click', () => {
            categoryButtons.forEach((item) => item.classList.remove('active'));
            button.classList.add('active');
            state.category = button.dataset.mapCategory || '';
            renderMap();
        });
    });

    zoomButtons.forEach((button) => {
        button.addEventListener('click', () => {
            if (button.dataset.mapZoom === 'in') map.zoomIn();
            if (button.dataset.mapZoom === 'out') map.zoomOut();
        });
    });

    renderMap();
};

const initAdminMapPickers = () => {
    const pickers = document.querySelectorAll('[data-map-picker]');
    if (!pickers.length || !window.L) return;

    pickers.forEach((picker, index) => {
        const latInput = document.getElementById(picker.dataset.latitudeTarget);
        const lngInput = document.getElementById(picker.dataset.longitudeTarget);
        const addressInput = document.getElementById(picker.dataset.addressTarget);
        const searchInput = picker.querySelector('[data-map-search]');
        const searchButton = picker.querySelector('[data-map-search-button]');
        const reverseButton = picker.querySelector('[data-map-reverse-button]');
        const fetchAddressButton = picker.querySelector('[data-map-fetch-address]');
        const status = picker.querySelector('[data-map-status]');
        const canvas = picker.querySelector('[data-map-canvas]');

        if (!latInput || !lngInput || !canvas || canvas._leaflet_id) return;

        const defaultLat = Number.parseFloat(latInput.value || '-0.502106');
        const defaultLng = Number.parseFloat(lngInput.value || '117.153709');
        const map = L.map(canvas, { zoomControl: true }).setView([defaultLat, defaultLng], latInput.value && lngInput.value ? 14 : 7);
        canvas._leaflet_map = map;

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
        }).addTo(map);

        const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        const updateFields = (lat, lng) => {
            latInput.value = Number(lat).toFixed(7);
            lngInput.value = Number(lng).toFixed(7);
            marker.setLatLng([lat, lng]);
            
            const suspicious = lat < -4.5 || lat > 5.5 || lng < 113 || lng > 120;

            if (status) {
                status.innerHTML = `Koordinat terpilih: <span class="font-bold">${Number(lat).toFixed(7)}, ${Number(lng).toFixed(7)}</span>`;
                if (suspicious) {
                    status.innerHTML += ` <span class="bg-rose-100 text-rose-600 px-2 py-0.5 rounded-full text-[10px] font-bold">Peringatan: Di luar Kaltim</span>`;
                }
            }
        };

        const reverseGeocode = async () => {
            const lat = Number.parseFloat(latInput.value);
            const lng = Number.parseFloat(lngInput.value);

            if (Number.isNaN(lat) || Number.isNaN(lng)) {
                if (status) status.textContent = 'Isi latitude dan longitude dulu sebelum mencari alamat.';
                return;
            }

            if (status) status.textContent = 'Mencari alamat dari titik...';

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${encodeURIComponent(lat)}&lon=${encodeURIComponent(lng)}`);
                const payload = await response.json();

                if (addressInput && payload.display_name) {
                    addressInput.value = payload.display_name;
                }

                if (status) status.textContent = payload.display_name || 'Alamat tidak ditemukan untuk titik ini.';
            } catch (error) {
                if (status) status.textContent = 'Gagal mengambil alamat dari koordinat.';
            }
        };

        map.on('click', (event) => {
            updateFields(event.latlng.lat, event.latlng.lng);
        });

        marker.on('dragend', (event) => {
            const position = event.target.getLatLng();
            updateFields(position.lat, position.lng);
        });

        [latInput, lngInput].forEach((input) => {
            input.addEventListener('change', () => {
                const lat = Number.parseFloat(latInput.value);
                const lng = Number.parseFloat(lngInput.value);

                if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                    updateFields(lat, lng);
                    map.setView([lat, lng], 14);
                }
            });
        });

        searchButton?.addEventListener('click', async () => {
            let keyword = searchInput?.value?.trim();

            if (!keyword) {
                if (status) status.textContent = 'Masukkan alamat atau nama tempat yang ingin dicari.';
                return;
            }

            // Append Kaltim to improve accuracy
            if (!keyword.toLowerCase().includes('kalimantan')) {
                keyword += ', Kalimantan Timur';
            }

            if (status) status.textContent = 'Mencari lokasi...';

            try {
                // Use Kaltim bounding box: 113.5, -2.5 to 119.5, 4.0
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(keyword)}&limit=1&bounded=1&viewbox=113.0,-3.5,120.0,5.5`);
                const payload = await response.json();
                const firstResult = payload?.[0];

                if (!firstResult) {
                    // Try again without bounding box if first search fails
                    const secondary = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&q=${encodeURIComponent(keyword)}&limit=1`);
                    const secondaryPayload = await secondary.json();
                    const secondResult = secondaryPayload?.[0];

                    if (!secondResult) {
                        if (status) status.textContent = 'Lokasi tidak ditemukan.';
                        return;
                    }
                    
                    const lat = Number.parseFloat(secondResult.lat);
                    const lng = Number.parseFloat(secondResult.lon);
                    updateFields(lat, lng);
                    map.setView([lat, lng], 15);
                    if (addressInput) addressInput.value = secondResult.display_name || addressInput.value;
                    return;
                }

                const lat = Number.parseFloat(firstResult.lat);
                const lng = Number.parseFloat(firstResult.lon);

                updateFields(lat, lng);
                map.setView([lat, lng], 15);

                if (addressInput) {
                    addressInput.value = firstResult.display_name || addressInput.value;
                }
            } catch (error) {
                if (status) status.textContent = 'Gagal mencari lokasi.';
            }
        });

        fetchAddressButton?.addEventListener('click', () => {
            if (addressInput && addressInput.value.trim()) {
                searchInput.value = addressInput.value;
                searchButton.click();
            } else {
                if (status) status.textContent = 'Alamat kosong. Isi alamat terlebih dahulu.';
            }
        });

        reverseButton?.addEventListener('click', reverseGeocode);

        window.setTimeout(() => map.invalidateSize(), 120 + index * 60);
    });
};

const createWatermarkTileMarkup = (text, imageUrl, secondary = false) => `
    <div class="watermark-tile${secondary ? ' watermark-tile-secondary' : ''}">
        ${imageUrl ? `<img src="${imageUrl}" alt="" class="watermark-tile-image" draggable="false">` : ''}
        ${text ? `<span class="watermark-tile-text">${escapeHtml(text)}</span>` : ''}
    </div>
`;

const createWatermarkMarkup = (text, imageUrl) => {
    const textSmall = Array.from(
        { length: 10 },
        () => (text ? `<span class="watermark-tile-text watermark-tile-text-small">${escapeHtml(text)}</span>` : ''),
    ).join('');
    const imageSmall = Array.from(
        { length: 14 },
        () => (imageUrl ? `<img src="${imageUrl}" alt="" class="watermark-tile-image watermark-tile-image-small" draggable="false">` : ''),
    ).join('');
    const textLarge = Array.from(
        { length: 4 },
        () => (text ? `<span class="watermark-center-text">${escapeHtml(text)}</span>` : ''),
    ).join('');
    const imageLarge = Array.from(
        { length: 1 },
        () => (imageUrl ? `<img src="${imageUrl}" alt="" class="watermark-center-image watermark-center-image-hero" draggable="false">` : ''),
    ).join('');

    return `
        <div class="watermark-marquee watermark-marquee-text watermark-marquee-diagonal watermark-marquee-top" aria-hidden="true">
            <div class="watermark-marquee-track">${textSmall}</div>
        </div>
        <div class="watermark-marquee watermark-marquee-image watermark-marquee-diagonal watermark-marquee-upper" aria-hidden="true">
            <div class="watermark-marquee-track">${imageSmall}</div>
        </div>
        <div class="watermark-band watermark-band-text" aria-hidden="true">
            ${textLarge}
        </div>
        <div class="watermark-band watermark-band-image" aria-hidden="true">
            ${imageLarge}
        </div>
        <div class="watermark-marquee watermark-marquee-text watermark-marquee-diagonal watermark-marquee-lower" aria-hidden="true">
            <div class="watermark-marquee-track watermark-marquee-track-reverse">${textSmall}</div>
        </div>
        <div class="watermark-marquee watermark-marquee-image watermark-marquee-diagonal watermark-marquee-bottom" aria-hidden="true">
            <div class="watermark-marquee-track watermark-marquee-track-reverse">${imageSmall}</div>
        </div>
    `;
};

const initAggressiveWatermark = () => {
    const overlay = document.querySelector('[data-watermark-root="true"]');
    if (!overlay) return;

    const restoreOverlay = () => {
        const existing = document.getElementById('globalWatermarkOverlay');
        const text = overlay.dataset.watermarkText || '';
        const imageUrl = overlay.dataset.watermarkImage || '';
        const opacity = overlay.dataset.watermarkOpacity || '0.18';

        if (!text && !imageUrl) return;

        const root = existing || overlay;
        root.id = 'globalWatermarkOverlay';
        root.dataset.watermarkRoot = 'true';
        root.dataset.watermarkText = text;
        root.dataset.watermarkImage = imageUrl;
        root.dataset.watermarkOpacity = opacity;
        root.style.setProperty('--watermark-opacity', opacity);

        if (!root.parentElement) {
            document.body.appendChild(root);
        }

        root.className = 'watermark-overlay';
        root.setAttribute('aria-hidden', 'true');
        root.innerHTML = createWatermarkMarkup(text, imageUrl);

        if (getComputedStyle(root).display === 'none' || getComputedStyle(root).visibility === 'hidden') {
            root.style.display = 'block';
            root.style.visibility = 'visible';
            root.style.opacity = '1';
        }
    };

    restoreOverlay();

    const observer = new MutationObserver(() => {
        const root = document.getElementById('globalWatermarkOverlay');
        if (!root || !document.body.contains(root)) {
            restoreOverlay();
            return;
        }

        const computedStyle = getComputedStyle(root);
        if (
            root.className !== 'watermark-overlay'
            || computedStyle.display === 'none'
            || computedStyle.visibility === 'hidden'
            || Number.parseFloat(computedStyle.opacity || '1') === 0
        ) {
            restoreOverlay();
        }
    });

    observer.observe(document.documentElement, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['class', 'style'],
    });

    window.setInterval(() => {
        const root = document.getElementById('globalWatermarkOverlay');
        if (!root || !document.body.contains(root)) {
            restoreOverlay();
        }
    }, 1200);
};

const initAdminUmkmImport = () => {
    const form = document.querySelector('[data-umkm-import-form]');
    if (!form || !window.axios) return;

    const submitButton = form.querySelector('button[type="submit"]');
    const progressWrap = form.querySelector('[data-umkm-import-progress]');
    const progressBar = form.querySelector('[data-umkm-import-bar]');
    const progressLabel = form.querySelector('[data-umkm-import-label]');
    const progressPercent = form.querySelector('[data-umkm-import-percent]');
    const successBox = form.querySelector('[data-umkm-import-success]');
    const errorBox = form.querySelector('[data-umkm-import-error]');

    const setBusy = (busy) => {
        if (submitButton) submitButton.disabled = !!busy;
        if (submitButton) submitButton.classList.toggle('opacity-60', !!busy);
        if (submitButton) submitButton.classList.toggle('pointer-events-none', !!busy);
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (successBox) successBox.classList.add('hidden');
        if (errorBox) errorBox.classList.add('hidden');

        if (progressWrap) progressWrap.classList.remove('hidden');
        if (progressBar) progressBar.style.width = '0%';
        if (progressLabel) progressLabel.textContent = 'Uploading...';
        if (progressPercent) progressPercent.textContent = '0%';

        setBusy(true);

        const formData = new FormData(form);
        const token = form.querySelector('input[name="_token"]')?.value;

        try {
            const response = await window.axios.post(form.action, formData, {
                headers: {
                    ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                    Accept: 'application/json',
                },
                onUploadProgress: (progressEvent) => {
                    const total = progressEvent.total || 0;
                    const loaded = progressEvent.loaded || 0;
                    const percent = total ? Math.min(100, Math.round((loaded / total) * 100)) : 0;

                    if (progressBar) progressBar.style.width = `${percent}%`;
                    if (progressPercent) progressPercent.textContent = `${percent}%`;

                    if (percent >= 100 && progressLabel) {
                        progressLabel.textContent = 'Processing...';
                    }
                },
            });

            if (successBox) {
                successBox.textContent = response?.data?.message || 'Import selesai.';
                successBox.classList.remove('hidden');
            }

            // Refresh to reflect imported data.
            window.setTimeout(() => window.location.reload(), 900);
        } catch (error) {
            const message =
                error?.response?.data?.message
                || Object.values(error?.response?.data?.errors || {})?.flat()?.[0]
                || 'Import gagal. Coba lagi atau cek log server.';

            if (errorBox) {
                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            }
        } finally {
            setBusy(false);
        }
    });
};

const initAdminUmkmQuickEdit = () => {
    const modalId = 'umkmQuickEditModal';
    const modal = document.getElementById(modalId);
    const form = document.querySelector('[data-umkm-quick-form]');
    if (!modal || !form || !window.axios) return;

    const fields = {
        id: document.getElementById('umkmQuickEditId'),
        nama_umkm: document.getElementById('umkmQuickEditNama'),
        nama_pemilik: document.getElementById('umkmQuickEditPemilik'),
        kab_kota: document.getElementById('umkmQuickEditKabKota'),
        kategori: document.getElementById('umkmQuickEditKategori'),
        approval: document.getElementById('umkmQuickEditApproval'),
        alamat: document.getElementById('umkmQuickEditAlamat'),
        nomor_wa: document.getElementById('umkmQuickEditWa'),
        link_pembelian: document.getElementById('umkmQuickEditLink'),
        status: document.getElementById('umkmQuickEditStatus'),
    };

    const diffBox = document.getElementById('umkmQuickEditDiff');
    const errorBox = document.getElementById('umkmQuickEditError');
    const submitButton = document.getElementById('umkmQuickEditSubmit');
    const submitText = document.getElementById('umkmQuickEditSubmitText');
    const toggleDiffButton = document.getElementById('umkmQuickEditToggleDiff');

    let activeRow = null;
    let original = null;

    const normalize = (value) => (value === null || value === undefined) ? '' : String(value).trim();

    const getCurrentValues = () => ({
        nama_umkm: normalize(fields.nama_umkm?.value),
        nama_pemilik: normalize(fields.nama_pemilik?.value),
        kab_kota: normalize(fields.kab_kota?.value),
        kategori: normalize(fields.kategori?.value),
        approval: normalize(fields.approval?.value),
        alamat: normalize(fields.alamat?.value),
        nomor_wa: normalize(fields.nomor_wa?.value),
        link_pembelian: normalize(fields.link_pembelian?.value),
        status: normalize(fields.status?.value),
    });

    const computeDiff = () => {
        if (!original) return [];

        const current = getCurrentValues();
        const changes = [];

        Object.entries(current).forEach(([key, value]) => {
            const before = normalize(original[key]);
            if (value !== before) {
                changes.push({ key, before, after: value });
            }
        });

        return changes;
    };

    const renderDiff = () => {
        if (!diffBox) return;
        const changes = computeDiff();
        if (!changes.length) {
            diffBox.innerHTML = '<span class="font-semibold text-slate-600">Tidak ada perubahan.</span>';
            return;
        }

        const html = changes.map((item) => {
            const before = item.before === '' ? '<span class="text-slate-400">(kosong)</span>' : `<span class="font-semibold text-slate-700">${escapeHtml(item.before)}</span>`;
            const after = item.after === '' ? '<span class="text-slate-400">(kosong)</span>' : `<span class="font-semibold text-emerald-700">${escapeHtml(item.after)}</span>`;
            return `<div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-200/60 py-2 text-xs last:border-b-0"><span class="font-bold uppercase tracking-[0.12em] text-slate-500">${escapeHtml(item.key)}</span><span>${before} <span class="text-slate-400">→</span> ${after}</span></div>`;
        }).join('');

        diffBox.innerHTML = html;
    };

    const setBusy = (busy) => {
        if (submitButton) submitButton.disabled = !!busy;
        if (submitButton) submitButton.classList.toggle('opacity-60', !!busy);
        if (submitButton) submitButton.classList.toggle('pointer-events-none', !!busy);
        if (submitText) submitText.textContent = busy ? 'Menyimpan...' : 'Simpan Update';
    };

    document.addEventListener('click', (event) => {
        const button = event.target?.closest?.('[data-umkm-quick-edit]');
        if (!button) return;

        const row = button.closest('tr');
        if (!row) return;

        let payload = null;
        try {
            payload = JSON.parse(row.getAttribute('data-umkm') || 'null');
        } catch (e) {
            payload = null;
        }
        if (!payload?.id) return;

        activeRow = row;
        original = payload;

        if (errorBox) errorBox.classList.add('hidden');
        if (diffBox) diffBox.classList.add('hidden');

        if (fields.id) fields.id.value = payload.id ?? '';
        if (fields.nama_umkm) fields.nama_umkm.value = payload.nama_umkm ?? '';
        if (fields.nama_pemilik) fields.nama_pemilik.value = payload.nama_pemilik ?? '';
        if (fields.kab_kota) fields.kab_kota.value = payload.kab_kota ?? '';
        if (fields.kategori) fields.kategori.value = payload.kategori ?? '';
        if (fields.approval) fields.approval.value = payload.approval ?? '';
        if (fields.alamat) fields.alamat.value = payload.alamat ?? '';
        if (fields.nomor_wa) fields.nomor_wa.value = payload.nomor_wa ?? '';
        if (fields.link_pembelian) fields.link_pembelian.value = payload.link_pembelian ?? '';
        if (fields.status) fields.status.value = payload.status ?? 'published';

        window.openModal(modalId);
        renderDiff();
    });

    toggleDiffButton?.addEventListener('click', () => {
        if (!diffBox) return;
        diffBox.classList.toggle('hidden');
        renderDiff();
    });

    form.addEventListener('input', () => {
        renderDiff();
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!original?.id) return;

        if (errorBox) errorBox.classList.add('hidden');

        const changes = computeDiff();
        if (!changes.length) {
            if (errorBox) {
                errorBox.textContent = 'Tidak ada perubahan untuk disimpan.';
                errorBox.classList.remove('hidden');
            }
            return;
        }

        setBusy(true);

        const token = form.querySelector('input[name="_token"]')?.value;
        const formData = new FormData();
        formData.append('_method', 'PUT');
        if (token) formData.append('_token', token);

        changes.forEach((change) => {
            formData.append(change.key, change.after);
        });

        try {
            const response = await window.axios.post(`/admin/umkms/${original.id}`, formData, {
                headers: {
                    ...(token ? { 'X-CSRF-TOKEN': token } : {}),
                    Accept: 'application/json',
                },
            });

            const newData = response?.data?.data;
            if (newData && activeRow) {
                activeRow.setAttribute('data-umkm', JSON.stringify({ ...original, ...newData }));

                const tds = activeRow.querySelectorAll('td');
                // [0]=foto, [1]=source_id, [2]=nama_umkm, [3]=pemilik, [4]=kab_kota, [5]=kategori, [6]=approval, [7]=produk_count, [8]=aksi
                if (tds[1]) tds[1].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.source_id ?? original.source_id ?? '')}</span>`;
                if (tds[2]) tds[2].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.nama_umkm ?? '')}</span>`;
                if (tds[3]) tds[3].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.nama_pemilik ?? '')}</span>`;
                if (tds[4]) tds[4].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.kab_kota ?? '')}</span>`;
                if (tds[5]) tds[5].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.kategori ?? '')}</span>`;
                if (tds[6]) tds[6].innerHTML = `<span class="font-medium text-slate-700">${escapeHtml(newData.approval ?? '')}</span>`;

                original = { ...original, ...newData };
            }

            window.closeModal(modalId);
        } catch (error) {
            const message =
                error?.response?.data?.message
                || Object.values(error?.response?.data?.errors || {})?.flat()?.[0]
                || 'Gagal menyimpan update. Coba lagi.';

            if (errorBox) {
                errorBox.textContent = message;
                errorBox.classList.remove('hidden');
            }
        } finally {
            setBusy(false);
        }
    });
};

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        window.closeModal('searchModal');
        window.closeModal('sehatiModal');
        window.closeModal('umkmImportModal');
        window.closeModal('umkmExportModal');
        window.closeModal('umkmQuickEditModal');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    createIcons();
    initNavbarScroll();
    initCounters();
    initSearchModal();
    initMap();
    initAdminMapPickers();
    initAggressiveWatermark();
    initAdminUmkmImport();
    initAdminUmkmQuickEdit();

    document.querySelectorAll('[data-open-on-load="true"]').forEach((element) => {
        if (element.id) {
            window.openModal(element.id);
        }
    });
});
