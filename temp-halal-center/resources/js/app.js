import './bootstrap';

import Alpine from 'alpinejs';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

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
                                    ? items.map((item) => `<div class="text-sm font-semibold text-slate-800">${escapeHtml(item.title ?? item.name)}</div>`).join('')
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
    if (!mapElement) return;

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
    const partnerFilter = document.getElementById('mapPartnerFilter');
    const searchInput = document.getElementById('mapSearchInput');
    const categoryButtons = document.querySelectorAll('[data-map-category]');
    const zoomButtons = document.querySelectorAll('[data-map-zoom]');
    const markersLayer = L.layerGroup().addTo(map);

    let state = {
        category: '',
        city: '',
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
            const regions = result.data.regions ?? [];
            const locations = result.data.locations ?? [];

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
                            <button class="rounded bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-700 transition hover:bg-emerald-50 hover:text-emerald-600">Detail</button>
                        </div>
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
                        <button class="w-full rounded border border-slate-200 bg-slate-100 px-2 py-1.5 text-[10px] font-bold text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50 hover:text-cyan-600">Lihat Produk</button>
                    </div>
                `;

                L.marker([location.latitude, location.longitude], { icon: businessIcon })
                    .bindPopup(popupContent)
                    .addTo(markersLayer);
            });
        } catch (error) {
            markersLayer.clearLayers();
        }
    };

    cityFilter?.addEventListener('change', () => {
        state.city = cityFilter.value;
        renderMap();
    });

    partnerFilter?.addEventListener('change', () => {
        state.lph_partner_id = partnerFilter.value;
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

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        window.closeModal('searchModal');
        window.closeModal('sehatiModal');
    }
});

document.addEventListener('DOMContentLoaded', () => {
    createIcons();
    initNavbarScroll();
    initCounters();
    initSearchModal();
    initMap();

    document.querySelectorAll('[data-open-on-load="true"]').forEach((element) => {
        if (element.id) {
            window.openModal(element.id);
        }
    });
});
