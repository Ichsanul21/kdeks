const DataStatistik = (() => {
    'use strict';

    const state = { tab: 'daerah', period: 5, sgieSector: 'overall', marketSector: 'food', nasView: 'eksekutif' };
    let charts = {};
    const CY = 2025;

    const C = {
        emerald:'#10b981', blue:'#3b82f6', amber:'#f59e0b', violet:'#8b5cf6',
        rose:'#f43f5e', cyan:'#06b6d4', teal:'#14b8a6', indigo:'#6366f1', pink:'#ec4899',
        slate:'#64748b', orange:'#f97316'
    };
    const FONT = '#94a3b8';
    const GRID = 'rgba(226,232,240,0.6)';
    const mob = () => window.innerWidth < 640;

    const fmt = n => n.toLocaleString('id-ID');
    const slice = (arr, p) => arr.slice(-(p === 1 ? 2 : p));

    function periodLabel(p) {
        const from = CY - (p === 1 ? 1 : p - 1);
        return from >= CY ? `${CY}` : `${from} – ${CY}`;
    }

    function hexRgba(hex, a) {
        const r = parseInt(hex.slice(1,3),16);
        const g = parseInt(hex.slice(3,5),16);
        const b = parseInt(hex.slice(5,7),16);
        return `rgba(${r},${g},${b},${a})`;
    }

    function tooltipCfg(extra) {
        return Object.assign({ backgroundColor:'#0f172a', padding:12, cornerRadius:10, titleFont:{weight:'700'} }, extra);
    }
    function legendCfg(pos='top') {
        return { position:pos, align:'end', labels:{ boxWidth:10, boxHeight:10, borderRadius:3, useBorderRadius:true, padding:mob()?10:16, font:{weight:'600',size:mob()?10:11} }};
    }
    function barLegendCfg() {
        return { position:'bottom', labels:{ boxWidth:10, boxHeight:10, borderRadius:3, useBorderRadius:true, padding:mob()?12:20, font:{weight:'600',size:mob()?10:11} }};
    }
    function lineScale(unit) {
        return {
            x:{ grid:{display:false} },
            y:{ beginAtZero:true, grid:{color:GRID,drawBorder:false}, ticks:{ callback:v=> unit==='K'?(v/1000)+'K': unit==='$'?'$'+v+'M': unit==='T'?(v/1000).toFixed(1)+'K T':v }}
        };
    }

    function makeLine(data, color, fill=true, dashed=false) {
        const ds = {
            data, borderColor:color,
            backgroundColor: fill ? hexRgba(color,0.08) : 'transparent',
            fill, tension:0.4, borderWidth: dashed?1.5:2.5,
            pointBackgroundColor:color, pointBorderColor:'#fff', pointBorderWidth:2,
        };
        if (dashed) { ds.borderDash=[6,4]; ds.pointRadius=0; ds.pointHoverRadius=4; }
        else { ds.pointRadius=mob()?3:4; ds.pointHoverRadius=6; }
        return ds;
    }

    // ── DATA NASIONAL (SGIE 2025 & LAINNYA) ───────
    const N_DATA = {
        sgie: {
            overall: { labels: ["Malaysia", "Saudi Arabia", "Indonesia", "UAE", "Bahrain", "Jordan", "Kuwait", "Pakistan", "Türkiye", "Qatar", "Oman", "Singapore", "Iran", "UK", "Bangladesh"], data: [165.1, 100.9, 99.9, 95.8, 81.9, 71.4, 67.0, 64.1, 64.0, 60.4, 58.0, 57.0, 52.3, 47.6, 45.9] },
            food: { labels: ["Malaysia", "Singapore", "UAE", "Indonesia", "Jordan", "UK", "Türkiye", "Saudi Arabia", "Pakistan", "Bahrain", "Oman", "Qatar", "Kuwait", "Iran", "Bangladesh"], data: [117.0, 89.8, 84.1, 78.8, 65.0, 63.2, 62.2, 59.8, 59.6, 59.6, 55.5, 49.2, 47.7, 44.7, 35.6] },
            finance: { labels: ["Malaysia", "Saudi Arabia", "Bahrain", "UAE", "Kuwait", "Indonesia", "Jordan", "Pakistan", "Oman", "Iran", "Qatar", "Bangladesh", "Türkiye", "UK", "Singapore"], data: [282.6, 201.6, 145.4, 141.9, 139.3, 135.9, 121.0, 115.2, 95.1, 92.7, 91.6, 77.9, 66.6, 23.2, 20.9] },
            travel: { labels: ["Malaysia", "Indonesia", "Saudi Arabia", "UAE", "Türkiye", "Bahrain", "Jordan", "Singapore", "Qatar", "Iran", "Pakistan", "UK", "Oman", "Bangladesh", "Kuwait"], data: [136.8, 102.4, 91.1, 89.3, 88.5, 66.5, 63.3, 55.3, 48.8, 38.4, 37.6, 36.0, 35.7, 34.8, 28.5] },
            media: { labels: ["Malaysia", "UK", "Qatar", "UAE", "Bahrain", "Singapore", "Indonesia", "Saudi Arabia", "Türkiye", "Oman", "Kuwait", "Jordan", "Iran", "Pakistan", "Bangladesh"], data: [102.4, 75.6, 72.2, 66.3, 64.0, 60.0, 59.5, 49.2, 45.7, 36.4, 27.4, 27.4, 25.4, 15.4, 14.0] },
            pharma: { labels: ["Malaysia", "Indonesia", "UAE", "Singapore", "Türkiye", "Saudi Arabia", "UK", "Bahrain", "Jordan", "Qatar", "Oman", "Kuwait", "Iran", "Pakistan", "Bangladesh"], data: [136.1, 85.8, 73.1, 68.7, 55.5, 52.6, 52.1, 43.6, 43.3, 35.5, 34.1, 33.7, 32.7, 28.0, 26.3] },
            fashion: { labels: ["Indonesia", "Malaysia", "Türkiye", "Singapore", "UK", "UAE", "Bangladesh", "Pakistan", "Saudi Arabia", "Bahrain", "Qatar", "Jordan", "Oman", "Kuwait", "Iran"], data: [106.8, 76.7, 63.6, 54.1, 52.8, 50.9, 43.6, 35.3, 32.0, 30.3, 25.1, 22.5, 22.3, 19.3, 13.9] }
        },
        gmti: [
            ["Malaysia",76,1], ["Indonesia",76,1], ["Saudi Arabia",74,3], ["Turkiye",73,4], ["UAE",72,5],
            ["Qatar",71,6], ["Iran",67,7], ["Jordan",67,7], ["Oman",66,9], ["Singapore",66,9],
            ["Brunei",66,9], ["Egypt",65,12], ["Kuwait",65,12], ["Uzbekistan",64,14], ["Maldives",64,14]
        ],
        pdb_tahunan: {
            years: [2019, 2020, 2021, 2022, 2023, 2024, 2025],
            adhb: [15832.6, 15443.3, 16976.7, 19588.4, 20892.3, 22138.9, 23821.1],
            adhk: [10949.1, 10722.9, 11120.0, 11710.2, 12301.4, 12920.5, 13580.5]
        },
        pdb_sektor: {
            labels: ["Industri Pengolahan", "Perdagangan", "Pertanian", "Konstruksi", "Pertambangan", "Transportasi", "Lainnya"],
            data: [4541.5, 3136.5, 3120.4, 2340.9, 2084.6, 1466.2, 7131.0]
        },
        aktivitas: {
            labels: ["2019", "2020", "2021", "2022", "2023", "2024", "2025"],
            nilai: [12000, 11765, 12911, 15015, 16360, 16780, 17429],
            pangsa: [0.479, 0.455, 0.464, 0.478, 0.486, 0.475, 0.478]
        },
        aset_global: {
            labels: ["Iran", "Saudi Arabia", "Malaysia", "UAE", "Qatar", "Kuwait", "Indonesia", "Bahrain", "Turkiye", "Bangladesh"],
            data: [1235, 896, 650, 252, 186, 153, 139, 106, 71, 58]
        },
        market: {
            food: { labels: ["Indonesia", "Mesir", "Bangladesh", "Nigeria", "Iran"], data: [149.8, 143.0, 137.0, 87.4, 87.4] },
            fashion: { labels: ["Iran", "Turkiye", "Saudi Arabia", "Pakistan", "Egypt"], data: [57.0, 36.7, 24.3, 23.8, 22.7] },
            travel: { labels: ["Turki", "UAE", "Arab Saudi", "Mesir", "Tunisia"], data: [21.1, 11.1, 11.0, 5.2, 4.5] },
            pharma: { labels: ["Turki", "Arab Saudi", "USA", "Indonesia", "Algeria"], data: [10.5, 9.5, 8.2, 6.1, 4.5] }
        },
        sertifikasi: {
            labels: ["2019", "2020", "2021", "2022", "2023", "2024", "2025"],
            reguler: [2, 5657, 16827, 18198, 25422, 91472, 100604],
            self: [0, 0, 750, 89560, 1207768, 2009463, 2215050]
        },
        ekspor: {
            labels: ["2019", "2020", "2021", "2022", "2023", "2024", "2025"],
            data: [37288, 40022, 57038, 61597, 50585, 51562, 63421]
        },
        ekspor_sektor: {
            labels: ["Makanan & Minuman", "Tekstil", "Farmasi", "Kosmetik"],
            data: [84.39, 13.69, 1.14, 0.78]
        },
        ekspor_negara: [
            {name:"United States", value:1678}, {name:"China", value:1382},
            {name:"India", value:704}, {name:"Pakistan", value:619}, {name:"Malaysia", value:541}
        ],
        iknb: {
            years: ["2020", "2021", "2022", "2023", "2024", "2025"],
            asuransi: [44.4, 43.5, 45.0, 44.2, 46.5, 50.9],
            pembiayaan: [21.9, 23.5, 33.1, 31.4, 33.8, 36.8],
            lainnya: [219.0, 242.0, 245.3, 276.9, 307.0, 92.4]
        },
        literasi: {
            years: [2019, 2021, 2022, 2023, 2024, 2025],
            index: [0.163, 0.200, 0.233, 0.280, 0.428, 0.501]
        }
    };

    const DATA = {
        daerah: {
            stats:[12847,384,1253,14],
            sgie:{
                labels:['Halal Food','Islamic Finance','Muslim-Friendly Travel','Media & Recreation','Halal Pharma & Cosmetics','Modest Fashion','Global (Rata-rata)'],
                short:['Halal Food','Islamic Finance','Muslim Travel','Media','Pharma & Cosmetics','Modest Fashion','Global Avg'],
                data:[72,58,64,41,53,48,56],
            },
            sertifikasi:{ years:['2020','2021','2022','2023','2024','2025*'], values:[1890,3420,5680,8150,10840,12847] },
            ekspor:{ years:['2020','2021','2022','2023','2024','2025*'], values:[38,67,94,128,156,183] },
            pariwisataBar:{
                labels:['Kota Samarinda','Kota Balikpapan','Kota Bontang','Kutai Kartanegara','Penajam Paser Utara','Kutai Timur','Paser','Berau','Mahakam Ulu'],
                short:['Samarinda','Balikpapan','Bontang','Kukar','PPU','Kutim','Paser','Berau','Mahulu'],
                data:[387,324,168,112,78,64,52,41,27],
            },
            umkmSebaran:[
                {name:'Kaltim',value:1253,color:'emerald'},{name:'Kalsel',value:987,color:'blue'},
                {name:'Kaltara',value:324,color:'amber'},{name:'Kalbar',value:256,color:'violet'},
                {name:'Kalteng',value:198,color:'rose'},
            ],
            lph:{ years:['2020','2021','2022','2023','2024','2025*'], lph:[3,5,7,9,11,14], auditor:[18,34,52,73,98,124] },
            lphKomposisi:{ labels:['LPH UMK','LPH Pemerintah','LPH Blended'], data:[8,3,3], colors:['rgba(139,92,246,0.8)','rgba(6,182,212,0.8)','rgba(245,158,11,0.8)'] },
            rphKomposisi:{ labels:['Pemerintah','Swasta','BUMD'], data:[5,7,2], colors:['rgba(239,68,68,0.8)','rgba(59,130,246,0.8)','rgba(16,185,129,0.8)'] },
            rphSebaran:[
                {name:'Kaltim',value:14,color:'emerald'},{name:'Kalsel',value:11,color:'blue'},
                {name:'Kaltara',value:7,color:'amber'},{name:'Kalbar',value:9,color:'violet'},
                {name:'Kalteng',value:6,color:'rose'},
            ],
            umk:{ years:['2020','2021','2022','2023','2024','2025*'], reguler:[210,680,1420,2890,4350,5640], selfDeclare:[0,0,180,620,1840,3200] },
            umkInfo:[87,12,'94,2%'],
            locLabel:'Kalimantan Timur', locChartLabel:'Kabupaten/Kota', totalLabel:'Total Kalimantan',
        },
    };

    // ── DOM Updates ───────────────────────────────
    function updateTexts() {
        const n = state.tab === 'nasional';
        const d = DATA.daerah;

        document.getElementById('sectionLabel').textContent = n ? 'Data KNEKS' : 'Data KDEKS';
        document.getElementById('sectionTitle').textContent  = n ? 'Nasional' : 'Kalimantan Timur';
        document.getElementById('sectionDesc').textContent   = n
            ? 'Kumpulan data statistik ekonomi syariah nasional berdasarkan laporan SGIE 2025, KNEKS, dan indikator global lainnya.'
            : 'Kumpulan data statistik terkini seputar ekosistem syariah, industri halal, dan komitmen KDEKS di Provinsi Kalimantan Timur.';

        document.getElementById('statsNasional').classList.toggle('hidden', !n);
        document.getElementById('statsDaerah').classList.toggle('hidden', n);
        document.getElementById('contentNasional').classList.toggle('hidden', !n);
        document.getElementById('contentDaerah').classList.toggle('hidden', n);

        if (!n) {
            document.getElementById('descIndustri').innerHTML     = 'Perkembangan sertifikasi & nilai ekspor produk halal <span class="font-semibold text-slate-600">' + d.locLabel + '</span>';
            document.getElementById('descPariwisata').innerHTML    = 'UMKM bersertifikat halal di <span class="font-semibold text-slate-600">' + d.locLabel + '</span>';
            document.getElementById('titlePariwisataBar').textContent = 'Jumlah Sertifikat per ' + d.locChartLabel;

            document.getElementById('stat0').textContent = fmt(d.stats[0]);
            document.getElementById('stat1').textContent = fmt(d.stats[1]);
            document.getElementById('stat2').textContent = fmt(d.stats[2]);
            document.getElementById('stat3').textContent = fmt(d.stats[3]);

            document.getElementById('umkInfo0').textContent = fmt(d.umkInfo[0]);
            document.getElementById('umkInfo1').textContent = fmt(d.umkInfo[1]);
            document.getElementById('umkInfo2').textContent = d.umkInfo[2];
        }

        document.getElementById('periodText').textContent = periodLabel(state.period);
    }

    function renderNasionalPanels() {
        // GMTI Panel
        const gmtiHtml = N_DATA.gmti.map((it, idx) => `
            <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50/50 p-3">
                <div class="flex items-center gap-3">
                    <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-emerald-100 text-[10px] font-bold text-emerald-700">${it[2]}</span>
                    <span class="text-xs font-semibold text-slate-700">${it[0]}</span>
                </div>
                <span class="text-xs font-bold text-slate-900">${it[1]}</span>
            </div>
        `).join('');
        document.getElementById('panelGMTI').innerHTML = gmtiHtml;

        // Ekspor Negara Panel
        const eksporHtml = N_DATA.ekspor_negara.map(it => `
            <div class="flex items-center justify-between">
                <span class="text-xs text-slate-500">${it.name}</span>
                <div class="flex items-center gap-3">
                    <div class="h-1.5 w-24 overflow-hidden rounded-full bg-slate-100 sm:w-32">
                        <div class="h-full bg-amber-400" style="width:${(it.value/1678*100)}%"></div>
                    </div>
                    <span class="text-xs font-bold text-slate-700">$${it.value}M</span>
                </div>
            </div>
        `).join('');
        document.getElementById('panelEksporNegara').innerHTML = eksporHtml;
    }

    function renderDaerahPanels() {
        const d = DATA.daerah;
        // Pariwisata
        document.getElementById('panelPariwisata').querySelector('.relative.z-10').innerHTML = d.umkmSebaran.map(it => `
            <div class="flex items-center justify-between rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2.5 backdrop-blur-sm sm:px-4 sm:py-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full" style="background-color:${C[it.color]}"></span>
                    <span class="text-[11px] font-semibold text-slate-700 sm:text-xs">${it.name}</span>
                </div>
                <span class="text-sm font-extrabold text-slate-900">${fmt(it.value)}</span>
            </div>
        `).join('');

        // RPH
        const items = d.rphSebaran;
        const total = items.reduce((s,i) => s + i.value, 0);
        const cards = items.map(it => `
            <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/80 bg-white/80 p-2.5 backdrop-blur-sm sm:p-3">
                <span class="text-xl font-extrabold text-slate-900 sm:text-2xl">${it.value}</span>
                <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider sm:text-[10px]" style="color:${C[it.color]}">${it.name}</span>
            </div>
        `).join('');
        const totalCard = `
            <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50/50 p-2.5 backdrop-blur-sm sm:p-3">
                <span class="text-xl font-extrabold text-slate-400 sm:text-2xl">${fmt(total)}</span>
                <span class="mt-0.5 text-[9px] font-bold uppercase tracking-wider text-slate-400 sm:text-[10px]">${d.totalLabel}</span>
            </div>`;
        document.getElementById('panelRPH').querySelector('.relative.z-10').innerHTML = cards + totalCard;
    }

    // ── Chart Creators ────────────────────────────
    function createDaerahCharts() {
        const d = DATA.daerah;
        const m = mob();
        
        charts.sgie = new Chart(document.getElementById('chartSGIE'), {
            type:'bar', data:{ labels: m ? d.sgie.short : d.sgie.labels, datasets:[{ label:'Skor SGIE 2025', data:d.sgie.data, backgroundColor:['rgba(16,185,129,0.85)','rgba(16,185,129,0.7)','rgba(16,185,129,0.6)','rgba(16,185,129,0.48)','rgba(16,185,129,0.38)','rgba(16,185,129,0.28)','rgba(100,116,139,0.25)'], borderColor:'transparent', borderRadius:6, barThickness: m?18:28 }] },
            options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg({callbacks:{label:c=>`Skor: ${c.parsed.x}/100`}}) }, scales:{ x:{beginAtZero:true,max:100,grid:{color:GRID}}, y:{grid:{display:false},ticks:{font:{weight:'600'}}} } }
        });

        charts.sertifikasi = new Chart(document.getElementById('chartSertifikasi'), {
            type:'line', data:{ labels:slice(d.sertifikasi.years,state.period), datasets:[makeLine(slice(d.sertifikasi.values,state.period),C.emerald)] },
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:lineScale('K') }
        });

        charts.ekspor = new Chart(document.getElementById('chartEkspor'), {
            type:'line', data:{ labels:slice(d.ekspor.years,state.period), datasets:[makeLine(slice(d.ekspor.values,state.period),C.blue)] },
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:lineScale('$') }
        });

        charts.pariwisata = new Chart(document.getElementById('chartPariwisata'), {
            type:'bar', data:{ labels:m?d.pariwisataBar.short:d.pariwisataBar.labels, datasets:[{ label:'Sertifikat', data:d.pariwisataBar.data, backgroundColor:C.amber, borderRadius:5, barThickness:m?16:22 }] },
            options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{beginAtZero:true}, y:{grid:{display:false}} } }
        });

        charts.lphLine = new Chart(document.getElementById('chartLPHLine'), {
            type:'line', data:{ labels:slice(d.lph.years,state.period), datasets:[ { label:'Jumlah LPH', ...makeLine(slice(d.lph.lph,state.period),C.violet) }, { label:'Auditor', ...makeLine(slice(d.lph.auditor,state.period),C.cyan) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:legendCfg(), tooltip:tooltipCfg() }, scales:lineScale('') }
        });

        charts.lphPie = new Chart(document.getElementById('chartLPHPie'), {
            type:'doughnut', data:{ labels:d.lphKomposisi.labels, datasets:[{data:d.lphKomposisi.data,backgroundColor:d.lphKomposisi.colors,borderWidth:3}] },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'62%', plugins:{ legend:barLegendCfg(), tooltip:tooltipCfg() } }
        });

        charts.rphPie = new Chart(document.getElementById('chartRPHPie'), {
            type:'doughnut', data:{ labels:d.rphKomposisi.labels, datasets:[{data:d.rphKomposisi.data,backgroundColor:d.rphKomposisi.colors,borderWidth:3}] },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'62%', plugins:{ legend:barLegendCfg(), tooltip:tooltipCfg() } }
        });

        const yrs = slice(d.umk.years,state.period);
        const reg = slice(d.umk.reguler,state.period);
        const sd  = slice(d.umk.selfDeclare,state.period);
        const tot = reg.map((v,i)=>v+sd[i]);
        charts.umk = new Chart(document.getElementById('chartUMK'), {
            type:'line', data:{ labels:yrs, datasets:[ { label:'SH Reguler', ...makeLine(reg,C.teal) }, { label:'Self-Declare', ...makeLine(sd,C.orange) }, { label:'Total', ...makeLine(tot,C.slate,false,true) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:legendCfg(), tooltip:tooltipCfg() }, scales:lineScale('K') }
        });
    }

    function createNasionalCharts() {
        const m = mob();
        
        // SGIE Bar
        const sData = N_DATA.sgie[state.sgieSector];
        charts.nasSgie = new Chart(document.getElementById('chartNasSGIE'), {
            type:'bar', data:{ labels:sData.labels, datasets:[{ label:'Skor SGIE 2025', data:sData.data, backgroundColor:hexRgba(C.emerald, 0.7), borderRadius:4, barThickness: m?12:20 }] },
            options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{beginAtZero:true}, y:{grid:{display:false}, ticks:{font:{size:m?9:11}}} } }
        });

        // PDB Line
        charts.nasPdb = new Chart(document.getElementById('chartNasPDB'), {
            type:'line', data:{ labels:slice(N_DATA.pdb_tahunan.years, state.period), datasets:[ { label:'ADHB', ...makeLine(slice(N_DATA.pdb_tahunan.adhb, state.period), C.blue) }, { label:'ADHK', ...makeLine(slice(N_DATA.pdb_tahunan.adhk, state.period), C.violet) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:legendCfg(), tooltip:tooltipCfg() }, scales:lineScale('T') }
        });

        // PDB Sektor Bar
        charts.nasPdbSektor = new Chart(document.getElementById('chartNasPDBSektor'), {
            type:'bar', data:{ labels:N_DATA.pdb_sektor.labels, datasets:[{ data:N_DATA.pdb_sektor.data, backgroundColor:[C.emerald, C.blue, C.amber, C.violet, C.rose, C.cyan, C.slate], borderRadius:5 }] },
            options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{display:false}, y:{grid:{display:false}, ticks:{font:{size:m?9:11}}} } }
        });

        // Sertifikasi Line
        const nSert = N_DATA.sertifikasi;
        charts.nasSert = new Chart(document.getElementById('chartNasSertifikasi'), {
            type:'line', data:{ labels:slice(nSert.labels, state.period), datasets:[ { label:'Reguler', ...makeLine(slice(nSert.reguler, state.period), C.emerald) }, { label:'Self-Declare', ...makeLine(slice(nSert.self, state.period), C.orange) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:legendCfg(), tooltip:tooltipCfg() }, scales:lineScale('K') }
        });

        // Ekspor Line
        charts.nasEkspor = new Chart(document.getElementById('chartNasEkspor'), {
            type:'line', data:{ labels:slice(N_DATA.ekspor.labels, state.period), datasets:[ makeLine(slice(N_DATA.ekspor.data, state.period), C.amber) ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:lineScale('K') }
        });

        // Ekspor Sektor Doughnut
        charts.nasEksporSektor = new Chart(document.getElementById('chartNasEksporSektor'), {
            type:'doughnut', data:{ labels:N_DATA.ekspor_sektor.labels, datasets:[{ data:N_DATA.ekspor_sektor.data, backgroundColor:[C.emerald, C.blue, C.violet, C.rose], borderWidth:2 }] },
            options:{ responsive:true, maintainAspectRatio:false, cutout:'65%', plugins:{ legend:barLegendCfg(), tooltip:tooltipCfg() } }
        });

        // Aktivitas Line
        charts.nasAktivitas = new Chart(document.getElementById('chartNasAktivitas'), {
            type:'line', data:{ labels:slice(N_DATA.aktivitas.labels, state.period), datasets:[ { label:'Nilai Pembiayaan', ...makeLine(slice(N_DATA.aktivitas.nilai, state.period), C.violet) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:lineScale('') }
        });

        // Aset Global Bar
        charts.nasAsetGlobal = new Chart(document.getElementById('chartNasAsetGlobal'), {
            type:'bar', data:{ labels:N_DATA.aset_global.labels, datasets:[{ label:'Total Aset (US$ B)', data:N_DATA.aset_global.data, backgroundColor:hexRgba(C.blue,0.6), borderRadius:4 }] },
            options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{beginAtZero:true}, y:{grid:{display:false}} } }
        });

        // IKNB Line
        charts.nasIknb = new Chart(document.getElementById('chartNasIKNB'), {
            type:'line', data:{ labels:slice(N_DATA.iknb.years, state.period), datasets:[ { label:'Asuransi', ...makeLine(slice(N_DATA.iknb.asuransi, state.period), C.emerald) }, { label:'Pembiayaan', ...makeLine(slice(N_DATA.iknb.pembiayaan, state.period), C.blue) } ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:legendCfg(), tooltip:tooltipCfg() }, scales:lineScale('') }
        });

        // Market Bar
        const mData = N_DATA.market[state.marketSector];
        charts.nasMarket = new Chart(document.getElementById('chartNasMarket'), {
            type:'bar', data:{ labels:mData.labels, datasets:[{ label:'Nilai (US$ B)', data:mData.data, backgroundColor:C.rose, borderRadius:5 }] },
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{grid:{display:false}}, y:{beginAtZero:true} } }
        });

        // Literasi Line
        charts.nasLiterasi = new Chart(document.getElementById('chartNasLiterasi'), {
            type:'line', data:{ labels:slice(N_DATA.literasi.years, state.period), datasets:[ makeLine(slice(N_DATA.literasi.index, state.period), C.teal) ]},
            options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ y:{beginAtZero:true, max:1} } }
        });
    }

    // ── Render ────────────────────────────────────
    function render() {
        Object.values(charts).forEach(c => { if(c) c.destroy(); });
        charts = {};
        
        updateTexts();
        if (state.tab === 'nasional') {
            updateNasUI();
            if (state.nasView === 'eksekutif') {
                renderNasionalPanels();
                createNasionalCharts();
            }
        } else {
            renderDaerahPanels();
            createDaerahCharts();
        }

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    // ── UI Toggle ─────────────────────────────────
    function updateUI() {
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.toggle('is-active', b.dataset.tab === state.tab);
        });
        document.querySelectorAll('.period-btn').forEach(b => {
            b.classList.toggle('is-active', b.dataset.period === String(state.period));
        });
    }

    function updateNasUI() {
        document.querySelectorAll('.nas-nav-btn, .nas-sub-nav-btn').forEach(btn => {
            btn.classList.toggle('is-active', btn.dataset.nasView === state.nasView);
        });

        const isEksekutif = state.nasView === 'eksekutif';
        const eksekutifView = document.getElementById('nasView-eksekutif');
        const placeholderView = document.getElementById('nasView-placeholder');

        if (eksekutifView) eksekutifView.classList.toggle('hidden', !isEksekutif);
        if (placeholderView) placeholderView.classList.toggle('hidden', isEksekutif);

        if (!isEksekutif) {
            const btn = document.querySelector(`[data-nas-view="${state.nasView}"]`);
            const label = document.getElementById('nasActiveCategoryName');
            if (btn && label) {
                label.textContent = btn.textContent.trim();
            }
        }
    }

    function resetNasView() {
        state.nasView = 'eksekutif';
        // Auto-expand the dashboard item if needed
        document.querySelectorAll('.nas-accordion-item').forEach(i => i.classList.remove('is-open'));
        updateNasUI();
        render();
    }

    // ── Events ────────────────────────────────────
    function bindEvents() {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                if (state.tab === btn.dataset.tab) return;
                state.tab = btn.dataset.tab;
                updateUI();
                render();
            });
        });
        document.querySelectorAll('.period-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const p = parseInt(btn.dataset.period);
                if (state.period === p) return;
                state.period = p;
                updateUI();
                render();
            });
        });

        // Nasional specific events
        document.addEventListener('change', (e) => {
            if (e.target && e.target.id === 'sgieSelector') {
                state.sgieSector = e.target.value;
                if(charts.nasSgie) charts.nasSgie.destroy();
                // We only need to re-create nasSgie
                const sData = N_DATA.sgie[state.sgieSector];
                charts.nasSgie = new Chart(document.getElementById('chartNasSGIE'), {
                    type:'bar', data:{ labels:sData.labels, datasets:[{ label:'Skor SGIE 2025', data:sData.data, backgroundColor:hexRgba(C.emerald, 0.7), borderRadius:4, barThickness: mob()?12:20 }] },
                    options:{ indexAxis:'y', responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{beginAtZero:true}, y:{grid:{display:false}, ticks:{font:{size:mob()?9:11}}} } }
                });
            }
        });

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.market-tab-btn');
            if (btn) {
                state.marketSector = btn.dataset.market;
                document.querySelectorAll('.market-tab-btn').forEach(b => b.classList.toggle('is-active', b.dataset.market === state.marketSector));
                if(charts.nasMarket) charts.nasMarket.destroy();
                const mData = N_DATA.market[state.marketSector];
                charts.nasMarket = new Chart(document.getElementById('chartNasMarket'), {
                    type:'bar', data:{ labels:mData.labels, datasets:[{ label:'Nilai (US$ B)', data:mData.data, backgroundColor:C.rose, borderRadius:5 }] },
                    options:{ responsive:true, maintainAspectRatio:false, plugins:{ legend:{display:false}, tooltip:tooltipCfg() }, scales:{ x:{grid:{display:false}}, y:{beginAtZero:true} } }
                });
            }
        });

        let rt;
        window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(render, 300); });
    }

    function bindNasEvents() {
        // Accordion Toggle
        document.querySelectorAll('.nas-accordion-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.nas-accordion-item');
                const isOpen = item.classList.contains('is-open');
                
                document.querySelectorAll('.nas-accordion-item').forEach(i => i.classList.remove('is-open'));
                
                if (!isOpen) {
                    item.classList.add('is-open');
                }
            });
        });

        // Main Nav Clicks
        document.querySelectorAll('.nas-nav-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const view = btn.dataset.nasView;
                if (state.nasView === view) return;
                
                // Close accordions when going to executive dashboard
                document.querySelectorAll('.nas-accordion-item').forEach(i => i.classList.remove('is-open'));
                
                state.nasView = view;
                render();
            });
        });

        // Sub Nav Clicks
        document.querySelectorAll('.nas-sub-nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const view = btn.dataset.nasView;
                if (state.nasView === view) return;

                state.nasView = view;
                render();
            });
        });
    }

    // ── Init ──────────────────────────────────────
    function init() {
        Chart.defaults.font.family = "'Inter','Satoshi',system-ui,sans-serif";
        Chart.defaults.font.size = mob() ? 10 : 11;
        Chart.defaults.color = FONT;
        bindEvents();
        bindNasEvents();
        updateUI();
        render();
    }

    return { init, resetNasView };
})();

document.addEventListener('DOMContentLoaded', () => DataStatistik.init());
