const DataStatistik = (() => {
    'use strict';

    const state = { tab: 'daerah', period: 5, sgieSector: 'overall', marketSector: 'food', nasView: 'eksekutif', dsYear: '2025', perbankanAsetPage: 1, perbankanDpkPage: 1, perbankanPydPage: 1, payrollPage: 1, kpbuPage: 1, pasarModalPage: 1, ponpesPage: 1, bumdesPage: 1, masjidPage: 1 };
    let charts = {};
    let nasMap = null;
    let nasMapRPH = null;
    let nasMapKhas = null;
    let nasMapDpk = null;
    let nasMapPyd = null;
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
            x: { grid: { display: false } },
            y: {
                beginAtZero: true, grid: { color: GRID, drawBorder: false },
                ticks: {
                    callback: v => {
                        if (unit === 'K') return (v / 1000) + 'K';
                        if (unit === '$') return '$' + v + 'M';
                        if (unit === 'T') return (v / 1000).toFixed(1) + 'K T';
                        if (unit === '%') return v + '%';
                        return v;
                    }
                }
            }
        };
    }

    function makeLine(data, color, fill=true, dashed=false) {
        const ds = {
            data, borderColor:color,
            backgroundColor: fill ? hexRgba(color,0.08) : 'transparent',
            fill, tension:0.4, borderWidth: dashed?1.5:2.5,
            pointBackgroundColor:color, pointBorderColor:'#fff', pointBorderWidth:2,
        };
        if (dashed) { ds.borderDash=[6,4]; ds.pointRadius=4; ds.pointHoverRadius=6; }
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
            years: ["2019", "2021", "2022", "2023", "2024", "2025"],
            index: [0.163, 0.2002, 0.2330, 0.2801, 0.4284, 0.5018]
        },
        daya_saing: {
            years: ["2025", "2026", "2027", "2028", "2029"],
            target: [4.46, 5.19, 6.23, 6.69, 7.00],
            realisasi: [4.55, null, null, null, null]
        },
        pariwisata: {
            restoran: {
                labels: ["DKI Jakarta", "Jawa Barat", "Jawa Timur", "Jawa Tengah", "Banten"],
                data: [278, 245, 173, 115, 109]
            },
            hotel: [
                {name: "Hotel Grand Palma by Horison", exp: "15 Apr 2028", status: "Masih Berlaku"},
                {name: "Unisi Hotel Malioboro", exp: "11 Nov 2027", status: "Masih Berlaku"},
                {name: "Hotel Lorin Sentul", exp: "24 Sep 2026", status: "Masih Berlaku"},
                {name: "Hotel Mangkuto Payakumbuh", exp: "11 Des 2025", status: "Kedaluwarsa"},
                {name: "CV Hotel Grand Permata Hati", exp: "19 Des 2021", status: "Kedaluwarsa"},
                {name: "CV Permata Hati", exp: "19 Des 2021", status: "Kedaluwarsa"},
                {name: "T Sofyan Hotels Tbk", exp: "21 Mar 2021", status: "Kedaluwarsa"}
            ]
        },
        perbankan_syariah: {
            total_aset: 1035.05,
            market_share: { labels: ["Konvensional", "Syariah"], data: [92.41, 7.59] },
            perbankan_table: [
                ["November 2025", 1035.05, 7.59], ["Oktober 2025", 1028.18, 7.64], ["September 2025", 1006.18, 7.67], ["Agustus 2025", 975.94, 7.44],
                ["Juli 2025", 965.2, 7.41], ["Juni 2025", 967.33, 7.41], ["Mei 2025", 942.71, 7.31], ["April 2025", 954.51, 7.44],
                ["Maret 2025", 960.82, 7.42], ["Februari 2025", 874.51, 7.46], ["Januari 2025", 948.21, 7.50], ["Desember 2024", 980.29, 7.72],
                ["Desember 2023", 892.17, 7.39], ["Desember 2022", 802.26, 7.09], ["Desember 2021", 693.8, 6.74], ["Desember 2020", 608.89, 6.51]
            ],
            rekening: {
                labels: ["Jan 2020", "Jan 2021", "Jan 2022", "Jan 2023", "Jan 2024", "Jan 2025"],
                simpanan: [377.36, 431.9, 508.7, 559.98, 570.67, 618.25],
                pembiayaan: [48.91, 85.44, 123.53, 83.79, 84.37, 96.12]
            },
            aset_lembaga: {
                labels: ["Des 2020", "Des 2021", "Des 2022", "Des 2023", "Des 2024", "Des 2025"],
                bus: [397072.97, 441788.83, 531859.89, 594708.74, 664611.0, 797230.0],
                uus: [196875.02, 234946.63, 250239.67, 274276.99, 290652.0, 244000.0],
                bprs: [14943.97, 17059.91, 20156.90, 23177.36, 25031.83, 26510.0]
            },
            marketshare_tren: {
                labels: ["Agu 2021", "Agu 2022", "Agu 2023", "Agu 2024"],
                aset: [6.56, 7.05, 7.28, 7.34],
                pyd: [7.23, 7.75, 8.16, 8.17],
                dpk: [7.10, 7.78, 7.83, 8.15]
            },
            map_dpk: [
                ["Aceh", 11, 100.00, 4.695135, 96.7493993], ["Sumatera Utara", 12, 7.30, 2.1153547, 99.5450974], ["Sumatera Barat", 13, 13.50, -0.7399397, 100.8000051],
                ["Riau", 14, 26.10, 0.2933469, 101.7068294], ["Jambi", 15, 11.40, -1.4851831, 102.4380581], ["Sumatera Selatan", 16, 13.10, -3.3194374, 103.914399],
                ["Bengkulu", 17, 9.50, -3.5778471, 102.3463875], ["Lampung", 18, 9.40, -4.5585849, 105.4068079], ["Kepulauan Bangka Belitung", 19, 7.90, -2.7410513, 106.4405872],
                ["Kepulauan Riau", 21, 26.10, 3.9456514, 108.1428669], ["DKI Jakarta", 31, 6.10, -6.211544, 106.845172], ["Jawa Barat", 32, 10.50, -7.090911, 107.668887],
                ["Jawa Tengah", 33, 7.50, -7.150975, 110.1402594], ["DI Yogyakarta", 34, 11.80, -7.8753849, 110.4262088], ["Jawa Timur", 35, 7.10, -7.5360639, 112.2384017],
                ["Banten", 36, 10.50, -6.4058172, 106.0640179], ["Bali", 51, 2.80, -8.4095178, 115.188916], ["Nusa Tenggara Barat", 52, 25.60, -8.6529334, 117.3616476],
                ["Nusa Tenggara Timur", 53, 0.70, -8.6573819, 121.0793705], ["Kalimantan Barat", 61, 12.60, -0.2787808, 111.4752851], ["Kalimantan Tengah", 62, 4.30, -1.6814878, 113.3823545],
                ["Kalimantan Selatan", 63, 10.90, -3.0926415, 115.2837585], ["Kalimantan Timur", 64, 9.20, 1.6406296, 116.419389], ["Sulawesi Utara", 71, 2.80, 0.6246932, 123.9750018],
                ["Sulawesi Tengah", 72, 5.10, -1.4300254, 121.4456179], ["Sulawesi Selatan", 73, 8.30, -3.6687994, 119.9740534], ["Sulawesi Tenggara", 74, 7.90, -4.14491, 122.174605],
                ["Gorontalo", 75, 3.90, 0.6999372, 122.4467238], ["Sulawesi Barat", 76, 7.70, -2.8441371, 119.2320784], ["Maluku", 81, 3.40, -3.2384616, 130.1452734],
                ["Maluku Utara", 82, 7.60, 1.5709993, 127.8087693], ["Papua Barat", 92, 1.10, -1.3361154, 133.1747162], ["Papua", 91, 1.70, -4.269928, 138.0803529],
                ["Kalimantan Utara", 65, 0.00, 3.35989, 116.53198], ["Papua Selatan", 93, 0.00, -7.0, 139.5], ["Papua Tengah", 94, 0.00, -3.5, 136.5],
                ["Papua Pegunungan", 95, 0.00, -4.1, 138.9], ["Papua Barat Daya", 96, 0.00, -0.9218202, 131.3332843]
            ],
            map_pembiayaan: [
                ["Aceh", 11, 100.00, 4.695135, 96.7493993], ["Sumatera Utara", 12, 7.40, 2.1153547, 99.5450974], ["Sumatera Barat", 13, 18.70, -0.7399397, 100.8000051],
                ["Riau", 14, 20.30, 0.2933469, 101.7068294], ["Jambi", 15, 9.00, -1.4851831, 102.4380581], ["Sumatera Selatan", 16, 11.30, -3.3194374, 103.914399],
                ["Bengkulu", 17, 11.30, -3.5778471, 102.3463875], ["Lampung", 18, 8.80, -4.5585849, 105.4068079], ["Kepulauan Bangka Belitung", 19, 6.60, -2.7410513, 106.4405872],
                ["Kepulauan Riau", 21, 20.30, 3.9456514, 108.1428669], ["DKI Jakarta", 31, 6.40, -6.211544, 106.845172], ["Jawa Barat", 32, 10.30, -7.090911, 107.668887],
                ["Jawa Tengah", 33, 7.90, -7.150975, 110.1402594], ["DI Yogyakarta", 34, 11.40, -7.8753849, 110.4262088], ["Jawa Timur", 35, 6.90, -7.5360639, 112.2384017],
                ["Banten", 36, 11.00, -6.4058172, 106.0640179], ["Bali", 51, 1.80, -8.4095178, 115.188916], ["Nusa Tenggara Barat", 52, 33.80, -8.6529334, 117.3616476],
                ["Nusa Tenggara Timur", 53, 0.70, -8.6573819, 121.0793705], ["Kalimantan Barat", 61, 6.10, -0.2787808, 111.4752851], ["Kalimantan Tengah", 62, 4.30, -1.6814878, 113.3823545],
                ["Kalimantan Selatan", 63, 10.60, -3.0926415, 115.2837585], ["Kalimantan Timur", 64, 7.20, 1.6406296, 116.419389], ["Sulawesi Utara", 71, 2.40, 0.6246932, 123.9750018],
                ["Sulawesi Tengah", 72, 6.00, -1.4300254, 121.4456179], ["Sulawesi Selatan", 73, 8.70, -3.6687994, 119.9740534], ["Sulawesi Tenggara", 74, 6.30, -4.14491, 122.174605],
                ["Gorontalo", 75, 8.40, 0.6999372, 122.4467238], ["Sulawesi Barat", 76, 8.60, -2.8441371, 119.2320784], ["Maluku", 81, 5.20, -3.2384616, 130.1452734],
                ["Maluku Utara", 82, 9.90, 1.5709993, 127.8087693], ["Papua Barat", 92, 2.70, -1.3361154, 133.1747162], ["Papua", 91, 1.80, -4.269928, 138.0803529],
                ["Kalimantan Utara", 65, 0.00, 3.35989, 116.53198], ["Papua Selatan", 93, 0.00, -7.0, 139.5], ["Papua Tengah", 94, 0.00, -3.5, 136.5],
                ["Papua Pegunungan", 95, 0.00, -4.1, 138.9], ["Papua Barat Daya", 96, 0.00, -0.9218202, 131.3332843]
            ]
        },
        payroll_asn: {
            bar: {
                labels: ["Q2 2023","Q4 2023","Q2 2024","Q3 2024","Q4 2024","Januari 2025","Februari 2025","Maret 2025","Mei 2025","Juni 2025","September 2025"],
                nominal: [997, 1032, 1147, 1172, 1203, 1117, 1153, 1199, 1206, 1187, 1373],
                persentase: [10.55, 10.81, 11.39, 11.6, 11.73, 11.75, 11.82, 11.65, 11.75, 11.57, 12.83]
            },
            trend: {
                labels: ["Jan 2023","Feb 2023","Mar 2023","Apr 2023","Mei 2023","Jun 2023","Jul 2023","Agu 2023","Sep 2023","Okt 2023","Nov 2023","Des 2023","Jan 2024","Feb 2024","Mar 2024","Apr 2024","Mei 2024","Jun 2024","Jul 2024","Agu 2024","Sep 2024","Okt 2024","Nov 2024","Des 2024","Jan 2025","Feb 2025","Mar 2025","Apr 2025","Mei 2025","Jun 2025","Jul 2025","Agu 2025","Sep 2025","Okt 2025","Nov 2025","Des 2025"],
                data: [832, 897, 947, 968, 989, 996, 984, 993, 996, 1010, 1017, 1032, 950, 993, 1090, 1132, 1136, 1147, 1152, 1162, 1172, 1180, 1183, 1203, 1117, 1152, 1198, 1212, 1205, 1186, 1108, 1196, 1272, 1285, 1309, 1339]
            },
            pie: {
                labels: ["Bank Syariah", "Bank Konvensional"],
                data: [13.42, 86.58]
            },
            provinsi: [
                ["Aceh", 358007321444, 100.00, 4.695135, 96.7493993],
                ["Maluku Utara", 93380220115, 58.00, 1.5709993, 127.8087693],
                ["Bengkulu", 100157567444, 50.00, -3.5778471, 102.3463875],
                ["Jambi", 142681210670, 50.00, -1.4851831, 102.4380581],
                ["Sulawesi Tenggara", 137537769114, 49.00, -4.14491, 122.174605],
                ["Riau", 176344198098, 46.00, 0.2933469, 101.7068294],
                ["Sulawesi Tengah", 129102849897, 45.00, -1.4300254, 121.4456179],
                ["Kalimantan Barat", 205351228825, 42.00, -0.2787808, 111.4752851],
                ["Sumatera Selatan", 281231860946, 42.00, -3.3194374, 103.914399],
                ["Sulawesi Barat", 61277175494, 42.00, -2.8441371, 119.2320784],
                ["Sulawesi Selatan", 444022586182, 41.00, -3.6687994, 119.9740534],
                ["Kalimantan Timur", 172548336132, 40.00, 1.6406296, 116.419389],
                ["Sumatera Utara", 434470163642, 38.00, 2.1153547, 99.5450974],
                ["Sumatera Barat", 22871407797, 38.00, -0.7399397, 100.8000051],
                ["Sulawesi Utara", 152355695643, 34.00, 0.6246932, 123.9750018],
                ["Kepulauan Riau", 115115823383, 34.00, 3.9456514, 108.1428669],
                ["Jawa Barat", 867734984146, 34.00, -7.090911, 107.668887],
                ["Lampung", 196669842789, 33.00, -4.5585849, 105.4068079],
                ["Nusa Tenggara Barat", 156070012632, 33.00, -8.6529334, 117.3616476],
                ["Kalimantan Tengah", 124067974966, 33.00, -1.6814878, 113.3823545],
                ["Kalimantan Utara", 6080771094, 32.00, 3.35989, 116.53198],
                ["Gorontalo", 80704680648, 31.00, 0.6999372, 122.4467238],
                ["Jawa Timur", 1013352445708, 29.00, -7.5360639, 112.2384017],
                ["Kalimantan Selatan", 185473658176, 27.00, -3.0926415, 115.2837585],
                ["Kepulauan Bangka Belitung", 70831212370, 25.00, -2.7410513, 106.4405872],
                ["Maluku", 160566795717, 25.00, -3.2384616, 130.1452734],
                ["Jawa Tengah", 724824186305, 25.00, -7.150975, 110.1402594],
                ["Banten", 182912291142, 24.00, -6.4058172, 106.0640179],
                ["DKI Jakarta", 2210692296243, 19.00, -6.211544, 106.845172],
                ["Papua", 239731974817, 15.00, -4.269928, 138.0803529],
                ["Papua Barat", 136327498604, 15.00, -1.3361154, 133.1747162],
                ["Bali", 214774086377, 15.00, -8.4095178, 115.188916],
                ["DI Yogyakarta", 229417665973, 13.00, -7.8753849, 110.4262088],
                ["Nusa Tenggara Timur", 172376907855, 9.00, -8.6573819, 121.0793705],
                ["Papua Barat Daya", 0, 0.00, -0.9218202, 131.3332843],
                ["Papua Selatan", 0, 0.00, -7.0, 139.5],
                ["Papua Tengah", 0, 0.00, -3.5, 136.5],
                ["Papua Pegunungan", 0, 0.00, -4.1, 138.9]
            ]
        },
        kih: {
            status: {
                labels: ["Tahap Pendampingan Kemenperin", "Telah Mendapatkan SK KIH", "Dalam Tahap Perencanaan"],
                fullLabels: ["Dalam Tahap Perencanaan dan Pendampingan oleh Kemenperin", "Telah Mendapatkan SK KIH", "Dalam Tahap Perencanaan"],
                data: [5, 4, 3],
                colors: [C.blue, C.emerald, C.amber]
            },
            list: [
                {name: "Kawasan Industri Surya Borneo", prov: "Kalimantan Tengah", status: "Dalam Tahap Perencanaan"},
                {name: "Jakarta Industrial Estate Pulogadung", prov: "DKI Jakarta", status: "Dalam Tahap Perencanaan"},
                {name: "Kawasan Industri Subang", prov: "Jawa Barat", status: "Dalam Tahap Perencanaan"},
                {name: "Kawasan Industri Tenayan", prov: "Riau", status: "Tahap Pendampingan Kemenperin"},
                {name: "Kawasan Industri Ladong", prov: "Aceh", status: "Tahap Pendampingan Kemenperin"},
                {name: "Kawasan Ekonomi Khusus Barsela", prov: "Aceh", status: "Tahap Pendampingan Kemenperin"},
                {name: "Kawasan Industri Makassar", prov: "Sulawesi Selatan", status: "Tahap Pendampingan Kemenperin"},
                {name: "Kawasan Industri Sidoarjo Rangkah Industrial Estate", prov: "Jawa Timur", status: "Tahap Pendampingan Kemenperin"},
                {name: "Modern Cikande Industrial Estate", prov: "Banten", status: "Telah Mendapatkan SK KIH"},
                {name: "Kawasan Industri Halal Bintan Inti", prov: "Kepulauan Riau", status: "Telah Mendapatkan SK KIH"},
                {name: "Kawasan Industri Safe N Lock", prov: "Jawa Timur", status: "Telah Mendapatkan SK KIH"},
                {name: "Kawasan Industri Jababeka", prov: "Jawa Barat", status: "Telah Mendapatkan SK KIH"}
            ],
            sebaran: [
                {prov: "Aceh", lat: 4.695135, lng: 96.7493993, kawasan: "Kawasan Industri Ladong, Kawasan Ekonomi Khusus Barsela", status: "Tahap Pendampingan Kemenperin"},
                {prov: "Kalimantan Tengah", lat: -1.6814878, lng: 113.3823545, kawasan: "Kawasan Industri Surya Borneo", status: "Dalam Tahap Perencanaan"},
                {prov: "Riau", lat: 0.2933469, lng: 101.7068294, kawasan: "Kawasan Industri Tenayan", status: "Tahap Pendampingan Kemenperin"},
                {prov: "Jawa Barat", lat: -7.090911, lng: 107.668887, kawasan: "Kawasan Industri Subang, Kawasan Industri Jababeka", status: "Perencanaan & SK KIH"},
                {prov: "DKI Jakarta", lat: -6.211544, lng: 106.845172, kawasan: "Jakarta Industrial Estate Pulogadung", status: "Dalam Tahap Perencanaan"},
                {prov: "Jawa Timur", lat: -7.5360639, lng: 112.2384017, kawasan: "Kawasan Industri Safe N Lock, Kawasan Industri Sidoarjo Rangkah Industrial Estate", status: "SK KIH & Pendampingan"},
                {prov: "Kepulauan Riau", lat: 3.9456514, lng: 108.1428669, kawasan: "Kawasan Industri Halal Bintan Inti", status: "Telah Mendapatkan SK KIH"},
                {prov: "Sulawesi Selatan", lat: -3.6687994, lng: 119.9740534, kawasan: "Kawasan Industri Makassar", status: "Tahap Pendampingan Kemenperin"},
                {prov: "Banten", lat: -6.4058172, lng: 106.0640179, kawasan: "Modern Cikande Industrial Estate", status: "Telah Mendapatkan SK KIH"}
            ]
        },
        lph: {
            growth: {
                years: ["2019", "2020", "2021", "2022", "2023", "2024", "2025", "April 2026"],
                data: [1, 3, 3, 38, 68, 80, 86, 124]
            },
            type: {
                labels: ["Pratama", "Utama"],
                data: [80, 44],
                colors: [C.emerald, C.blue]
            },
            education: {
                labels: ["Kimia", "Biologi", "Teknik Kimia", "Farmasi", "Teknologi Pangan"],
                data: [217, 111, 108, 101, 65]
            }
        },
        jaminan_sosial: {
            aceh: {
                labels: ["April 2025", "Juni 2025", "Juli 2025", "Agustus 2025", "September 2025", "Oktober 2025", "November 2025"],
                investasi: [1.800, 1.840, 1.850, 1.860, 1.860, 1.860, 1.890],
                peserta: [265223, 389911, 376028, 391247, 403231, 425166, 450770]
            },
            portofolio: {
                labels: ["Konvensional", "Syariah"],
                data: [5655.38, 1719.70]
            },
            pertumbuhan: {
                labels: ["Jan 2025", "Feb 2025", "Mar 2025", "Apr 2025", "Jun 2025", "Jul 2025", "Agu 2025", "Sep 2025"],
                konvensional: [606.85, 604.30, 615.83, 627.81, 644.90, 651.60, 656.30, 659.94],
                syariah: [187.09, 186.54, 185.50, 189.32, 192.36, 195.03, 200.47, 204.01]
            }
        },
        pembiayaan_bprs: {
            bprs: {
                labels: ["Jun 2024", "Jul 2024", "Agu 2024", "Sep 2024", "Okt 2024", "Nov 2024", "Des 2024", "Jan 2025", "Feb 2025", "Mar 2025", "Apr 2025", "Mei 2025", "Jun 2025"],
                data: [10333480, 10630629, 10753490, 10828506, 11018307, 11052806, 11098378, 11122808, 11077316, 11217511, 11124800, 11142421, 11076726]
            },
            lpdb: { prov: 34, mitra: 32, dana: 750.34 },
            pip: { prov: 34, mitra: 40, dana: 4.40 }
        },
        kpbu_syariah: {
            total_pembiayaan: 20.656,
            total_proyek: 17,
            proyek: {
                labels: ["Jalan Tol Cileunyi-Sumedang-Dawuan", "Jalan Tol Semanan-Sunter-Pulogebang", "Jalan Tol Jakarta-Cikampek Elevated", "Jalan Tol Serang Panimbang", "Jalan Tol Semarang Demak", "Jalan Tol Cimanggis-Cibitung", "Jalan Tol Pasuruan-Probolinggo", "Jalan Tol Balikpapan-Samarinda", "Jalan Tol Pemalang-Batang", "Jalan Tol Krian-Legundi-Bunder-Manyar", "Perkeretaapian Makassar-Pare Pare", "Preservasi Jalintim Sumatera Selatan", "Jalan Tol Solo–Yogyakarta–NYIA Kulon Progo", "Preservasi Jalintim Riau", "Jalan Tol Depok-Antasari", "Jalan Trans Papua Ruas Jayapura-Wamena, Segmen Mamberamo-Elelim", "Alat Penerangan Jalan Pemerintah Kabupaten Madiun"],
                data: [5500, 2229, 1830, 1800, 1347, 1275, 1192, 1105, 825, 700, 693, 645, 450, 420, 300, 300, 45],
                tahun: [2022, 2019, 2018, 2021, 2022, 2020, 2018, 2020, 2020, 2018, 2021, 2021, 2022, 2021, 2020, 2024, 2023]
            }
        },
        pasar_modal: {
            total_aset: 11799.87,
            market_share: {
                labels: ["Konvensional", "Syariah"],
                data: [92.41, 7.59]
            },
            tabel_perbankan: {
                labels: ["November 2025","Oktober 2025","September 2025","Agustus 2025","Juli 2025","Juni 2025","Mei 2025","April 2025","Maret 2025","Februari 2025","Januari 2025","Desember 2024","Desember 2023","Desember 2022","Desember 2021","Desember 2020"],
                aset: [1035.05, 1028.18, 1006.18, 975.94, 965.2, 967.33, 942.71, 954.51, 960.82, 874.51, 948.21, 980.29, 892.17, 802.26, 693.8, 608.89],
                share: [7.59, 7.64, 7.67, 7.44, 7.41, 7.41, 7.31, 7.44, 7.42, 7.46, 7.50, 7.72, 7.39, 7.09, 6.74, 6.51]
            },
            saham: {
                labels: ["2021", "2022", "2023", "2024", "Dec 2025"],
                jumlah: [495.0, 552.0, 629.0, null, null],
                yoy_jumlah: [12.24, 11.52, 13.95, null, null],
                kapitalisasi: [3983.65, 4786.02, 6145.96, 6825.31, 8971.68],
                yoy_kapitalisasi: [19.10, 20.14, 28.41, 11.05, 31.45]
            },
            reksadana: {
                labels: ["2021", "2022", "2023", "2024", "Sep 2025"],
                jumlah: [289.0, 274.0, 273.0, 250.0, 255.0],
                yoy_jumlah: [0.0, -5.19, -0.36, -8.42, 2.0],
                nab: [44.00, 40.61, 42.78, 50.55, 70.02],
                yoy_nab: [-40.83, -7.72, 5.34, 18.18, 48.05]
            },
            sukuk_korporasi: {
                labels: ["2021", "2022", "2023", "2024", "Sep 2025"],
                jumlah: [189.0, 221.0, 234.0, null, 281.0],
                yoy_jumlah: [16.67, 16.93, 5.88, null, 18.57],
                nilai: [34.77, 42.50, 45.27, 55.27, 78.38],
                yoy_nilai: [14.54, 22.24, 6.52, 22.09, 55.72]
            },
            sukuk_negara: {
                labels: ["2021", "2022", "2023", "2024", "Des 2025"],
                jumlah: [69.0, 78.0, 82.0, null, null],
                yoy_jumlah: [1.47, 13.04, 5.13, null, null],
                nilai: [1157.06, 1344.35, 1445.53, 1627.68, 1703.60],
                yoy_nilai: [18.93, 16.19, 7.53, 12.60, 4.66]
            }
        },
        tren_pasar_modal: {
            sukuk_negara_combo: {
                labels: ["2021", "2022", "2023", "2024", "Des 2025"],
                nilai: [1157.06, 1344.35, 1445.53, 1627.68, 1703.60],
                jumlah: [69, 78, 82, 0, 0]
            },
            efek_1: {
                labels: [2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025],
                data: [407, 408, 457, 443, 504, 574, 646, 670]
            },
            efek_2: {
                labels: [2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025],
                data: [413, 435, 436, 484, 542, 629, 671, 0]
            },
            kapitalisasi: {
                labels: [2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025],
                data: [3667, 3745, 3345, 3984, 4786, 6146, 6825, 8486]
            },
            sukuk_korporasi_combo: {
                labels: ["2021", "2022", "2023", "2024", "Sep 2025"],
                nilai_outstanding: [34.77, 42.50, 45.27, 55.27, 78.38],
                jumlah_outstanding: [189, 221, 234, null, 281],
                nilai_akumulasi: [66.45, 84.97, 99.96, null, 158.13],
                jumlah_akumulasi: [327, 403, 457, null, 605]
            }
        },
        inovasi_keuangan: {
            tapera_combo: {
                labels: ["Jul-22", "Aug-22", "Sep-22", "Oct-22", "Nov-22", "Dec-22", "Jan-23", "Feb-23", "Mar-23", "Apr-23", "May-23", "Jun-23", "Jul-23", "Aug-23", "Sep-23", "Oct-23", "Nov-23", "Dec-23", "Jan-24", "Feb-24", "Mar-24", "Apr-24", "May-24", "Jun-24", "Jul-24", "Aug-24", "Sep-24", "Oct-24", "Nov-24", "Dec-24", "Jan-25", "Feb-25", "Mar-25", "Apr-25", "May-25", "Jun-25", "Jul-25", "Aug-25", "Sep-25", "Oct-25", "Nov-25"],
                peserta: [274757, 274744, 274943, 275138, 274943, 271849, 271481, 257935, 255439, 255439, 255596, 253507, 253058, 253058, 255665, 254441, 254742, 254742, 254737, 254234, 255439, 253622, 254248, 254748, 254374, 255061, 254245, 255446, 255681, 254441, 255693, 255891, 274271, 273909, 273602, 255332, 255147, 254736, 254941, 252615, 254941],
                investasi: [358.0, 360.6, 361.0, 546.9, 549.2, 512.8, 515.4, 507.717, 509.587, 509.3, 512.2, 502.35, 504.0, 506.26, 505.69, 506.661, 507.9, 508.25, 508.22, 506.1, 509.0, 504.3, 505.3, 505.4, 504.8, 504.3, 505.8, 504.6, 503.5, 504.2, 504.0, 504.2, 504.2, 503.9, 501.9, 503.5, 503.2, 500.8, 503.6, 497.9, 496.0]
            },
            tapera_marketshare: {
                labels: ["Jul-22", "Aug-22", "Sep-22", "Oct-22", "Nov-22", "Dec-22", "Jan-23", "Nov-23", "Dec-23", "Jan-24", "Feb-24", "Mar-24", "Apr-24", "May-24", "Jun-24", "Jul-24", "Aug-24", "Sep-24", "Oct-24", "Nov-24", "Dec-24", "Jan-25", "Feb-25", "Mar-25", "Apr-25", "May-25", "Jun-25", "Sep-25", "Nov-25"],
                data: [0.069, 0.067, 0.067, 0.067, 0.067, 0.07, 0.067, 0.063, 0.063, 0.062, 0.061, 0.062, 0.061, 0.062, 0.062, 0.062, 0.062, 0.062, 0.06, 0.06, 0.079, 0.077, 0.08, 0.086, 0.086, 0.086, 0.081, 0.081219, 0.081219414]
            }
        },
        asuransi_syariah: {
            labels: ["Jul 2023", "Sep 2023", "Dec 2023", "Mar 2024", "Jun 2024", "Dec 2024", "Jan 2025", "Feb 2025", "Mar 2025", "Apr 2025", "Sep 2025", "Oct 2025", "Nov 2025"],
            jiwa: [35.31, 34.91, 32.81, 33.16, 33.13, 34.2, 33.39, 33.76, 33.77, 34.52, 36.14, 37.05, 37.82],
            umum: [8.33, 8.47, 8.46, 9.13, 9.24, 9.46, 9.46, 9.5, 9.55, 9.64, 9.92, 10.22, 10.21],
            reasuransi: [2.65, 2.72, 2.74, 2.82, 2.89, 2.89, 2.96, 2.93, 2.85, 2.87, 2.95, 2.91, 2.9],
            total: [46.28, 46.1, 44.0, 45.1, 45.26, 46.55, 45.81, 46.19, 46.17, 47.03, 49.01, 50.18, 50.93]
        },
        dapen_syariah: {
            labels: ["Ags-23", "Dec-23", "Mar-24", "Jun-24", "Sep-24", "Dec-24", "Jan-25", "Feb-25", "Mar-25", "Apr-25", "Ags-25", "Sep-25", "Okt-25", "Nov-25"],
            dppk_ppmp: [1.58, 1.62, 1.66, 1.7, 1.75, 1.82, 1.83, 1.85, 1.85, 1.86, 1.96, 1.98, 2.01, 2.01],
            dppk_ppip: [0.7, 0.84, 0.93, 0.92, 0.96, 1.0, 1.02, 1.01, 1.02, 1.02, 1.05, 1.06, 1.06, 1.06],
            dplk: [1.63, 1.65, 1.69, 1.67, 1.67, 1.71, 1.73, 1.74, 1.76, 1.77, 1.82, 1.84, 1.85, 1.87],
            jamsosnaker: [1.55, 1.37, 1.65, 1.07, 1.71, 1.76, 1.78, 1.77, 1.78, 1.79, 1.86, 1.86, 1.86, 1.89],
            pis_dplk: [7.37, 8.28, 8.89, 9.16, 9.64, 9.8, 10.04, 10.0, 10.07, 11.02, 10.45, 10.45, 10.62, 10.62]
        },
        sektor_ekonomi: {
            pembiayaan: {
                labels: ["Perdagangan Besar dan Eceran", "Industri Pengolahan", "Konstruksi", "Pertanian, Perburuan dan Kehutanan", "Transportasi, Pergudangan dan Komunikasi", "Perantara Keuangan"],
                data: [53038.0, 39014.0, 34083.0, 33969.0, 29240.0, 20412.0]
            },
            pertumbuhan: {
                labels: ["Jasa Kesehatan dan Kegiatan Sosial", "Pertanian, Perburuan dan Kehutanan", "Real Estate, Usaha Persewaan, dan Jasa Perusahaan", "Penyediaan Akomodasi dan Penyediaan Makan Minum", "Listrik, Gas dan Air", "Pertambangan dan Penggalian"],
                data: [29.94, 19.92, 16.34, 16.19, 15.97, 15.79]
            }
        },
        iknb_syariah: {
            total_aset: 185.25,
            market_share: {
                labels: ["Konvensional", "Syariah"],
                data: [3333.86, 185.25]
            },
            pelaku: {
                labels: ["LKM Syariah", "Asuransi Syariah", "Pembiayaan Syariah", "IKNB Syariah Lainnya", "Penjaminan Syariah", "Fintech Syariah"],
                data: [80, 59, 42, 12, 9, 7]
            }
        },
        kinerja_perbankan: {
            eksposure: {
                labels: ["DPK", "PYD", "Aset"],
                growth_ytd: [-2.16, -0.70, -3.28],
                nilai: [737.35, 639.06, 948.17]
            },
            rekening: {
                labels: ["DPK", "PYD"],
                growth_ytd: [0.88, 0.01],
                growth_yoy: [7.12, -0.44],
                jumlah: [64.26, 7.62]
            },
            market_share_sektor: {
                labels: ["Konvensional", "Syariah"],
                data: [11366.57, 923.42]
            },
            market_share_lembaga: {
                labels: ["BUS", "UUS", "BPRS"],
                data: [797.23, 244.0, 26.51]
            },
            jumlah_lembaga: [14, 19, 174],
            growth_yoy: {
                labels: ["DPK", "PYD", "Aset"],
                data: [9.85, 9.77, 9.16]
            },
            trend_growth: {
                labels: ["Jan 2021", "Feb 2021", "Mar 2021", "Apr 2021", "Mei 2021", "Jun 2021", "Jul 2021", "Agu 2021", "Sep 2021", "Okt 2021", "Nov 2021", "Des 2021", "Jan 2022", "Feb 2022", "Mar 2022", "Apr 2022", "Mei 2022", "Jun 2022", "Jul 2022", "Agu 2022", "Sep 2022", "Okt 2022", "Nov 2022", "Des 2022", "Jan 2023", "Feb 2023", "Mar 2023", "Apr 2023", "Mei 2023", "Jun 2023", "Jul 2023", "Agu 2023", "Sep 2023", "Okt 2023", "Nov 2023", "Des 2023", "Jan 2024", "Feb 2024", "Mar 2024", "Apr 2024", "Mei 2024", "Jun 2024", "Jul 2024", "Agu 2024", "Sep 2024", "Okt 2024", "Nov 2024", "Des 2024", "Jan 2025"],
                dpk: [11.62, 9.84, 11.58, 14.08, 17.52, 16.54, 17.98, 14.78, 9.42, 8.52, 9.32, 15.30, 15.97, 15.00, 15.14, 13.30, 12.69, 13.15, 13.55, 18.08, 15.81, 15.47, 14.80, 12.93, 12.14, 15.00, 18.00, 16.62, 15.05, 10.27, 10.56, 6.91, 9.26, 9.14, 7.79, 10.49, 9.05, 8.24, 7.62, 7.34, 7.19, 10.41, 11.16, 11.42, 12.03, 12.24, 12.77, 10.09, 9.85],
                pyd: [8.17, 7.42, 6.52, 7.86, 7.32, 7.35, 6.82, 7.67, 7.48, 7.86, 5.15, 6.90, 5.94, 7.78, 9.53, 10.25, 10.86, 14.08, 15.32, 18.56, 18.87, 18.29, 21.76, 19.93, 20.70, 19.92, 19.31, 18.54, 19.33, 17.03, 17.46, 14.84, 14.79, 14.80, 14.76, 15.72, 15.74, 15.92, 15.26, 14.87, 14.07, 13.58, 11.98, 11.65, 11.40, 11.94, 11.26, 9.92, 9.77],
                aset: [13.50, 12.50, 12.81, 13.84, 15.54, 15.80, 16.35, 15.29, 12.24, 11.05, 12.03, 13.94, 12.84, 13.19, 14.25, 12.71, 13.75, 14.21, 14.21, 17.31, 16.02, 14.52, 14.16, 15.63, 15.84, 16.07, 17.69, 17.81, 15.58, 14.04, 13.55, 9.80, 10.94, 12.09, 10.96, 11.21, 10.57, 10.48, 9.71, 8.76, 9.70, 9.07, 9.33, 10.37, 10.56, 10.67, 11.46, 9.88, 9.16]
            }
        },
        aset_keuangan_syariah: {
            combo: {
                labels: ["2019", "2020", "2021", "2022", "2023", "2024", "Mar-25", "Aug-25", "Sep-25", "Okt-25", "Nov-25"],
                pasar_modal: [4569.01, 4421.15, 5219.95, 6214.07, 7680.26, 8559.53, 8176.12, 10690.21, 11284.55, 11124.49, 11799.87],
                iknb: [230.91, 262.11, 281.48, 290.43, 310.81, 387.43, 392.27, 406.12, 407.47, 409.06, 185.25],
                perbankan: [538.32, 608.89, 693.80, 802.26, 892.17, 980.29, 960.82, 975.94, 1006.18, 1028.18, 1035.05],
                growth: [5.00, -1.00, 17.00, 18.00, 22.00, 11.80, 5.30, 19.80, 26.40, 23.20, 34.90]
            },
            market_share: {
                labels: ["Konvensional", "Syariah"],
                data: [21277.31, 2742.08]
            },
            total_aset_breakdown: [
                ["2020", 608.89, 44.44, 21.90, 219.080, 30.35, 74.37, 971.50, 3344.93],
                ["2021", 693.80, 43.55, 23.53, 242.090, 34.77, 44.00, 1157.06, 3983.65],
                ["2022", 802.26, 45.02, 33.10, 245.320, 42.50, 40.38, 1344.35, 4786.02],
                ["2023", 892.17, 44.24, 31.45, 276.910, 45.37, 42.78, 1445.53, 6145.96],
                ["2024", 980.29, 46.55, 33.80, 307.084, 55.27, 48.78, 1627.68, 6825.31],
                ["Jan-25", 948.21, 45.81, 33.84, 308.924, 54.68, 51.74, 1633.81, 6718.24],
                ["Feb-25", 949.56, 46.19, 33.90, 308.880, 56.03, 53.82, 1652.48, 6267.99],
                ["Mar-25", 960.82, 46.17, 36.51, 309.590, 62.53, 53.26, 1659.01, 6403.94],
                ["Apr-25", 954.51, 47.03, 36.29, 310.300, 62.97, 54.94, 1704.33, 6863.05],
                ["May-25", 942.71, 47.02, 36.42, 310.270, 64.88, 59.01, 1704.34, 7192.40],
                ["Jun-25", 967.33, 47.54, 36.52, 315.240, 77.14, 55.83, 1695.28, 7578.21],
                ["Jul-25", 965.20, 47.94, 35.78, 303.960, 76.67, 61.91, 1749.61, 8486.43],
                ["Aug-25", 975.94, 48.27, 36.38, 304.330, 80.08, 66.53, 1685.69, 8856.95],
                ["Sep-25", 1006.18, 49.01, 36.85, 304.420, 78.38, 70.02, 1692.12, 9443.06],
                ["Okt-25", 1028.18, 50.18, 37.40, 304.080, 83.77, 74.63, 1649.98, 9315.12],
                ["Nov-25", 1035.05, 50.93, 36.88, 92.480, 85.91, 81.54, 1649.54, 9981.86]
            ],
            iknb_breakdown: [
                ["2020", 44.44, 21.9, 219.08, 0.18, 285.42],
                ["2021", 43.55, 23.53, 242.09, 0.08, 309.17],
                ["2022", 45.02, 33.1, 245.32, 0.05, 323.44],
                ["2023", 44.24, 31.45, 276.91, 0.09, 352.598],
                ["2024", 46.55, 33.8, 307.084, 0.209, 387.43],
                ["Jan-25", 45.81, 33.84, 308.924, 0.208, 388.57],
                ["Feb-25", 46.19, 33.9, 308.88, 0.099, 388.97],
                ["Mar-25", 46.17, 36.51, 309.59, 0.109, 392.27],
                ["Apr-25", 47.03, 36.29, 310.3, 0.1094, 393.62],
                ["May-25", 47.02, 36.42, 310.27, 0.0951, 393.71],
                ["Jun-25", 47.54, 36.52, 315.24, 0.0306, 399.3],
                ["Jul-25", 47.94, 35.78, 303.96, 0.085, 404.69],
                ["Aug-25", 48.27, 36.38, 304.33, 0.086, 406.12],
                ["Sep-25", 49.01, 36.85, 304.42, 0.079, 407.47],
                ["Okt-25", 50.18, 37.4, 304.08, 0.064, 409.06],
                ["Nov-25", 50.93, 36.88, 92.48, 0.13, 185.25]
            ]
        },
        syariah_daerah: {
            rasio_pdrb: {
                labels: ["Aceh", "DKI Jakarta", "Nusa Tenggara Barat", "DI Yogyakarta", "Riau", "Kalimantan Selatan", "Banten", "Kepulauan Riau", "Sumatera Barat", "Kalimantan Barat", "Jawa Timur", "Jawa Barat", "Bengkulu", "Sumatera Selatan", "Jawa Tengah", "Sumatera Utara", "Bali", "Sulawesi Selatan", "Bangka Belitung", "Jambi", "Kalimantan Timur", "Sulawesi Tenggara", "Maluku Utara", "Sulawesi Barat", "Gorontalo", "Lampung", "Maluku", "Kalimantan Tengah", "Sulawesi Tengah", "Sulawesi Utara", "Papua Barat", "Nusa Tenggara Timur"],
                data: [42.55, 19.00, 15.78, 7.09, 6.03, 5.11, 4.81, 4.70, 4.39, 3.76, 3.59, 3.58, 3.43, 3.05, 2.86, 2.82, 2.72, 2.66, 2.63, 2.39, 2.10, 2.08, 2.00, 1.81, 1.77, 1.70, 1.59, 1.10, 0.94, 0.83, 0.71, 0.29]
            }
        },
        kinerja_bus_uus: {
            labels: ["2021", "2022", "2023", "2024", "2025"],
            aset: {
                nominal: [693795.37, 802256.45, 892163.09, 980294.82, 1067740.0],
                yoy: [0.1394, 0.1563, 0.1120, 0.0987, 0.0892]
            },
            dpk: {
                nominal: [473320.43, 548922.13, 615563.82, 671256.23, 737349.77],
                yoy: [0.1162, 0.1597, 0.1214, 0.0904, 0.0984]
            },
            pyd: {
                nominal: [393419.95, 416781.52, 503038.95, 582198.27, 639064.39],
                yoy: [0.0817, 0.0593, 0.2069, 0.1573, 0.0976]
            },
            npf: {
                gross: [0.0315, 0.0265, 0.0237, 0.0207, 0.0219],
                net: [0.0171, 0.0097, 0.0077, 0.0073, 0.0085]
            },
            car: [0.2180, 0.2267, 0.2611, 0.2555, 0.2527],
            fdr: [0.8258, 0.7532, 0.8114, 0.8612, 0.8604],
            bopo: [0.8207, 0.9004, 0.7688, 0.8102, 0.7852],
            roa: [0.0197, 0.0223, 0.0201, 0.0169, 0.0189],
            nom: [0.0209, 0.0263, 0.0272, 0.0230, 0.0248]
        },
        kodifikasi: {
            stats: [850, 131052, 28548000, 290],
            scale: {
                labels: ["Besar", "Menengah", "Kecil", "Mikro"],
                data: [97.93, 2.41, 2.07, 0.34],
                colors: [C.emerald, C.blue, C.amber, C.rose]
            },
            portDest: {
                labels: ["Karachi", "Chattogram", "Zhangjiagang", "New Orleans", "Taman", "Rotterdam", "Paranagua", "Huangpu", "Xinsha", "Paldiski"],
                data: [1816449, 1503284, 1394947, 1390681, 1044747, 1026676, 842361, 792793, 639052, 542105]
            },
            topCompany: {
                labels: ["KUTAI REFINERY", "INTIBENUA", "IVO MAS", "MUSIM MAS", "PELITA AGUNG", "KREASIJAYA", "DUA KUDA", "LDC EAST", "PT DUA KUDA", "WIRA INNO"],
                data: [6425916, 4368227, 3307901, 1770609, 972769, 913714, 793463, 765199, 648004, 590254]
            },
            portOrigin: {
                labels: ["Dumai", "Balikpapan", "Priok", "Belawan", "Panjang", "Teluk Bayur", "Perak", "Bitung", "Manggar", "Sampit"],
                data: [10248526, 7295943, 4458219, 2355915, 1059916, 868294, 781867, 429174, 393084, 245747]
            },
            topCommodity: {
                labels: ["Minyak Sawit", "Asam Lemak", "Bungkil/Residu", "Minyak Kelapa", "Lemak Hewani", "Alkohol", "Margarin", "Buah/Kacang", "Pasta", "Gliserol"],
                data: [17795401, 1765518, 1652934, 1131752, 1018582, 603232, 566887, 506866, 358618, 259172]
            }
        },
        kesehatan: {
            stats: [44634, 550, 38, 1, 1],
            farmasi: {
                labels: ["Obat Tradisional", "Jamu", "Suplemen", "Pangan Gizi Khusus", "Vaksin", "Obat Keras", "Produk Biologi", "Alat Kesehatan", "Obat Bebas Terbatas", "Bahan Obat", "Vitamin & Mineral", "Obat Bebas", "Obat Kuasi", "Protein/Amino", "Enzim"],
                data: [16804, 5972, 4728, 3972, 3695, 2123, 1922, 1762, 777, 617, 446, 272, 183, 89, 83]
            },
            list: [
                {name: "PT Cahaya Intan Indonesia", type: "Laboratorium Medis", exp: "12 Jul 2027", status: "Masih Aktif"},
                {name: "RS Al-Irsyad Surabaya", type: "Rumah Sakit", exp: "08 Apr 2028", status: "Masih Aktif"},
                {name: "RS Permata Hati", type: "Rumah Sakit", exp: "18 Mar 2028", status: "Masih Aktif"},
                {name: "RSUI Boyolali", type: "Rumah Sakit", exp: "11 Feb 2028", status: "Masih Aktif"},
                {name: "RSUD Meuraxa Banda Aceh", type: "Rumah Sakit", exp: "22 Jan 2028", status: "Masih Aktif"},
                {name: "Klinik Nur Hidayah", type: "Klinik", exp: "19 Mar 2027", status: "Masih Aktif"},
                {name: "RS YARSI", type: "Rumah Sakit", exp: "28 Des 2024", status: "Proses Perpanjangan"},
                {name: "RS Muhammadiyah Lamongan", type: "Rumah Sakit", exp: "20 Jan 2025", status: "Kedaluwarsa"}
            ]
        },
        rph: {
            stats: [1078, 28],
            owner: { labels: ["Swasta", "Pemerintah"], data: [876, 209], colors: [C.blue, C.emerald] },
            list: [
                ["Jawa Tengah", -7.150975, 110.1402594, 233], ["Jawa Timur", -7.5360639, 112.2384017, 159],
                ["Jawa Barat", -7.090911, 107.668887, 144], ["DI Yogyakarta", -7.8753849, 110.4262088, 112],
                ["Banten", -6.4058172, 106.0640179, 66], ["Kep. Bangka Belitung", -2.7410513, 106.4405872, 66],
                ["DKI Jakarta", -6.211544, 106.845172, 54], ["Bali", -8.4095178, 115.188916, 41]
            ],
            topTable: [
                {prov: "Jawa Tengah", val: 233}, {prov: "Jawa Timur", val: 159}, {prov: "Jawa Barat", val: 144},
                {prov: "DI Yogyakarta", val: 112}, {prov: "Banten", val: 66}, {prov: "Kep. Bangka Belitung", val: 66},
                {prov: "Lainnya (22 Provinsi)", val: 254}
            ]
        },
        hvc: {
            quarters: ["Q1 18", "Q2 18", "Q3 18", "Q4 18", "Q1 19", "Q2 19", "Q3 19", "Q4 19", "Q1 20", "Q2 20", "Q3 20", "Q4 20", "Q1 21", "Q2 21", "Q3 21", "Q4 21", "Q1 22", "Q2 22", "Q3 22", "Q4 22", "Q1 23", "Q2 23", "Q3 23", "Q4 23", "Q1 24", "Q2 24", "Q3 24", "Q4 24", "Q1 25", "Q2 25", "Q3 25", "Q4 25"],
            sectors: ["Makanan-Minuman", "Modest Fashion", "Pariwisata Ramah Muslim", "Pertanian Halal", "Sektor Unggulan HVC"],
            growth: [
                [0.1277, 0.0867, 0.081, 0.0274, 0.0677, 0.0799, 0.0833, 0.0795, 0.0394, 0.0022, 0.0066, 0.0166, 0.0245, 0.0295, 0.0349, 0.0123, 0.0375, 0.0368, 0.0357, 0.0868, 0.0533, 0.0462, 0.0328, 0.0471, 0.0587, 0.0553, 0.0582, 0.0635, 0.0604, 0.0615, 0.0649, 0.0681],
                [0.0708, 0.0738, 0.0986, 0.1105, 0.1521, 0.1555, 0.135, 0.056, -0.0109, -0.1336, -0.1101, -0.0976, -0.1085, -0.0327, -0.0021, 0.0652, 0.1167, 0.1364, 0.0901, 0.0353, -0.0055, -0.0147, -0.0276, -0.0197, 0.0321, 0.0031, 0.0792, 0.0754, 0.0506, 0.0506, 0.0071, 0.0334],
                [0.0674, 0.0716, 0.0582, 0.0566, 0.0532, 0.0531, 0.056, 0.0663, 0.0181, -0.259, -0.1435, -0.1134, -0.1013, 0.2257, 0.0036, 0.052, 0.09, 0.1338, 0.1902, 0.1457, 0.1302, 0.1208, 0.1245, 0.0917, 0.0889, 0.0959, 0.0853, 0.0726, 0.0744, 0.0827, 0.0839, 0.0802],
                [0.0324, 0.0495, 0.0358, 0.0395, 0.02, 0.055, 0.0303, 0.0451, -0.0025, 0.0221, 0.0235, 0.0307, 0.04, 0.0064, 0.0138, 0.0227, 0.011, 0.018, 0.0221, 0.0483, 0.0038, 0.0208, 0.0132, 0.0104, -0.0378, 0.035, 0.0186, 0.0098, 0.1123, 0.0172, 0.0517, 0.0519],
                [0.0649, 0.0642, 0.0546, 0.0441, 0.0467, 0.0668, 0.0545, 0.0599, 0.0121, -0.0451, -0.0203, -0.0128, -0.002, 0.0419, 0.0169, 0.0276, 0.0388, 0.0491, 0.0567, 0.0781, 0.0408, 0.0438, 0.0364, 0.0363, 0.0193, 0.0506, 0.0455, 0.0431, 0.0852, 0.0441, 0.0595, 0.0621]
            ],
            contribution: [
                [0.0313, 0.0215, 0.0202, 0.0077, 0.0176, 0.0203, 0.0213, 0.0221, 0.0104, 0.0006, 0.0017, 0.0047, 0.0067, 0.0079, 0.0094, 0.0036, 0.0104, 0.0098, 0.0098, 0.0249, 0.0148, 0.0122, 0.0088, 0.0136, 0.0165, 0.0146, 0.0156, 0.0186, 0.0177, 0.0163, 0.0176, 0.0203],
                [0.0042, 0.0042, 0.0053, 0.0069, 0.009, 0.009, 0.0076, 0.0037, -0.0007, -0.0083, -0.0067, -0.0064, -0.0069, -0.0019, -0.0001, 0.0039, 0.0066, 0.0072, 0.0049, 0.0022, -0.0003, -0.0008, -0.0015, -0.0012, 0.0019, 0.0002, 0.0041, 0.0043, 0.003, 0.0026, 0.0004, 0.0019],
                [0.0132, 0.0134, 0.0108, 0.012, 0.0104, 0.01, 0.0105, 0.0142, 0.0036, -0.0482, -0.0269, -0.0244, -0.0201, 0.0326, 0.0006, 0.0101, 0.0161, 0.0227, 0.0307, 0.0288, 0.0244, 0.0222, 0.0226, 0.0193, 0.0181, 0.0189, 0.0168, 0.0161, 0.0162, 0.0171, 0.0172, 0.0183],
                [0.0162, 0.0251, 0.0183, 0.0176, 0.0097, 0.0275, 0.0152, 0.0199, -0.0012, 0.0109, 0.0115, 0.0134, 0.0186, 0.0034, 0.0071, 0.0104, 0.0053, 0.0092, 0.0113, 0.0219, 0.0018, 0.0103, 0.0065, 0.0046, -0.0172, 0.017, 0.009, 0.0042, 0.0483, 0.0082, 0.0243, 0.0216],
                [0.0649, 0.0642, 0.0546, 0.0441, 0.0467, 0.0668, 0.0545, 0.0599, 0.0121, -0.0451, -0.0203, -0.0128, -0.0018, 0.042, 0.0169, 0.0279, 0.0385, 0.0489, 0.0566, 0.0778, 0.0408, 0.0438, 0.0364, 0.0363, 0.0193, 0.0506, 0.0455, 0.0431, 0.0852, 0.0441, 0.0595, 0.0621]
            ],
            pangsaLabels: ["2022", "2023", "2024", "2025"],
            pangsaSectors: ["Pertanian Halal", "Makanan & Minuman Halal", "Modest Fashion", "Pariwisata Ramah Muslim"],
            pangsaData: [
                [11.94, 12.43, 12.35, 10.36, 11.16, 12.69, 12.86, 10.74, 10.99, 13.15, 13.05, 10.72, 12.10, 13.25, 13.72, 10.98],
                [6.36, 5.99, 6.07, 6.13, 6.28, 6.20, 6.39, 6.54, 6.76, 6.53, 6.69, 6.85, 6.99, 6.73, 6.92, 7.03],
                [1.34, 1.25, 1.22, 1.21, 1.23, 1.20, 1.17, 1.17, 1.25, 1.16, 1.22, 1.20, 1.24, 1.16, 1.16, 1.17],
                [4.48, 4.55, 4.67, 5.10, 5.07, 5.28, 5.37, 5.55, 5.40, 5.61, 5.57, 5.61, 5.50, 5.60, 5.53, 5.61]
            ],
            pangsaColors: [C.emerald, C.blue, C.amber, C.teal]
        },
        khas: {
            peresmian: [
                ["Taman Valkenet Malabar", "Bandung", "Jabar", "12 Des 22", 18, "Walikota"],
                ["Lego-lego", "Makassar", "Sulsel", "03 Apr 23", 36, "Gubernur"],
                ["Kantin Utama ITS", "Surabaya", "Jatim", "31 Ags 23", 18, "Wapres"],
                ["Soto Ayam Bok Ijo", "Kediri", "Jatim", "21 Sep 23", 11, "Gubernur"],
                ["Kantin Jawara BI", "Bandung", "Jabar", "02 Nov 23", 5, "ADG BI"],
                ["Riau Garden", "Pekanbaru", "Riau", "03 Nov 23", 24, "Gubernur"],
                ["Masjid Istiqlal", "Jakpus", "DKI", "22 Des 23", 24, "Imam Besar"],
                ["Jangkar Sandar", "Manado", "Sulut", "04 Apr 24", 11, "Wapres"],
                ["Waduk Cacaban", "Tegal", "Jateng", "22 Mei 24", 45, "Bupati"],
                ["Masjid Al-Alam", "Kendari", "Sultra", "07 Jul 24", 13, "Gubernur"],
                ["Masjid Agung Jateng", "Semarang", "Jateng", "08 Ags 24", 54, "Gubernur"],
                ["Solo Square", "Solo", "Jateng", "23 Ags 24", 59, "Walikota"],
                ["UNESA", "Surabaya", "Jatim", "29 Nov 24", 43, "Gubernur"],
                ["Pasar PON Trenggalek", "Trenggalek", "Jatim", "05 Mei 25", 70, "Wagub"],
                ["RSUZA", "Banda Aceh", "Aceh", "16 Jun 25", 21, "Gubernur"],
                ["IPB University", "Bogor", "Jabar", "18 Des 25", 22, "Rektor"],
                ["MTQ Sultra", "Kolaka Timur", "Sultra", "21 Feb 26", 86, "Pemprov"]
            ],
            sebaran: [
                ["Aceh", "RSUZA", 21, 4.695, 96.749], ["Banten", "Univ. Tirta", 10, -6.406, 106.064],
                ["Bengkulu", "Kawasan Berendo Merah Putih", 30, -3.578, 102.346], ["DI Yogyakarta", "Foodcourt UIN SUKA", 8, -7.875, 110.426],
                ["DKI Jakarta", "Istiqlal, Kemenag", 36, -6.211, 106.845], ["Gorontalo", "Objek Wisata Danau Perintis", 10, 0.7, 122.447],
                ["Jawa Barat", "Valkenet, BI, RSAI, IPB, dll", 122, -7.091, 107.669], ["Jawa Tengah", "Slawi, Semarang, Solo, dll", 259, -7.151, 110.14],
                ["Jawa Timur", "ITS, Kediri, UNESA, dll", 197, -7.536, 112.238], ["Kalimantan Barat", "Ayani Megamall", 12, -0.279, 111.475],
                ["Kep. Bangka Belitung", "Pasar Mambo", 12, -2.741, 106.441], ["Lampung", "Uin Raden Inten", 24, -4.559, 105.407],
                ["Nusa Tenggara Barat", "Taman Rinjani Selong", 12, -8.653, 117.362], ["Nusa Tenggara Timur", "Pusat Kuliner Kampung Ujung", 41, -8.657, 121.079],
                ["Papua Barat Daya", "Kantin Kampus Univ Muhammadiyah", 10, -0.922, 131.333], ["Riau", "Riau Garden", 24, 0.293, 101.707],
                ["Sulawesi Selatan", "Lego-lego, Athirah, BI", 61, -3.669, 119.974], ["Sulawesi Tenggara", "Al-Alam, MTQ", 99, -4.145, 122.175],
                ["Sulawesi Utara", "Jangkar Sandar", 11, 0.625, 123.975], ["Sumatera Selatan", "Kantin SMPN 17 Palembang", 6, -3.319, 103.914]
            ]
        },
        umkm_ih: {
            labels: ["2023", "2024", "2025"],
            data: [3034, 13134, 14114]
        },
        industri_daging: {
            rphr: { total: 594, ops: 511, nkv: 221, halal: 311, both: 174 },
            rphu: { total: 362, ops: 328, nkv: 263, halal: 269, both: 230 },
            coldStorage: { labels: ["Halal & NKV", "NKV Saja"], data: [1388, 100], colors: [C.teal, C.amber] },
            kios: { labels: ["Halal & NKV", "NKV Saja"], data: [97, 219], colors: [C.emerald, C.rose] },
            usahaDaging: { labels: ["Halal & NKV", "NKV Saja"], data: [247, 116], colors: [C.blue, C.orange] }
        },
        aktivitas_usaha: {
            quarters: ["Q1 15", "Q2 15", "Q3 15", "Q4 15", "Q1 16", "Q2 16", "Q3 16", "Q4 16", "Q1 17", "Q2 17", "Q3 17", "Q4 17", "Q1 18", "Q2 18", "Q3 18", "Q4 18", "Q1 19", "Q2 19", "Q3 19", "Q4 19", "Q1 20", "Q2 20", "Q3 20", "Q4 20", "Q1 21", "Q2 21", "Q3 21", "Q4 21", "Q1 22", "Q2 22", "Q3 22", "Q4 22", "Q1 23", "Q2 23", "Q3 23", "Q4 23", "Q1 24", "Q2 24", "Q3 24", "Q4 24", "Q1 25", "Q2 25"],
            nilai: [8397, 8208, 8123, 9244, 9401, 9614, 9998, 10217, 10086, 10190, 10251, 10770, 10760, 10649, 10873, 11196, 11481, 11487, 11523, 12000, 11457, 11280, 11241, 11765, 11996, 12095, 12541, 12911, 13291, 13800, 14185, 15015, 15159, 15299, 15709, 16360, 16307, 16749, 17026, 16780, 17410, 17429],
            pangsa_pembiayaan: [0.4886, 0.4742, 0.4601, 0.493, 0.4951, 0.4919, 0.502, 0.5042, 0.5023, 0.4945, 0.491, 0.4962, 0.4913, 0.4773, 0.4754, 0.4739, 0.4789, 0.4768, 0.4737, 0.4795, 0.4549, 0.457, 0.4462, 0.4556, 0.4546, 0.4544, 0.4614, 0.4642, 0.4681, 0.4703, 0.4707, 0.4783, 0.4788, 0.4805, 0.4819, 0.4863, 0.4815, 0.4821, 0.486, 0.4752, 0.4782, 0.4781],
            pangsa_aktivitas: [0.4759, 0.4627, 0.4483, 0.4794, 0.4814, 0.4785, 0.4883, 0.4903, 0.4882, 0.4809, 0.4773, 0.4827, 0.4777, 0.4645, 0.4626, 0.4608, 0.4652, 0.464, 0.4607, 0.4657, 0.4406, 0.4439, 0.4338, 0.4417, 0.4412, 0.4415, 0.4486, 0.4516, 0.4543, 0.457, 0.458, 0.4652, 0.4647, 0.4673, 0.4689, 0.4729, 0.4676, 0.4685, 0.4729, 0.4626, 0.4649, 0.4649]
        },
        ekspor_pdb: {
            years: [2019, 2020, 2021, 2022, 2023, 2024],
            ekspor: [526.19, 585.33, 968.34, 918.78, 769.81, 817.47],
            pdb: [15833, 15434, 16970, 19588, 20892, 22139],
            ratio: [3.32, 3.79, 5.71, 4.69, 3.68, 3.69]
        },
        logistik: {
            total: 1979,
            types: { labels: ["Jasa Pendistribusian", "Jasa Penyimpanan", "Jasa Pengemasan"], data: [1669, 262, 48], colors: [C.blue, C.teal, C.emerald] }
        },
        percepatan_ekspor: {
            years: ["2019", "2020", "2021", "2022", "2023", "2024", "2025"],
            nilai: [37288753, 40022171, 57038741, 61597036, 50585553, 51562787, 63421539],
            topCountries: { labels: ["China", "USA", "India", "Pakistan", "Malaysia"], data: [10731187, 10167430, 5078352, 3459119, 3213901] },
            peb952: { months: ["Jan 26", "Feb 26", "Mar 26"], data: [3607, 3756, 2668] },
            sectorContrib: { labels: ["Makan/Minum", "Tekstil", "Farmasi", "Kosmetik"], data: [84.39, 13.69, 1.14, 0.78], colors: [C.blue, C.teal, C.emerald, C.rose] },
            provinsi: [
                ["Riau", 13419754, 14659395, 10969893, 10331356, 14257799, 22.48, -2.26],
                ["Sumut", 7092979.5, 7687912, 6024350.5, 5851764, 7535676, 11.88, -1.51],
                ["Jabar", 6152297.5, 6904819.5, 6190097, 6561529.5, 7173191, 11.31, 2.59],
                ["Jatim", 5479010, 6119218, 5078537.5, 5499451.5, 6725457, 10.60, 3.08],
                ["Jateng", 4034912.8, 4485230.5, 3963462.2, 4260831.5, 4246732, 6.70, 0.51],
                ["DKI Jakarta", 2707346.2, 2766295.2, 2421624.8, 2690123, 3530957, 5.57, 5.16],
                ["Lampung", 2711176.5, 2808866, 2395301.2, 2866244, 3523244.5, 5.56, 5.59],
                ["Kaltim", 3315506.5, 3384802.5, 3148673.5, 2950100.8, 3264454.8, 5.15, -1.67],
                ["Banten", 2167238.8, 2347297.2, 1913790, 2455974.5, 2958194.5, 4.66, 6.90],
                ["Sumbar", 2581308.8, 2474432.8, 2009075.8, 1825231.4, 2445412.2, 3.86, -4.04],
                ["Kepri", 1974488.8, 1912876.2, 1392670.5, 1538991, 1969097.4, 3.10, -2.20],
                ["Kalsel", 1235713.4, 1368048.2, 1092453.8, 1140002.6, 1315612.4, 2.07, -0.57],
                ["Sulut", 820079, 825701, 632773, 624351, 981466, 1.55, 0.80],
                ["Kalteng", 540380, 769376, 607077, 490387, 705102, 1.11, 0.82],
                ["Sulbar", 651798, 449153, 456430, 419699, 618830, 0.98, -1.70],
                ["Kalbar", 629375, 792557, 630917, 549875, 429747, 0.68, -10.67],
                ["Sumsel", 266208, 379413, 478897, 303173, 316142, 0.50, 1.20],
                ["DIY", 248444, 284673, 223395, 275384, 294014, 0.46, 3.08],
                ["Sulsel", 204688, 182202, 154102, 178308, 269508, 0.42, 5.43],
                ["Jambi", 167121, 318783, 193835, 184972, 268693, 0.42, 4.14],
                ["Babel", 268043, 250150, 222598, 207118, 218512, 0.34, -5.80],
                ["Bali", 111685, 146902, 127734, 130686, 83650, 0.13, -6.71],
                ["Aceh", 46167, 54975, 62336, 18741, 70032, 0.11, -2.40],
                ["Kaltara", 118452, 95854, 79969, 73403, 67013, 0.11, -13.12],
                ["Maluku", 4534, 39131, 41441, 30324, 42230, 0.07, 52.32],
                ["Sulteng", 18661, 26313, 19201, 31166, 30446, 0.05, 12.17],
                ["NTB", 29250, 19073, 12403, 21707, 27935, 0.04, 0.37],
                ["NTT", 5959, 18787, 22228, 23055, 18973, 0.03, 28.67],
                ["Sultra", 5710, 7071, 6494, 12086, 15302, 0.02, 28.50],
                ["Gorontalo", 6068, 7474, 5341, 6170, 6305, 0.01, -1.14],
                ["Pua Barat Daya", 0, 0, 0, 8549, 8894, 0.01, 0],
                ["Malut", 755, 1608, 175, 160, 282, 0.00, -34.82],
                ["Papua Barat", 6664, 7627, 7129, 130, 49, 0.00, -75.10],
                ["Bengkulu", 0, 42, 6, 0, 293, 0.00, 236.49],
                ["Pua Tengah", 0, 0, 0, 0, 111, 0.00, 0],
                ["Pua Selatan", 0, 0, 0, 18, 39, 0.00, 0],
                ["Papua", 16965, 977, 1141, 1726, 2144, 0.00, -30.00]
            ],
            yoy: {
                labels: ["Riau", "Sumut", "Jabar", "Jatim", "Jateng", "DKI", "Lampung", "Kaltim", "Banten", "Sumbar", "Kepri", "Kalsel", "Sulut", "Kalteng", "Sulbar", "Kalbar", "Sumsel", "DIY", "Sulsel", "Jambi", "Babel", "Bali", "Aceh", "Kaltara", "Maluku", "Sulteng", "NTB", "NTT", "Sultra", "Pua Brt Dy", "Gorontalo", "Papua", "Bengkulu", "Malut", "Pua Tgh", "Pua Brt", "Pua Sel"],
                nilai2024: [10331356, 5851764, 6561529.5, 5499451.5, 4260831.5, 2690123, 2866244, 2950100.8, 2455974.5, 1825231.4, 1538991, 1140002.6, 624350.9, 490387, 419698.8, 549875.4, 303172.6, 275384.1, 178308.4, 184971.7, 207117.8, 130686, 18740.8, 73402.9, 30324.5, 31165.8, 21707.4, 23055.1, 12085.7, 8548.5, 6169.8, 1726.5, 0, 159.5, 0, 129.6, 17.5],
                nilai2025: [14257799, 7535676, 7173191, 6725457, 4246732, 3530957, 3523244.5, 3264454.8, 2958194.5, 2445412.2, 1969097.4, 1315612.4, 981465.8, 705102.2, 618830.4, 429747.1, 316142, 294014, 269508.3, 268692.9, 218511.6, 83649.6, 70031.9, 67012.9, 42229.7, 30445.8, 27934.8, 18973, 15301.9, 8894.3, 6304.9, 2144.1, 293, 282, 110.5, 49, 39.4],
                yoy: [38.01, 28.78, 9.32, 22.29, -0.33, 31.26, 22.92, 10.66, 20.45, 33.98, 27.95, 15.40, 57.20, 43.78, 47.45, -21.85, 4.28, 6.77, 51.15, 45.26, 5.50, -35.99, 273.69, -8.71, 39.26, -2.31, 28.69, -17.71, 26.61, 4.04, 2.19, 24.19, 0, 76.85, 0, -62.23, 125.01]
            }
        },
        aset_keuangan: {
            years: [2018, 2019, 2020, 2021, 2022, 2023, 2024, 2025],
            pdb: [14837, 15834, 15434, 16970, 19588, 20892, 22139, 23290],
            aset: [5071, 5338.24, 5249.63, 6156.04, 6495.56, 8863.81, 9846.61, 12475.15],
            ratio: [34, 34, 34, 36, 33, 42, 44, 54]
        },
        pembiayaan_umkm: {
            total_konsolidasi: 165532.857,
            komposisi: {
                labels: ["Perbankan Syariah", "Non-Perbankan Syariah", "Securities Crowdfunding Syariah"],
                data: [114814.31, 50328.69, 389.86]
            },
            kontribusi: {
                labels: ["BUS, UUS & BPRS", "PNM", "PP Syariah", "Pergadaian", "MV Syariah", "Fintech Syariah", "LPDB Syariah", "SCF Syariah", "LKMS", "BWM"],
                data: [114814.31, 34280.18, 5630.1, 3699.51, 3078.48, 1750.94, 1606.65, 389.86, 268.5, 14.32]
            },
            perbankan: {
                total: 114814.31,
                rasio: { labels: ["Pembiayaan UMKM", "Pembiayaan Non-UMKM"], data: [114814.31, 590407.43] },
                bus: 82233.54,
                uus: 21455.88,
                bprs: 11124.89,
                rasio_umkm: { bus: "15.88%", uus: "12.83%", bprs: "54.79%" },
                pertumbuhan: "-0,15%",
                komposisi_lembaga: { labels: ["BUS", "UUS", "BPRS"], data: [14, 19, 173] },
                tren: {
                    labels: ["Dec 2023", "Mar 2024", "Jun 2024", "Sep 2024", "Dec 2024", "Mar 2025", "Jun 2025", "Sep 2025", "Dec 2025"],
                    bprs: [9769.76, 10198.18, 10333.48, 10828.00, 11058.00, 11217.00, 11077.41, 11126.95, 11124.89],
                    bus: [74570.90, 75699.74, 76793.05, 77931.82, 80954.35, 80234.11, 79659.37, 79687.28, 82233.54],
                    uus: [20839.53, 21214.45, 21684.94, 22450.39, 22972.74, 22437.79, 22491.86, 22450.95, 21455.88]
                }
            },
            non_perbankan: {
                total: 48707.72,
                rasio: { labels: ["Pembiayaan UMKM", "Pembiayaan Non-UMKM"], data: [48707.72, 158528.14] },
                komposisi_lembaga: { labels: ["Fintech Syariah", "LKMS", "MV Syariah", "Pergadaian", "PNM", "PP Syariah"], data: [6, 79, 7, 1, 2, 20] },
                kontribusi: { labels: ["PNM", "PP Syariah", "Pergadaian", "MV Syariah", "Fintech Syariah", "LKMS"], data: [34280.18, 5630.1, 3699.51, 3078.48, 1750.94, 268.5] }
            },
            scf: {}
        },
        zis_pdb: {
            labels: [2021, 2022, 2023, 2024],
            zis_triliun: [14.10, 22.40, 32.30, 40.40],
            pdb_triliun: [16970.00, 19588.00, 20892.00, 22139.00],
            rasio_persen: [0.08, 0.11, 0.15, 0.18]
        },
        transformasi_wakaf: {
            nazhir: 432,
            lkspwu: 61,
            lembaga_syariah: {
                labels: ["BPR Syariah", "Unit Usaha Syariah", "Bank Umum Syariah"],
                data: [32, 17, 12]
            },
            cwld: {
                total: 11789167657,
                banks: {
                    labels: ["BRK Syariah", "Bank Jatim Syariah", "KBBS", "Bank Sumsel Babel Syariah", "Bank Nano Syariah", "BPRS Barokah Dana Sejahtera", "BPRS Hijra", "BTPN Syariah", "BJB Syariah"],
                    data: [2856000000, 2722237500, 1871500000, 1000000000, 900000000, 770000000, 748500000, 566500000, 354430157]
                }
            },
            sebaran_nazhir: [
                ["Jawa Barat", 104, -7.090911, 107.668887],
                ["Jawa Tengah", 81, -7.150975, 110.1402594],
                ["DKI Jakarta", 77, -6.211544, 106.845172],
                ["Jawa Timur", 65, -7.5360639, 112.2384017],
                ["DI Yogyakarta", 21, -7.8753849, 110.4262088],
                ["Banten", 19, -6.4058172, 106.0640179],
                ["Lampung", 11, -4.5585849, 105.4068079],
                ["Riau", 11, 0.2933469, 101.7068294],
                ["Sumatera Barat", 11, -0.7399397, 100.8000051],
                ["Nusa Tenggara Barat", 8, -8.6529334, 117.3616476],
                ["Sulawesi Selatan", 6, -3.6687994, 119.9740534],
                ["Sulawesi Tengah", 2, -1.4300254, 121.4456179],
                ["Aceh", 2, 4.695135, 96.7493993],
                ["Bengkulu", 2, -3.5778471, 102.3463875],
                ["Kalimantan Selatan", 1, -3.0926415, 115.2837585],
                ["Sulawesi Tenggara", 1, -4.14491, 122.174605],
                ["Sumatera Selatan", 1, -3.3194374, 103.914399],
                ["Kalimantan Barat", 1, -0.2787808, 111.4752851],
                ["Kepulauan Riau", 1, 3.9456514, 108.1428669],
                ["Kalimantan Timur", 1, 1.6406296, 116.419389],
                ["Bali", 1, -8.4095178, 115.188916],
                ["Kalimantan Tengah", 1, -1.6814878, 113.3823545],
                ["Sumatera Utara", 1, 2.1153547, 99.5450974]
            ],
            sertifikasi: {
                stats: {
                    jumlah_sdh: 252937,
                    jumlah_blm: 187575,
                    luas_sdh: 21197.09,
                    luas_blm: 36066.58,
                    lokasi_total: 440512,
                    luas_total: 57263.67,
                    persen_lokasi: 57.27,
                    persen_luas: 46.41
                },
                penggunaan: {
                    labels: ["Musholla", "Masjid", "Makam", "Sekolah", "Pesantren", "Sosial Lainnya"],
                    data: [123, 191, 19, 47, 18, 41]
                }
            }
        },
        wakaf_uang_pdb: {
            labels: [2020, 2021, 2022, 2023, 2024],
            wakaf_uang_triliun: [0.81, 1.48, 1.77, 2.39, 3.02],
            pdb_triliun: [15434.00, 16970.00, 19588.00, 20892.00, 22139.00],
            rasio_persen: [0.0052, 0.0087, 0.0090, 0.0114, 0.0136]
        },
        pendanaan_umkm_sosial: {
            jaringan_bzm_bwm: 62,
            total_miliar: 14.32,
            growth: -13.06,
            labels: ["Q4-2022", "Q1-2023", "Q2-2023", "Q3-2023", "Q4-2023", "Q1-2024", "Q2-2024", "Q3-2024", "Q4-2024", "Q1-2025", "Q2-2025", "Q3-2025", "Q4-2025"],
            data: [14.12, 14.12, 16.12, 15.79, 16.69, 16.69, 17.64, 16.62, 16.47, 19.62, 16.47, 12.38, 14.32]
        },
        zis_nasional: {
            stats: {
                pengumpulan: 10.33, // Triliun
                penyaluran: 9.21,
                operasional: 750.0, // Miliar
                mustahik: 58.47, // Juta
                muzaki: 34.94
            },
            pengumpulan_wilayah: { labels: ["Kab/Kota", "Pusat", "Provinsi"], data: [8600.97, 881.56, 854.11] },
            penyaluran_wilayah: { labels: ["Kab/Kota", "Provinsi", "Pusat"], data: [7648.70, 891.08, 675.09] },
            operasional_wilayah: { labels: ["Provinsi", "Kab/Kota", "Pusat"], data: [430.88, 215.53, 102.01] },
            mustahik_wilayah: { labels: ["Kab/Kota", "Pusat", "Provinsi"], data: [54854170, 2494228, 1123124] },
            muzaki_wilayah: { labels: ["Kab/Kota", "Provinsi", "Pusat"], data: [30970857, 3513594, 459640] },
            bar_sosial: {
                labels: ["Kab/Kota", "Pusat", "Provinsi"],
                pengumpulan: [8601.0, 881.6, 854.1],
                penyaluran: [7648.7, 675.1, 891.1],
                operasional: [215.5, 102.0, 430.9]
            },
            bar_orang: {
                labels: ["Kab/Kota", "Provinsi", "Pusat"],
                muzaki: [31.0, 3.5, 0.5],
                mustahik: [54.9, 1.1, 2.5]
            }
        },
        sertifikasi_umk_nas: {
            stats: {
                total: 2315654,
                reguler: 100604,
                self_declare: 2215050,
                pendamping: 102835,
                lembaga: 236
            },
            chart: {
                labels: ["2019", "2020", "2021", "2022", "2023", "2024", "Mar-25", "Jun-25"],
                reguler: [2, 5657, 16827, 18198, 25422, 91472, 97752, 100604],
                self_declare: [0, 0, 750, 89560, 1207768, 2009463, 2016911, 2215050],
                total: [2, 5657, 17577, 107758, 1233190, 2100935, 2114663, 2315654]
            }
        },
        literasi_ekonomi_nas: {
            chart: {
                labels: ["2019", "2021", "2022", "2023", "2024", "2025"],
                data: [0.163, 0.2002, 0.2330, 0.2801, 0.4284, 0.5018]
            }
        },
        layanan_komunitas_nas: {
            agen_ponpes: {
                trend: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    data: [528, 574, 605, 621]
                },
                map: [
                    ["Aceh",4.695135,96.7493993,19], ["Bali",-8.4095178,115.188916,13], ["Banten",-6.4058172,106.0640179,60], ["Bengkulu",-3.5778471,102.3463875,5], ["DI Yogyakarta",-7.8753849,110.4262088,12], ["DKI Jakarta",-6.211544,106.845172,16], ["Gorontalo",0.6999372,122.4467238,0], ["Jambi",-1.4851831,102.4380581,15], ["Jawa Barat",-7.090911,107.668887,159], ["Jawa Tengah",-7.150975,110.1402594,83], ["Jawa Timur",-7.5360639,112.2384017,82], ["Kalimantan Barat",-0.2787808,111.4752851,8], ["Kalimantan Selatan",-3.0926415,115.2837585,7], ["Kalimantan Tengah",-1.6814878,113.3823545,5], ["Kalimantan Timur",1.6406296,116.419389,2], ["Kalimantan Utara",3.35989,116.53198,2], ["Kepulauan Bangka Belitung",-2.7410513,106.4405872,1], ["Kepulauan Riau",3.9456514,108.1428669,6], ["Lampung",-4.5585849,105.4068079,27], ["Maluku",-3.2384616,130.1452734,0], ["Maluku Utara",1.5709993,127.8087693,1], ["Nusa Tenggara Barat",-8.6529334,117.3616476,9], ["Nusa Tenggara Timur",-8.6573819,121.0793705,0], ["Papua",-4.269928,138.0803529,3], ["Papua Barat",-1.3361154,133.1747162,0], ["Papua Barat Daya",-0.9218202,131.3332843,1], ["Papua Pegunungan",-4.1,138.9,0], ["Papua Selatan",-7.0,139.5,0], ["Papua Tengah",-3.5,136.5,0], ["Riau",0.2933469,101.7068294,18], ["Sulawesi Barat",-2.8441371,119.2320784,1], ["Sulawesi Selatan",-3.6687994,119.9740534,11], ["Sulawesi Tengah",-1.4300254,121.4456179,4], ["Sulawesi Tenggara",-4.14491,122.174605,1], ["Sulawesi Utara",0.6246932,123.9750018,2], ["Sumatera Barat",-0.7399397,100.8000051,17], ["Sumatera Selatan",-3.3194374,103.914399,18], ["Sumatera Utara",2.1153547,99.5450974,13]
                ],
                top5: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    datasets: [
                        { label: "Banten", data: [43, 46, 56, 60], color: C.blue },
                        { label: "Jawa Barat", data: [134, 154, 158, 159], color: C.emerald },
                        { label: "Jawa Tengah", data: [70, 79, 80, 83], color: C.amber },
                        { label: "Jawa Timur", data: [94, 93, 81, 82], color: C.rose },
                        { label: "Lampung", data: [28, 27, 27, 27], color: C.violet }
                    ]
                },
                table: [
                    ["Jawa Barat",134,154,158,159], ["Jawa Tengah",70,79,80,83], ["Jawa Timur",94,93,81,82], ["Banten",43,46,56,60], ["Lampung",28,27,27,27], ["Aceh",11,11,19,19], ["Riau",17,18,16,18], ["Sumatera Selatan",19,19,18,18], ["Sumatera Barat",11,14,15,17], ["DKI Jakarta",12,12,16,16], ["Jambi",10,12,15,15], ["Bali",8,10,13,13], ["Sumatera Utara",9,12,12,13], ["DI Yogyakarta",6,6,12,12], ["Sulawesi Selatan",7,10,11,11], ["Nusa Tenggara Barat",7,7,7,9], ["Kalimantan Barat",10,10,8,8], ["Kalimantan Selatan",5,5,7,7], ["Kepulauan Riau",4,4,6,6], ["Kalimantan Tengah",5,5,5,5], ["Bengkulu",3,3,5,5], ["Sulawesi Tengah",4,4,4,4], ["Papua",3,3,3,3], ["Kalimantan Utara",2,2,2,2], ["Sulawesi Utara",2,2,2,2], ["Kalimantan Timur",1,2,2,2], ["Sulawesi Barat",0,0,1,1], ["Papua Barat Daya",0,0,1,1], ["Maluku Utara",1,1,1,1], ["Kepulauan Bangka Belitung",0,1,1,1], ["Sulawesi Tenggara",1,1,1,1], ["Maluku",0,0,0,0], ["Papua Barat",1,1,0,0], ["Nusa Tenggara Timur",0,0,0,0], ["Papua Tengah",0,0,0,0], ["Gorontalo",0,0,0,0], ["Papua Selatan",0,0,0,0], ["Papua Pegunungan",0,0,0,0]
                ]
            },
            agen_bumdes: {
                trend: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    data: [71, 111, 134, 136]
                },
                map: [
                    ["Aceh",4.695135,96.7493993,10], ["Bali",-8.4095178,115.188916,25], ["Banten",-6.4058172,106.0640179,4], ["Bengkulu",-3.5778471,102.3463875,0], ["DI Yogyakarta",-7.8753849,110.4262088,1], ["DKI Jakarta",-6.211544,106.845172,2], ["Gorontalo",0.6999372,122.4467238,0], ["Jambi",-1.4851831,102.4380581,5], ["Jawa Barat",-7.090911,107.668887,13], ["Jawa Tengah",-7.150975,110.1402594,19], ["Jawa Timur",-7.5360639,112.2384017,16], ["Kalimantan Barat",-0.2787808,111.4752851,0], ["Kalimantan Selatan",-3.0926415,115.2837585,2], ["Kalimantan Tengah",-1.6814878,113.3823545,1], ["Kalimantan Timur",1.6406296,116.419389,2], ["Kalimantan Utara",3.35989,116.53198,0], ["Kepulauan Bangka Belitung",-2.7410513,106.4405872,3], ["Kepulauan Riau",3.9456514,108.1428669,0], ["Lampung",-4.5585849,105.4068079,4], ["Maluku",-3.2384616,130.1452734,0], ["Maluku Utara",1.5709993,127.8087693,0], ["Nusa Tenggara Barat",-8.6529334,117.3616476,13], ["Nusa Tenggara Timur",-8.6573819,121.0793705,1], ["Papua",-4.269928,138.0803529,0], ["Papua Barat",-1.3361154,133.1747162,0], ["Papua Barat Daya",-0.9218202,131.3332843,0], ["Papua Pegunungan",-4.1,138.9,0], ["Papua Selatan",-7.0,139.5,0], ["Papua Tengah",-3.5,136.5,0], ["Riau",0.2933469,101.7068294,4], ["Sulawesi Barat",-2.8441371,119.2320784,0], ["Sulawesi Selatan",-3.6687994,119.9740534,1], ["Sulawesi Tengah",-1.4300254,121.4456179,7], ["Sulawesi Tenggara",-4.14491,122.174605,0], ["Sulawesi Utara",0.6246932,123.9750018,0], ["Sumatera Barat",-0.7399397,100.8000051,1], ["Sumatera Selatan",-3.3194374,103.914399,0], ["Sumatera Utara",2.1153547,99.5450974,2]
                ],
                top5: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    datasets: [
                        { label: "Bali", data: [8, 19, 25, 25], color: C.blue },
                        { label: "Jawa Barat", data: [7, 11, 13, 13], color: C.emerald },
                        { label: "Jawa Tengah", data: [6, 8, 17, 19], color: C.amber },
                        { label: "Jawa Timur", data: [11, 19, 16, 16], color: C.rose },
                        { label: "NTB", data: [5, 13, 13, 13], color: C.violet }
                    ]
                },
                table: [
                    ["Bali",8,19,25,25], ["Jawa Tengah",6,8,17,19], ["Jawa Timur",11,19,16,16], ["Nusa Tenggara Barat",5,13,13,13], ["Jawa Barat",7,11,13,13], ["Aceh",8,9,10,10], ["Sulawesi Tengah",7,7,7,7], ["Jambi",3,3,5,5], ["Banten",0,0,4,4], ["Lampung",1,3,4,4], ["Riau",3,5,4,4], ["Kepulauan Bangka Belitung",2,2,3,3], ["Sumatera Utara",2,3,2,2], ["DKI Jakarta",1,1,2,2], ["Kalimantan Timur",1,1,2,2], ["Kalimantan Selatan",1,2,2,2], ["Sulawesi Selatan",1,1,1,1], ["Sumatera Barat",1,1,1,1], ["DI Yogyakarta",1,1,1,1], ["Kalimantan Tengah",1,1,1,1], ["Nusa Tenggara Timur",1,1,1,1]
                ],
                transaksi: {
                    labels: ["Q3 2025", "Q4 2025"],
                    jumlah: [2568, 2906],
                    volume: [2.69, 3.10] // Dalam Miliar
                }
            },
            agen_masjid: {
                trend: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    data: [246, 256, 263, 270]
                },
                map: [
                    ["Aceh",4.695135,96.7493993,2], ["Bali",-8.4095178,115.188916,0], ["Banten",-6.4058172,106.0640179,18], ["Bengkulu",-3.5778471,102.3463875,0], ["DI Yogyakarta",-7.8753849,110.4262088,5], ["DKI Jakarta",-6.211544,106.845172,21], ["Gorontalo",0.6999372,122.4467238,1], ["Jambi",-1.4851831,102.4380581,1], ["Jawa Barat",-7.090911,107.668887,84], ["Jawa Tengah",-7.150975,110.1402594,45], ["Jawa Timur",-7.5360639,112.2384017,26], ["Kalimantan Barat",-0.2787808,111.4752851,35], ["Kalimantan Selatan",-3.0926415,115.2837585,1], ["Kalimantan Tengah",-1.6814878,113.3823545,1], ["Kalimantan Timur",1.6406296,116.419389,1], ["Kalimantan Utara",3.35989,116.53198,0], ["Kepulauan Bangka Belitung",-2.7410513,106.4405872,0], ["Kepulauan Riau",3.9456514,108.1428669,7], ["Lampung",-4.5585849,105.4068079,4], ["Maluku",-3.2384616,130.1452734,0], ["Maluku Utara",1.5709993,127.8087693,0], ["Nusa Tenggara Barat",-8.6529334,117.3616476,0], ["Nusa Tenggara Timur",-8.6573819,121.0793705,0], ["Papua",-4.269928,138.0803529,1], ["Papua Barat",-1.3361154,133.1747162,0], ["Papua Barat Daya",-0.9218202,131.3332843,0], ["Papua Pegunungan",-4.1,138.9,0], ["Papua Selatan",-7.0,139.5,0], ["Papua Tengah",-3.5,136.5,0], ["Riau",0.2933469,101.7068294,18], ["Sulawesi Barat",-2.8441371,119.2320784,0], ["Sulawesi Selatan",-3.6687994,119.9740534,11], ["Sulawesi Tengah",-1.4300254,121.4456179,0], ["Sulawesi Tenggara",-4.14491,122.174605,0], ["Sulawesi Utara",0.6246932,123.9750018,0], ["Sumatera Barat",-0.7399397,100.8000051,1], ["Sumatera Selatan",-3.3194374,103.914399,1], ["Sumatera Utara",2.1153547,99.5450974,3]
                ],
                top5: {
                    labels: ["Q1 2025", "Q2 2025", "Q3 2025", "Q4 2025"],
                    datasets: [
                        { label: "DKI Jakarta", data: [18, 19, 21, 21], color: C.blue },
                        { label: "Jawa Barat", data: [68, 70, 78, 84], color: C.emerald },
                        { label: "Jawa Tengah", data: [40, 41, 44, 45], color: C.amber },
                        { label: "Jawa Timur", data: [30, 36, 26, 26], color: C.rose },
                        { label: "Kalimantan Barat", data: [41, 41, 35, 35], color: C.violet }
                    ]
                },
                table: [
                    ["Jawa Barat",68,70,78,84], ["Jawa Tengah",40,41,44,45], ["Kalimantan Barat",41,41,35,35], ["Jawa Timur",30,36,26,26], ["DKI Jakarta",18,19,21,21], ["Banten",13,14,18,18], ["Sulawesi Selatan",12,12,11,11], ["Kepulauan Riau",1,1,7,7], ["DI Yogyakarta",4,4,5,5], ["Lampung",5,5,4,4], ["Sumatera Utara",3,3,3,3], ["Aceh",2,2,2,2], ["Sumatera Selatan",2,2,1,1], ["Kalimantan Timur",2,1,1,1], ["Kalimantan Selatan",1,1,1,1], ["Sumatera Barat",1,1,1,1], ["Papua",1,1,1,1], ["Kalimantan Tengah",1,1,1,1], ["Gorontalo",0,0,1,1], ["Riau",0,0,1,1], ["Jambi",0,0,1,1], ["Sulawesi Barat",1,1,0,0]
                ],
                transaksi: {
                    labels: ["Q3 2025", "Q4 2025"],
                    jumlah: [351, 399],
                    volume: [0.59, 1.01] // Dalam Miliar
                }
            }
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
        } else {
            // Top Cards Nasional
            document.getElementById('nStat0').textContent = '2.31M';
            document.getElementById('nStat1').textContent = '23.8K T';
            document.getElementById('nStat2').textContent = '1.03K T';
            document.getElementById('nStat3').textContent = '50.18';
        }

        if (n && state.nasView === 'pendanaan-umkm-sosial') {
            const pu = N_DATA.pendanaan_umkm_sosial;
            const elJ = document.getElementById('nasPendanaanSosialJaringan');
            const elT = document.getElementById('nasPendanaanSosialTotal');
            const elG = document.getElementById('nasPendanaanSosialGrowth');
            if (elJ) elJ.textContent = pu.jaringan_bzm_bwm;
            if (elT) elT.textContent = fmt(pu.total_miliar);
            if (elG) {
                elG.textContent = pu.growth + '%';
                elG.classList.toggle('text-rose-500', pu.growth < 0);
                elG.classList.toggle('text-emerald-600', pu.growth >= 0);
            }
        }

        if (n && state.nasView === 'zis-nasional') {
            const z = N_DATA.zis_nasional;
            const elPu = document.getElementById('nasZisTotalPengumpulan');
            const elPa = document.getElementById('nasZisTotalPenyaluran');
            const elOp = document.getElementById('nasZisTotalOperasional');
            const elMu = document.getElementById('nasZisTotalMustahik');
            const elMz = document.getElementById('nasZisTotalMuzaki');
            if (elPu) elPu.textContent = fmt(z.stats.pengumpulan) + ' T';
            if (elPa) elPa.textContent = fmt(z.stats.penyaluran) + ' T';
            if (elOp) elOp.textContent = fmt(z.stats.operasional) + ' Miliar';
            if (elMu) elMu.textContent = fmt(z.stats.mustahik) + ' Jiwa';
            if (elMz) elMz.textContent = fmt(z.stats.muzaki) + ' Jiwa';
        }

        if (n && state.nasView === 'sertifikasi-umk-nas') {
            const s = N_DATA.sertifikasi_umk_nas;
            const elTs = document.getElementById('nasSertifTotal');
            const elRs = document.getElementById('nasSertifReguler');
            const elSs = document.getElementById('nasSertifSelf');
            const elPp = document.getElementById('nasSertifPendamping');
            const elLp = document.getElementById('nasSertifLembaga');
            if (elTs) elTs.textContent = fmt(s.stats.total);
            if (elRs) elRs.textContent = fmt(s.stats.reguler);
            if (elSs) elSs.textContent = fmt(s.stats.self_declare);
            if (elPp) elPp.textContent = fmt(s.stats.pendamping);
            if (elLp) elLp.textContent = fmt(s.stats.lembaga);
        }

        if (n && state.nasView === 'pasar-modal') {
            const el = document.getElementById('pasarModalTotalAset');
            if (el) el.textContent = fmt(N_DATA.pasar_modal.total_aset);
        }

        if (n && state.nasView === 'kpbu-syariah') {
            const el1 = document.getElementById('kpbuTotalProyek');
            const el2 = document.getElementById('kpbuTotalPembiayaan');
            if (el1) el1.textContent = N_DATA.kpbu_syariah.total_proyek;
            if (el2) el2.textContent = N_DATA.kpbu_syariah.total_pembiayaan + ' T';
        }

        if (n && state.nasView === 'iknb-syariah') {
            const el = document.getElementById('iknbTotalAset');
            if (el) el.textContent = N_DATA.iknb_syariah.total_aset + ' T';
        }

        const periodEl = document.getElementById('periodText');
        if (periodEl) periodEl.textContent = periodLabel(state.period);
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
        const colors = [C.rose, C.blue, C.amber, C.emerald, C.sky];
        const eksporHtml = N_DATA.ekspor_negara.map((it, i) => `
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-bold text-slate-500 w-16 shrink-0 truncate sm:text-xs">${it.name}</span>
                <div class="flex-1 flex items-center gap-3">
                    <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full transition-all duration-1000" style="background-color:${colors[i] || C.slate}; width:${(it.value/1678*100)}%"></div>
                    </div>
                    <span class="text-[10px] font-black text-slate-700 w-10 text-right sm:text-xs">$${it.value}M</span>
                </div>
            </div>
        `).join('');
        document.getElementById('panelEksporNegara').innerHTML = eksporHtml;
    }

    function renderDayaSaingPanels() {
        const ds = N_DATA.daya_saing;
        const selector = document.getElementById('dsYearSelector');
        if (!selector) return;

        // Populate dropdown if empty
        if (selector.options.length === 0) {
            selector.innerHTML = ds.years.map(y => `<option value="${y}">${y}</option>`).join('');
            selector.value = state.dsYear;
        }

        const idx = ds.years.indexOf(state.dsYear);
        document.getElementById('dsTargetVal').textContent = ds.target[idx] || '-';
        document.getElementById('dsRealisasiVal').textContent = ds.realisasi[idx] || '-';
    }

    function renderPariwisataPanels() {
        const h = N_DATA.pariwisata.hotel;
        const table = document.getElementById('tableNasHotel');
        if (!table) return;

        table.innerHTML = h.map(it => `
            <tr>
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-slate-800">${it.name}</p>
                </td>
                <td class="px-6 py-4">
                    <p class="text-xs text-slate-500">${it.exp}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold ${it.status === 'Masih Berlaku' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'}">
                        ${it.status}
                    </span>
                </td>
            </tr>
        `).join('');
    }

    function renderKihPanels() {
        const kih = N_DATA.kih;
        const total = kih.status.data.reduce((a,b) => a+b, 0);

        const renderLegend = (startIndex, count) => {
            return kih.status.labels.slice(startIndex, startIndex + count).map((label, i) => {
                const idx = startIndex + i;
                const val = kih.status.data[idx];
                const pct = ((val/total)*100).toFixed(1);
                const color = kih.status.colors[idx];
                return `
                    <div class="flex items-start gap-3 p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="mt-1 h-3 w-3 shrink-0 rounded-full" style="background-color:${color}"></div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">${val} Kawasan</span>
                                <span class="text-xs font-black text-slate-900">${pct}%</span>
                            </div>
                            <p class="mt-0.5 text-xs font-bold text-slate-600 leading-tight">${label}</p>
                        </div>
                    </div>
                `;
            }).join('');
        };

        document.getElementById('kihLegendLeft').innerHTML = renderLegend(0, 1);
        document.getElementById('kihLegendRight').innerHTML = renderLegend(1, 2);

        const table = document.getElementById('tableNasKIH');
        if (table) {
            table.innerHTML = kih.list.map(it => `
                <tr>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-800">${it.name}</p>
                    </td>
                    <td class="px-6 py-4 text-xs font-semibold text-slate-500">${it.prov}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold ${it.status.includes('SK') ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'}">
                            ${it.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
    }

    function renderRphPanels() {
        const r = N_DATA.rph;
        const total = r.owner.data.reduce((a,b) => a+b, 0);
        const legend = document.getElementById('rphOwnerLegend');
        if (legend) {
            legend.innerHTML = r.owner.labels.map((label, i) => {
                const val = r.owner.data[i];
                const pct = ((val/total)*100).toFixed(1);
                return `
                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="h-3 w-3 rounded-full" style="background-color:${r.owner.colors[i]}"></div>
                            <span class="text-xs font-bold text-slate-600">${label}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-900">${val}</span>
                            <span class="text-[10px] font-bold text-slate-400 ml-1">(${pct}%)</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        const table = document.getElementById('tableNasRPHTop');
        if (table) {
            table.innerHTML = r.topTable.map(it => `
                <tr>
                    <td class="px-6 py-4 text-sm font-bold text-slate-800">${it.prov}</td>
                    <td class="px-6 py-4 text-right text-sm font-bold text-slate-900">${fmt(it.val)}</td>
                </tr>
            `).join('');
        }
    }

    function renderLphPanels() {
        const lph = N_DATA.lph;
        const total = lph.type.data.reduce((a,b) => a+b, 0);
        const legend = document.getElementById('lphTypeLegend');
        if (legend) {
            legend.innerHTML = lph.type.labels.map((label, i) => {
                const val = lph.type.data[i];
                const pct = ((val/total)*100).toFixed(1);
                return `
                    <div class="flex items-center justify-between p-3 rounded-xl border border-slate-100 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <div class="h-3 w-3 rounded-full" style="background-color:${lph.type.colors[i]}"></div>
                            <span class="text-xs font-bold text-slate-600">${label}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-slate-900">${val}</span>
                            <span class="text-[10px] font-bold text-slate-400 ml-1">(${pct}%)</span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        const table = document.getElementById('tableNasLPH');
        if (table) {
            table.innerHTML = lph.education.labels.map((label, i) => `
                <tr>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-800">${label}</p>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-sm font-bold text-slate-900">${fmt(lph.education.data[i])}</span>
                    </td>
                </tr>
            `).join('');
        }
    }

    function renderKhasPanels() {
        const k = N_DATA.khas;
        const table = document.getElementById('tableNasKhas');
        if (table) {
            table.innerHTML = k.peresmian.map(it => `
                <tr>
                    <td class="px-6 py-4 font-bold text-slate-800 text-sm">${it[0]}</td>
                    <td class="px-6 py-4 text-xs text-slate-500">${it[1]}, ${it[2]}</td>
                    <td class="px-6 py-4 text-xs font-bold text-slate-600">${it[3]}</td>
                    <td class="px-6 py-4 text-center font-black text-slate-900 text-sm">${it[4]}</td>
                    <td class="px-6 py-4 text-xs italic text-slate-400">${it[5]}</td>
                </tr>
            `).join('');
        }
    }

    function renderLogistikPanels() {
        const d = N_DATA.logistik;
        const total = d.types.data.reduce((a,b) => a+b, 0);
        const legend = document.getElementById('logistikTypeLegend');
        if (legend) {
            legend.innerHTML = d.types.labels.map((label, i) => {
                const val = d.types.data[i];
                const pct = ((val/total)*100).toFixed(1);
                return `
                    <div class="flex items-center justify-between p-2 rounded-lg border border-slate-50 bg-white shadow-sm">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full" style="background-color:${d.types.colors[i]}"></div>
                            <span class="text-[10px] font-bold text-slate-500">${label}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black text-slate-900">${fmt(val)}</span>
                            <span class="text-[9px] font-bold text-slate-400 ml-1">(${pct}%)</span>
                        </div>
                    </div>
                `;
            }).join('');
        }
    }

    function renderEksporProvPanels() {
        const d = N_DATA.percepatan_ekspor;
        const table = document.getElementById('tableNasEksporProv');
        if (table) {
            table.innerHTML = d.provinsi.map(it => `
                <tr>
                    <td class="px-6 py-4 font-bold text-slate-800 text-sm whitespace-nowrap">${it[0]}</td>
                    <td class="px-6 py-4 text-right text-xs text-slate-500">${fmt(it[1])}</td>
                    <td class="px-6 py-4 text-right text-xs text-slate-500">${fmt(it[2])}</td>
                    <td class="px-6 py-4 text-right text-xs text-slate-500">${fmt(it[3])}</td>
                    <td class="px-6 py-4 text-right font-bold text-slate-600 text-xs">${fmt(it[4])}</td>
                    <td class="px-6 py-4 text-right font-black text-slate-900 text-xs">${fmt(it[5])}</td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 rounded bg-slate-100 text-[9px] font-bold text-slate-500">${it[6]}%</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-xs font-bold ${it[7] > 0 ? 'text-emerald-500' : 'text-rose-500'}">${it[7] > 0 ? '+' : ''}${it[7].toFixed(2)}%</span>
                    </td>
                </tr>
            `).join('');
        }
    }

    function renderKodifikasiPanels() {
        const k = N_DATA.kodifikasi;
        const legend = document.getElementById('kodifScaleLegend');
        if (legend) {
            legend.innerHTML = k.scale.labels.map((label, i) => {
                const val = k.scale.data[i];
                return `
                    <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full" style="background-color:${k.scale.colors[i]}"></div>
                            <span class="text-[10px] font-bold text-slate-500">${label}</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-900">${val}%</span>
                    </div>
                `;
            }).join('');
        }
    }

    function renderKesehatanPanels() {
        const k = N_DATA.kesehatan;
        const table = document.getElementById('tableNasRS');
        if (table) {
            table.innerHTML = k.list.map(it => `
                <tr>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-slate-800">${it.name}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">${it.type}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-slate-500">${it.exp}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold ${it.status === 'Masih Aktif' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'}">
                            ${it.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }
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
        const ekColors = [C.emerald, C.blue, C.violet, C.rose];
        const pieTooltip = { backgroundColor:'#0f172a', padding:12, cornerRadius:10, titleFont:{weight:'700'}, callbacks: { label: c => { let total = c.chart.data.datasets[0].data.reduce((a, b) => a + b, 0); return `${c.label}: ${fmt(c.raw)} (${total > 0 ? ((c.raw / total) * 100).toFixed(1) : 0}%)`; } } };
        charts.nasEksporSektor = new Chart(document.getElementById('chartNasEksporSektor'), {
            type:'doughnut', data:{ labels:N_DATA.ekspor_sektor.labels, datasets:[{ data:N_DATA.ekspor_sektor.data, backgroundColor:ekColors, borderWidth:0 }] },
            options:{
                responsive:true, maintainAspectRatio:false, cutout:'75%',
                plugins:{ legend:{ display: false }, tooltip:pieTooltip }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart; ctx.restore();
                    let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.font = 'bold 24px Inter'; ctx.fillStyle = '#0f172a';
                    ctx.fillText(fmt(total) + '%', width / 2, height / 2); ctx.save();
                }
            }]
        });

        const ekLegend = document.getElementById('eksporSectorLegend');
        if (ekLegend) {
            ekLegend.innerHTML = N_DATA.ekspor_sektor.labels.map((l, i) => `
                <div class="flex items-center justify-between p-2 rounded-lg border border-slate-50 bg-slate-50/30">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full" style="background-color:${ekColors[i]}"></div>
                        <span class="text-[10px] font-bold text-slate-500">${l}</span>
                    </div>
                    <span class="text-[10px] font-black text-slate-900">${N_DATA.ekspor_sektor.data[i]}%</span>
                </div>
            `).join('');
        }

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
        const lData = slice(N_DATA.literasi.index, state.period).map(v => v * 100);
        charts.nasLiterasi = new Chart(document.getElementById('chartNasLiterasi'), {
            type:'line', data:{ labels:slice(N_DATA.literasi.years, state.period), datasets:[ makeLine(lData, C.teal) ]},
            options:{
                responsive:true,
                maintainAspectRatio:false,
                plugins:{
                    legend:{display:false},
                    tooltip:tooltipCfg({
                        callbacks: {
                            label: (c) => `Indeks: ${c.parsed.y.toFixed(2)}`
                        }
                    })
                },
                scales:{
                    y:{
                        beginAtZero:true,
                        max:100,
                        ticks: {
                            callback: v => v.toFixed(2)
                        }
                    }
                }
            }
        });
    }

    function createDayaSaingCharts() {
        const ds = N_DATA.daya_saing;
        const ctx = document.getElementById('chartDayaSaing');
        if (!ctx) return;

        charts.dayaSaing = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ds.years,
                datasets: [
                    {
                        label: 'Target RPJMN',
                        data: ds.target,
                        backgroundColor: '#A7D07C',
                        borderRadius: 6,
                        barThickness: mob() ? 20 : 40,
                        order: 2
                    },
                    {
                        label: 'Realisasi Capaian',
                        data: ds.realisasi,
                        borderColor: '#F2A86F',
                        backgroundColor: '#F2A86F',
                        type: 'line',
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#F2A86F',
                        pointBorderWidth: 3,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipCfg({
                        callbacks: {
                            label: (c) => `${c.dataset.label}: ${c.parsed.y}%`
                        }
                    })
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        grid: { color: GRID },
                        ticks: { callback: v => v + '%' }
                    }
                }
            }
        });
    }

    function createPariwisataCharts() {
        const p = N_DATA.pariwisata.restoran;
        const ctx = document.getElementById('chartNasPariwisataProv');
        if (!ctx) return;

        charts.nasPariwisataProv = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: p.labels,
                datasets: [{
                    label: 'Restoran Halal',
                    data: p.data,
                    backgroundColor: [C.emerald, C.blue, C.amber, C.violet, C.rose],
                    borderRadius: 6,
                    barThickness: mob() ? 15 : 25
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipCfg()
                },
                scales: {
                    x: { beginAtZero: true, grid: { color: GRID } },
                    y: { grid: { display: false }, ticks: { font: { weight: '600' } } }
                }
            }
        });
    }

    function createKihCharts() {
        const kih = N_DATA.kih;
        const total = kih.status.data.reduce((a,b) => a+b, 0);
        const ctx = document.getElementById('chartNasKIHStatus');
        if (!ctx) return;

        charts.nasKIHStatus = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: kih.status.fullLabels,
                datasets: [{
                    data: kih.status.data,
                    backgroundColor: kih.status.colors,
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipCfg({
                        callbacks: {
                            label: (c) => ` Jumlah: ${c.raw} Kawasan`
                        }
                    })
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart;
                    ctx.restore();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    ctx.font = 'bold 12px Inter';
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TOTAL', width / 2, height / 2 - 12);

                    ctx.font = 'bold 24px Inter';
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(total, width / 2, height / 2 + 10);
                    ctx.save();
                }
            }]
        });
    }

    function initKihMap() {
        if (!window.L) {
            document.getElementById('mapNasKIHFallback').classList.remove('hidden');
            return;
        }

        if (nasMap) {
            nasMap.remove();
        }

        nasMap = L.map('mapNasKIH', {
            center: [-2.5, 118],
            zoom: mob() ? 4 : 5,
            zoomControl: false
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(nasMap);

        L.control.zoom({ position: 'bottomright' }).addTo(nasMap);

        const markers = N_DATA.kih.sebaran;
        markers.forEach(m => {
            const isSK = m.status.includes('SK');
            const color = isSK ? '#10b981' : (m.status.includes('Pendampingan') ? '#3b82f6' : '#f59e0b');

            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${color}; width: 14px; height: 14px; border: 2.5px solid white; border-radius: 50%; box-shadow: 0 3px 6px rgba(0,0,0,0.16);"></div>`,
                iconSize: [14, 14],
                iconAnchor: [7, 7]
            });

            const marker = L.marker([m.lat, m.lng], { icon: icon }).addTo(nasMap);

            const popup = `
                <div class="p-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">${m.prov}</p>
                    <p class="text-xs font-bold text-slate-800 mb-1 leading-tight">${m.kawasan}</p>
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold ${isSK ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'}">
                        ${m.status}
                    </span>
                </div>
            `;

            marker.bindPopup(popup, { closeButton: false, minWidth: 160 });
            marker.on('mouseover', function() { this.openPopup(); });
            marker.on('mouseout', function() { this.closePopup(); });
        });
    }

    function createLphCharts() {
        const lph = N_DATA.lph;
        const total = lph.type.data.reduce((a,b) => a+b, 0);

        // Growth Chart
        charts.nasLPHGrowth = new Chart(document.getElementById('chartNasLPHGrowth'), {
            type: 'line',
            data: {
                labels: lph.growth.years,
                datasets: [makeLine(lph.growth.data, C.emerald)]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: lineScale('')
            }
        });

        // Type Chart
        charts.nasLPHType = new Chart(document.getElementById('chartNasLPHType'), {
            type: 'doughnut',
            data: {
                labels: lph.type.labels,
                datasets: [{
                    data: lph.type.data,
                    backgroundColor: lph.type.colors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '80%',
                plugins: {
                    legend: { display: false },
                    tooltip: tooltipCfg()
                }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart;
                    ctx.restore();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.font = 'bold 11px Inter';
                    ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TOTAL LPH', width / 2, height / 2 - 10);
                    ctx.font = 'bold 22px Inter';
                    ctx.fillStyle = '#0f172a';
                    ctx.fillText(total, width / 2, height / 2 + 10);
                    ctx.save();
                }
            }]
        });
    }

    function createKodifikasiCharts() {
        const k = N_DATA.kodifikasi;
        const totalScale = k.scale.data.reduce((a,b) => a+b, 0);
        charts.nasKodifScale = new Chart(document.getElementById('chartNasKodifScale'), {
            type: 'doughnut',
            data: {
                labels: k.scale.labels,
                datasets: [{ data: k.scale.data, backgroundColor: k.scale.colors, borderWidth: 0 }]
            },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: tooltipCfg() } },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart; ctx.restore();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.font = 'bold 20px Inter'; ctx.fillStyle = '#0f172a';
                    ctx.fillText(fmt(totalScale), width / 2, height / 2); ctx.save();
                }
            }]
        });
        charts.nasKodifCommodity = new Chart(document.getElementById('chartNasKodifCommodity'), {
            type: 'bar',
            data: { labels: k.topCommodity.labels, datasets: [{ label: 'Tonase', data: k.topCommodity.data, backgroundColor: hexRgba(C.emerald, 0.7), borderRadius: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } } } }
        });
        charts.nasKodifCompany = new Chart(document.getElementById('chartNasKodifCompany'), {
            type: 'bar',
            data: { labels: k.topCompany.labels, datasets: [{ label: 'Nilai Ekspor', data: k.topCompany.data, backgroundColor: hexRgba(C.blue, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { y: { grid: { display: false } } } }
        });
        charts.nasKodifPortOrigin = new Chart(document.getElementById('chartNasKodifPortOrigin'), {
            type: 'bar',
            data: { labels: k.portOrigin.labels, datasets: [{ data: k.portOrigin.data, backgroundColor: hexRgba(C.teal, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { y: { grid: { display: false } } } }
        });
        charts.nasKodifPortDest = new Chart(document.getElementById('chartNasKodifPortDest'), {
            type: 'bar',
            data: { labels: k.portDest.labels, datasets: [{ data: k.portDest.data, backgroundColor: hexRgba(C.amber, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { y: { grid: { display: false } } } }
        });
    }

    function createRphCharts() {
        const r = N_DATA.rph;
        const total = r.owner.data.reduce((a,b) => a+b, 0);
        charts.nasRPHOwner = new Chart(document.getElementById('chartNasRPHOwner'), {
            type: 'doughnut',
            data: { labels: r.owner.labels, datasets: [{ data: r.owner.data, backgroundColor: r.owner.colors, borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '80%',
                plugins: { legend: { display: false }, tooltip: tooltipCfg() }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart;
                    ctx.restore();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.font = 'bold 12px Inter'; ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TOTAL RPH', width / 2, height / 2 - 12);
                    ctx.font = 'bold 24px Inter'; ctx.fillStyle = '#0f172a';
                    ctx.fillText(total, width / 2, height / 2 + 10);
                    ctx.save();
                }
            }]
        });

        // Initialize Map
        if (nasMapRPH) { nasMapRPH.remove(); }
        nasMapRPH = L.map('mapNasRPH').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(nasMapRPH);

        r.list.forEach(m => {
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${C.orange}; width: 14px; height: 14px; border: 2.5px solid white; border-radius: 50%; box-shadow: 0 3px 6px rgba(0,0,0,0.16);"></div>`,
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            const marker = L.marker([m[1], m[2]], { icon: icon }).addTo(nasMapRPH);
            const popup = `<div class="p-1"><p class="text-[10px] font-bold text-slate-400 uppercase mb-1">${m[0]}</p><p class="text-xs font-black text-slate-800">${m[3]} RPH Halal</p></div>`;
            marker.bindPopup(popup, { closeButton: false, minWidth: 140 });
            marker.on('mouseover', function() { this.openPopup(); });
            marker.on('mouseout', function() { this.closePopup(); });
        });
    }

    function createHvcCharts() {
        const h = N_DATA.hvc;
        const colors = [C.blue, C.rose, C.amber, C.teal, C.emerald];

        charts.nasHVCGrowth = new Chart(document.getElementById('chartNasHVCGrowth'), {
            type: 'line',
            data: {
                labels: h.quarters,
                datasets: h.sectors.map((s, i) => ({
                    label: s, data: h.growth[i].map(v => v * 100),
                    borderColor: colors[i], backgroundColor: hexRgba(colors[i], 0.1),
                    borderWidth: 2, pointRadius: 2, tension: 0.3
                }))
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } }, tooltip: tooltipCfg({ callbacks: { label: c => `${c.dataset.label}: ${c.parsed.y.toFixed(2)}%` } }) },
                scales: { y: { ticks: { callback: v => v + '%' } } }
            }
        });

        charts.nasHVCContrib = new Chart(document.getElementById('chartNasHVCContrib'), {
            type: 'line',
            data: {
                labels: h.quarters,
                datasets: h.sectors.map((s, i) => ({
                    label: s, data: h.contribution[i].map(v => v * 100),
                    borderColor: colors[i], borderWidth: 1.5, pointRadius: 0, tension: 0.3
                }))
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } } },
                scales: { y: { ticks: { callback: v => v.toFixed(2) } } }
            }
        });

        charts.nasHVCPangsa = new Chart(document.getElementById('chartNasHVCPangsa'), {
            type: 'bar',
            data: {
                labels: ["Q1 22", "Q2 22", "Q3 22", "Q4 22", "Q1 23", "Q2 23", "Q3 23", "Q4 23", "Q1 24", "Q2 24", "Q3 24", "Q4 24", "Q1 25", "Q2 25", "Q3 25", "Q4 25"],
                datasets: h.pangsaSectors.map((s, i) => ({
                    label: s, data: h.pangsaData[i].map(v => v * 100),
                    backgroundColor: h.pangsaColors[i], stack: 'a'
                }))
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom', labels: { boxWidth: 8, font: { size: 10 } } } },
                scales: { y: { stacked: true, ticks: { callback: v => v + '%' } }, x: { stacked: true } }
            }
        });
    }

    function createKhasCharts() {
        const k = N_DATA.khas;
        if (nasMapKhas) { nasMapKhas.remove(); }
        nasMapKhas = L.map('mapNasKhas').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(nasMapKhas);

        k.sebaran.forEach(m => {
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${C.emerald}; width: 14px; height: 14px; border: 2.5px solid white; border-radius: 50%; box-shadow: 0 3px 6px rgba(0,0,0,0.16);"></div>`,
                iconSize: [14, 14], iconAnchor: [7, 7]
            });
            const marker = L.marker([m[3], m[4]], { icon: icon }).addTo(nasMapKhas);
            const popup = `
                <div class="p-1">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">${m[0]}</p>
                    <p class="text-xs font-black text-slate-800 mb-1">${m[2]} Tenant Zona KHAS</p>
                    <p class="text-[10px] text-slate-500 italic">${m[1]}</p>
                </div>
            `;
            marker.bindPopup(popup, { closeButton: false, minWidth: 160 });
            marker.on('mouseover', function() { this.openPopup(); });
            marker.on('mouseout', function() { this.closePopup(); });
        });
    }

    function createUmkmIhCharts() {
        const u = N_DATA.umkm_ih;
        charts.nasUmkmIh = new Chart(document.getElementById('chartNasUmkmIh'), {
            type: 'bar',
            data: {
                labels: u.labels,
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: u.data,
                    backgroundColor: hexRgba(C.blue, 0.7),
                    borderRadius: 8,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: { x: { grid: { display: false } }, y: { beginAtZero: true } }
            }
        });
    }

    function createIndustriDagingCharts() {
        const d = N_DATA.industri_daging;
        const setVal = (id, val) => { const el = document.getElementById(id); if(el) el.innerText = fmt(val); };
        setVal('statRPHRTotal', d.rphr.total); setVal('statRPHROps', d.rphr.ops);
        setVal('statRPHRNkv', d.rphr.nkv); setVal('statRPHRHalal', d.rphr.halal); setVal('statRPHRBoth', d.rphr.both);
        setVal('statRPHUTotal', d.rphu.total); setVal('statRPHUOps', d.rphu.ops);
        setVal('statRPHUNkv', d.rphu.nkv); setVal('statRPHUHalal', d.rphu.halal); setVal('statRPHUBoth', d.rphu.both);

        const makeDonut = (id, data, legendId) => {
            const total = data.data.reduce((a,b) => a+b, 0);
            const chart = new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: { labels: data.labels, datasets: [{ data: data.data, backgroundColor: data.colors, borderWidth: 0 }] },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '75%',
                    plugins: { legend: { display: false }, tooltip: tooltipCfg() }
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: (chart) => {
                        const { ctx, width, height } = chart; ctx.restore();
                        ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                        ctx.font = 'bold 18px Inter'; ctx.fillStyle = '#0f172a';
                        ctx.fillText(fmt(total), width / 2, height / 2); ctx.save();
                    }
                }]
            });
            const legend = document.getElementById(legendId);
            if (legend) {
                legend.innerHTML = data.labels.map((l, i) => {
                    const pct = ((data.data[i]/total)*100).toFixed(1);
                    return `
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-2 rounded-full" style="background-color:${data.colors[i]}"></div>
                                <span class="text-[10px] font-bold text-slate-500">${l}</span>
                            </div>
                            <span class="text-[10px] font-black text-slate-900">${pct}%</span>
                        </div>
                    `;
                }).join('');
            }
            return chart;
        };
        charts.nasColdStorage = makeDonut('chartNasColdStorage', d.coldStorage, 'coldStorageLegend');
        charts.nasKios = makeDonut('chartNasKios', d.kios, 'kiosLegend');
        charts.nasUsahaDaging = makeDonut('chartNasUsahaDaging', d.usahaDaging, 'usahaDagingLegend');
    }

    function createAktivitasCharts() {
        const d = N_DATA.aktivitas_usaha;
        charts.nasAktivitasNilai = new Chart(document.getElementById('chartNasAktivitasNilai'), {
            type: 'line',
            data: {
                labels: d.quarters,
                datasets: [
                    { label: 'Nilai Pembiayaan (Triliun)', data: d.nilai, borderColor: C.blue, backgroundColor: hexRgba(C.blue, 0.1), fill: true, tension: 0.3, pointRadius: 2 },
                    { label: 'Pangsa Pembiayaan (%)', data: d.pangsa_pembiayaan.map(v => v * 100), borderColor: C.amber, tension: 0.3, yAxisID: 'y1', pointRadius: 0 },
                    { label: 'Pangsa Aktivitas (%)', data: d.pangsa_aktivitas.map(v => v * 100), borderColor: C.emerald, tension: 0.3, yAxisID: 'y1', pointRadius: 0 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } }, tooltip: tooltipCfg() },
                scales: {
                    y: { grid: { display: false } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => v.toFixed(1) + '%' } }
                }
            }
        });
    }

    function createEksporPdbCharts() {
        const d = N_DATA.ekspor_pdb;
        charts.nasEksporPdbRatio = new Chart(document.getElementById('chartNasEksporPdbRatio'), {
            type: 'bar',
            data: {
                labels: d.years,
                datasets: [
                    { label: 'Ekspor Produk Halal (Triliun)', data: d.ekspor, backgroundColor: hexRgba(C.blue, 0.7), borderRadius: 4 },
                    { label: 'PDB Nasional (Triliun)', data: d.pdb, backgroundColor: hexRgba(C.slate, 0.2), borderRadius: 4 },
                    { label: 'Rasio (%)', data: d.ratio, borderColor: C.amber, type: 'line', yAxisID: 'y1', tension: 0.3, pointRadius: 4, pointBackgroundColor: '#fff', borderWidth: 3 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } }, tooltip: tooltipCfg() },
                scales: {
                    y: { grid: { color: GRID } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => v + '%' } }
                }
            }
        });
    }

    function createLogistikCharts() {
        const d = N_DATA.logistik;
        const total = d.types.data.reduce((a,b) => a+b, 0);
        charts.nasLogistikType = new Chart(document.getElementById('chartNasLogistikType'), {
            type: 'doughnut',
            data: { labels: d.types.labels, datasets: [{ data: d.types.data, backgroundColor: d.types.colors, borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '80%',
                plugins: { legend: { display: false }, tooltip: tooltipCfg() }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart; ctx.restore();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.font = 'bold 12px Inter'; ctx.fillStyle = '#94a3b8';
                    ctx.fillText('TOTAL', width / 2, height / 2 - 12);
                    ctx.font = 'bold 24px Inter'; ctx.fillStyle = '#0f172a';
                    ctx.fillText(fmt(total), width / 2, height / 2 + 10); ctx.save();
                }
            }]
        });
    }

    function createPercepatanEksporCharts() {
        const d = N_DATA.percepatan_ekspor;

        charts.nasEksporTrend = new Chart(document.getElementById('chartNasEksporTrend'), {
            type: 'bar',
            data: { labels: d.years, datasets: [{ label: 'Nilai Ekspor', data: d.nilai, backgroundColor: hexRgba(C.blue, 0.7), borderRadius: 6 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() } }
        });

        const totalSectors = d.sectorContrib.data.reduce((a,b) => a+b, 0).toFixed(0);
        charts.nasEksporSector = new Chart(document.getElementById('chartNasEksporSector'), {
            type: 'doughnut',
            data: { labels: d.sectorContrib.labels, datasets: [{ data: d.sectorContrib.data, backgroundColor: d.sectorContrib.colors, borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: { legend: { display: false }, tooltip: tooltipCfg({ callbacks: { label: c => `${c.label}: ${c.parsed}%` } }) }
            },
            plugins: [{
                id: 'centerText',
                beforeDraw: (chart) => {
                    const { ctx, width, height } = chart; ctx.restore();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    ctx.font = 'bold 24px Inter'; ctx.fillStyle = '#0f172a';
                    ctx.fillText(totalSectors + '%', width / 2, height / 2); ctx.save();
                }
            }]
        });

        const legend = document.getElementById('percepatanEksporSectorLegend');
        if (legend) {
            legend.innerHTML = d.sectorContrib.labels.map((l, i) => `
                <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-100 bg-slate-50/50 transition-all hover:bg-slate-100/80">
                    <div class="flex items-center gap-3">
                        <div class="h-3 w-3 rounded-full shadow-sm" style="background-color:${d.sectorContrib.colors[i]}"></div>
                        <span class="text-[11px] font-bold text-slate-600">${l}</span>
                    </div>
                    <span class="text-[11px] font-black text-slate-900">${d.sectorContrib.data[i]}%</span>
                </div>
            `).join('');
        }

        charts.nasEksporCountries = new Chart(document.getElementById('chartNasEksporCountries'), {
            type: 'bar',
            data: { labels: d.topCountries.labels, datasets: [{ data: d.topCountries.data, backgroundColor: hexRgba(C.emerald, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() } }
        });

        charts.nasEksporPEB = new Chart(document.getElementById('chartNasEksporPEB'), {
            type: 'line',
            data: { labels: d.peb952.months, datasets: [{ label: 'Jumlah PEB', data: d.peb952.data, borderColor: C.teal, backgroundColor: hexRgba(C.teal, 0.1), fill: true, tension: 0.3 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() } }
        });

        charts.nasEksporYoy = new Chart(document.getElementById('chartNasEksporYoy'), {
            type: 'bar',
            data: {
                labels: d.yoy.labels,
                datasets: [
                    { label: 'Nilai 2024', data: d.yoy.nilai2024, backgroundColor: hexRgba(C.slate, 0.2), borderRadius: 4 },
                    { label: 'Nilai 2025', data: d.yoy.nilai2025, backgroundColor: hexRgba(C.blue, 0.7), borderRadius: 4 },
                    { label: 'YoY Growth (%)', data: d.yoy.yoy, borderColor: C.amber, type: 'line', yAxisID: 'y1', tension: 0.3, pointRadius: 3, pointBackgroundColor: '#fff' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top', labels: { boxWidth: 10, font: { size: 10 } } },
                    tooltip: tooltipCfg({ callbacks: { label: c => `${c.dataset.label}: ${c.dataset.type === 'line' ? c.parsed.y.toFixed(2)+'%' : fmt(c.parsed.y)}` } })
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 8 } } },
                    y: { grid: { color: GRID }, ticks: { font: { size: 10 } } },
                    y1: { position: 'right', grid: { display: false }, ticks: { font: { size: 10 }, callback: v => v + '%' } }
                }
            }
        });
    }

    function createAsetKeuanganCharts() {
        const d = N_DATA.aset_keuangan;
        charts.nasAsetKeuanganCombo = new Chart(document.getElementById('chartNasAsetKeuanganCombo'), {
            type: 'bar',
            data: {
                labels: d.years,
                datasets: [
                    { label: 'Total Aset JKS (Triliun)', data: d.aset, backgroundColor: hexRgba(C.violet, 0.7), borderRadius: 4 },
                    { label: 'PDB Nasional (Triliun)', data: d.pdb, backgroundColor: hexRgba(C.slate, 0.2), borderRadius: 4 },
                    { label: 'Rasio (%)', data: d.ratio, borderColor: C.amber, type: 'line', yAxisID: 'y1', tension: 0.3, pointRadius: 4, pointBackgroundColor: '#fff', borderWidth: 3 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } }, tooltip: tooltipCfg() },
                scales: {
                    y: { grid: { color: GRID } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => v + '%' } }
                }
            }
        });
    }

    function createPembiayaanUmkmCharts() {
        const d = N_DATA.pembiayaan_umkm;
        const pieTooltip = { backgroundColor:'#0f172a', padding:12, cornerRadius:10, titleFont:{weight:'700'}, callbacks: { label: c => { let total = c.chart.data.datasets[0].data.reduce((a, b) => a + b, 0); return `${c.label}: ${fmt(c.raw)} (${total > 0 ? ((c.raw / total) * 100).toFixed(1) : 0}%)`; } } };

        const renderLegendDetail = (elId, labels, data, colors) => {
            const el = document.getElementById(elId);
            if (!el) return;
            const total = data.reduce((a, b) => a + b, 0);
            el.innerHTML = labels.map((l, i) => {
                const val = data[i];
                const pct = total > 0 ? ((val / total) * 100).toFixed(1) : 0;
                return `
                    <div class="flex items-center justify-between p-2 rounded-xl border border-slate-100 bg-slate-50/50 transition-all hover:bg-slate-100">
                        <div class="flex items-center gap-2">
                            <div class="h-2.5 w-2.5 rounded-full shadow-sm" style="background-color:${colors[i]}"></div>
                            <span class="text-[10px] font-bold text-slate-600 truncate max-w-[120px]">${l}</span>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-[10px] font-black text-slate-900">${fmt(val)}</p>
                            <p class="text-[8px] font-bold text-slate-400">${pct}%</p>
                        </div>
                    </div>
                `;
            }).join('');
        };

        charts.nasUmkmKomposisi = new Chart(document.getElementById('chartNasUmkmKomposisi'), {
            type: 'doughnut', data: { labels: d.komposisi.labels, datasets: [{ data: d.komposisi.data, backgroundColor: [C.emerald, C.amber, C.rose], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 20px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmKomposisiLegend', d.komposisi.labels, d.komposisi.data, [C.emerald, C.amber, C.rose]);

        charts.nasUmkmKontribusi = new Chart(document.getElementById('chartNasUmkmKontribusi'), {
            type: 'bar', data: { labels: d.kontribusi.labels, datasets: [{ label: 'Outstanding (Rp Miliar)', data: d.kontribusi.data, backgroundColor: hexRgba(C.blue, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { x: { grid: { color: GRID } }, y: { grid: { display: false }, ticks: { font: { size: mob()?9:11 } } } } }
        });

        charts.nasUmkmBankRasio = new Chart(document.getElementById('chartNasUmkmBankRasio'), {
            type: 'doughnut', data: { labels: d.perbankan.rasio.labels, datasets: [{ data: d.perbankan.rasio.data, backgroundColor: [C.emerald, C.slate], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 12px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmBankRasioLegend', d.perbankan.rasio.labels, d.perbankan.rasio.data, [C.emerald, C.slate]);

        charts.nasUmkmBankLembaga = new Chart(document.getElementById('chartNasUmkmBankLembaga'), {
            type: 'doughnut', data: { labels: d.perbankan.komposisi_lembaga.labels, datasets: [{ data: d.perbankan.komposisi_lembaga.data, backgroundColor: [C.violet, C.slate, C.amber], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 16px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmBankLembagaLegend', d.perbankan.komposisi_lembaga.labels, d.perbankan.komposisi_lembaga.data, [C.violet, C.slate, C.amber]);

        charts.nasUmkmBankTren = new Chart(document.getElementById('chartNasUmkmBankTren'), {
            type: 'line', data: { labels: d.perbankan.tren.labels, datasets: [{ label: 'BUS', ...makeLine(d.perbankan.tren.bus, C.blue) }, { label: 'UUS', ...makeLine(d.perbankan.tren.uus, C.violet) }, { label: 'BPRS', ...makeLine(d.perbankan.tren.bprs, C.amber) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });

        charts.nasUmkmNonBankRasio = new Chart(document.getElementById('chartNasUmkmNonBankRasio'), {
            type: 'doughnut', data: { labels: d.non_perbankan.rasio.labels, datasets: [{ data: d.non_perbankan.rasio.data, backgroundColor: [C.amber, C.slate], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 12px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmNonBankRasioLegend', d.non_perbankan.rasio.labels, d.non_perbankan.rasio.data, [C.amber, C.slate]);

        charts.nasUmkmNonBankLembaga = new Chart(document.getElementById('chartNasUmkmNonBankLembaga'), {
            type: 'doughnut', data: { labels: d.non_perbankan.komposisi_lembaga.labels, datasets: [{ data: d.non_perbankan.komposisi_lembaga.data, backgroundColor: [C.blue, C.teal, C.rose, C.orange, C.violet, C.emerald], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 16px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmNonBankLembagaLegend', d.non_perbankan.komposisi_lembaga.labels, d.non_perbankan.komposisi_lembaga.data, [C.blue, C.teal, C.rose, C.orange, C.violet, C.emerald]);

        charts.nasUmkmNonBankKontribusi = new Chart(document.getElementById('chartNasUmkmNonBankKontribusi'), {
            type: 'bar', data: { labels: d.non_perbankan.kontribusi.labels, datasets: [{ label: 'Outstanding (Rp Miliar)', data: d.non_perbankan.kontribusi.data, backgroundColor: hexRgba(C.amber, 0.7), borderRadius: 4 }] },
            options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { x: { grid: { color: GRID } }, y: { grid: { display: false }, ticks: { font: { size: mob()?9:11 } } } } }
        });

        charts.nasUmkmScfPerkembangan = new Chart(document.getElementById('chartNasUmkmScfPerkembangan'), {
            type: 'bar', data: {
                labels: d.scf.perkembangan.labels,
                datasets: [
                    { label: 'Penerbit Syariah', data: d.scf.perkembangan.penerbit_syariah, type: 'line', borderColor: C.blue, backgroundColor: '#fff', borderWidth: 2, pointRadius: 4, yAxisID: 'y1' },
                    { label: 'Penerbit Total', data: d.scf.perkembangan.penerbit_total, type: 'line', borderColor: C.amber, backgroundColor: '#fff', borderWidth: 2, pointRadius: 4, yAxisID: 'y1' },
                    { label: 'Nilai Penerbitan Syariah', data: d.scf.perkembangan.nilai_syariah, backgroundColor: hexRgba(C.emerald, 0.7), borderRadius: 4, yAxisID: 'y' },
                    { label: 'Nilai Penerbitan Total', data: d.scf.perkembangan.nilai_total, backgroundColor: hexRgba(C.slate, 0.2), borderRadius: 4, yAxisID: 'y' }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg({ position: 'top', labels: { boxWidth: 10, font: { size: 10 } } }), tooltip: tooltipCfg() },
                scales: { x: { grid: { display: false } }, y: { position: 'left', grid: { color: GRID }, title: { display: true, text: 'Nilai (Miliar)' } }, y1: { position: 'right', grid: { display: false }, title: { display: true, text: 'Jml Penerbit' } } }
            }
        });

        charts.nasUmkmScfMarketshare = new Chart(document.getElementById('chartNasUmkmScfMarketshare'), {
            type: 'doughnut', data: { labels: d.scf.marketshare.labels, datasets: [{ data: d.scf.marketshare.data, backgroundColor: [C.emerald, C.slate], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { display: false }, tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; let total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0); ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 16px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText(fmt(total), width / 2, height / 2); ctx.save(); } }]
        });
        renderLegendDetail('umkmScfMarketshareLegend', d.scf.marketshare.labels, d.scf.marketshare.data, [C.emerald, C.slate]);
    }

    function createPerbankanSyariahCharts() {
        const d = N_DATA.perbankan_syariah;
        const pieTooltip = { backgroundColor:'#0f172a', padding:12, cornerRadius:10, titleFont:{weight:'700'}, callbacks: { label: c => { let total = c.chart.data.datasets[0].data.reduce((a, b) => a + b, 0); return `${c.label}: ${fmt(c.raw)} (${total > 0 ? ((c.raw / total) * 100).toFixed(1) : 0}%)`; } } };

        // Market Share Pie
        charts.nasPerbankanMarket = new Chart(document.getElementById('chartNasPerbankanMarket'), {
            type: 'doughnut', data: { labels: d.market_share.labels, datasets: [{ data: d.market_share.data, backgroundColor: [C.slate, C.emerald], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: barLegendCfg(), tooltip: pieTooltip } },
            plugins: [{ id: 'centerText', beforeDraw: chart => { const { ctx, width, height } = chart; ctx.restore(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle'; ctx.font = 'bold 18px Inter'; ctx.fillStyle = '#0f172a'; ctx.fillText('100%', width / 2, height / 2); ctx.save(); } }]
        });

        // Rekening Line
        charts.nasPerbankanRekening = new Chart(document.getElementById('chartNasPerbankanRekening'), {
            type: 'line', data: { labels: d.rekening.labels, datasets: [{ label: 'Simpanan', ...makeLine(d.rekening.simpanan, C.blue) }, { label: 'Pembiayaan', ...makeLine(d.rekening.pembiayaan, C.amber) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });

        // Aset Lembaga Bar
        charts.nasPerbankanLembaga = new Chart(document.getElementById('chartNasPerbankanLembaga'), {
            type: 'bar', data: { labels: d.aset_lembaga.labels, datasets: [{ label: 'BUS', data: d.aset_lembaga.bus, backgroundColor: C.emerald, borderRadius: 4 }, { label: 'UUS', data: d.aset_lembaga.uus, backgroundColor: C.blue, borderRadius: 4 }, { label: 'BPRS', data: d.aset_lembaga.bprs, backgroundColor: C.amber, borderRadius: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });

        // Marketshare Tren Line
        charts.nasPerbankanMarketTren = new Chart(document.getElementById('chartNasPerbankanMarketTren'), {
            type: 'line', data: { labels: d.marketshare_tren.labels, datasets: [{ label: 'Aset', ...makeLine(d.marketshare_tren.aset, C.emerald, false) }, { label: 'PYD', ...makeLine(d.marketshare_tren.pyd, C.blue, false) }, { label: 'DPK', ...makeLine(d.marketshare_tren.dpk, C.violet, false) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });

        renderPerbankanAsetTable();

        // Rekening Table
        const tableRekening = document.getElementById('tableNasPerbankanRekening');
        if (tableRekening) {
            tableRekening.innerHTML = d.rekening.labels.map((l, i) => `<tr><td class="px-4 py-3 text-xs font-medium text-slate-700">${l}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${d.rekening.simpanan[i]}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${d.rekening.pembiayaan[i]}</td></tr>`).reverse().join('');
        }

        // Lembaga Table
        const tableLembaga = document.getElementById('tableNasPerbankanLembaga');
        if (tableLembaga) {
            tableLembaga.innerHTML = d.aset_lembaga.labels.map((l, i) => `<tr><td class="px-4 py-3 text-xs font-medium text-slate-700">${l}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${fmt(d.aset_lembaga.bus[i])}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${fmt(d.aset_lembaga.uus[i])}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${fmt(d.aset_lembaga.bprs[i])}</td></tr>`).reverse().join('');
        }

        // Marketshare Table
        const tableMarket = document.getElementById('tableNasPerbankanMarketTren');
        if (tableMarket) {
            tableMarket.innerHTML = d.marketshare_tren.labels.map((l, i) => `<tr><td class="px-4 py-3 text-xs font-medium text-slate-700">${l}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${d.marketshare_tren.aset[i]}%</td><td class="px-4 py-3 text-xs font-black text-slate-900">${d.marketshare_tren.pyd[i]}%</td><td class="px-4 py-3 text-xs font-black text-slate-900">${d.marketshare_tren.dpk[i]}%</td></tr>`).reverse().join('');
        }

        // DPK & PYD Prov Tables — paginated
        renderPerbankanDpkTable();
        renderPerbankanPydTable();
    }

    function renderPerbankanDpkTable() {
        const data = N_DATA.perbankan_syariah.map_dpk;
        const perPage = 8;
        const total = data.length;
        const start = (state.perbankanDpkPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        const tbody = document.getElementById('tableNasPerbankanDpkProv');
        if (tbody) {
            tbody.innerHTML = data.slice(start, end).map(it =>
                `<tr><td class="px-4 py-2.5 text-[11px] font-medium text-slate-700">${it[0]}</td><td class="px-4 py-2.5 text-[11px] font-black text-slate-900 text-right">${it[2].toFixed(2)}%</td></tr>`
            ).join('');
        }
        const rangeEl = document.getElementById('tableNasPerbankanDpkRange');
        if (rangeEl) rangeEl.textContent = `${start + 1} - ${end} dari ${total}`;
        const prev = document.getElementById('btnPrevPerbankanDpk');
        const next = document.getElementById('btnNextPerbankanDpk');
        if (prev) prev.disabled = state.perbankanDpkPage === 1;
        if (next) next.disabled = end >= total;
    }

    function renderPerbankanPydTable() {
        const data = N_DATA.perbankan_syariah.map_pembiayaan;
        const perPage = 8;
        const total = data.length;
        const start = (state.perbankanPydPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        const tbody = document.getElementById('tableNasPerbankanPydProv');
        if (tbody) {
            tbody.innerHTML = data.slice(start, end).map(it =>
                `<tr><td class="px-4 py-2.5 text-[11px] font-medium text-slate-700">${it[0]}</td><td class="px-4 py-2.5 text-[11px] font-black text-slate-900 text-right">${it[2].toFixed(2)}%</td></tr>`
            ).join('');
        }
        const rangeEl = document.getElementById('tableNasPerbankanPydRange');
        if (rangeEl) rangeEl.textContent = `${start + 1} - ${end} dari ${total}`;
        const prev = document.getElementById('btnPrevPerbankanPyd');
        const next = document.getElementById('btnNextPerbankanPyd');
        if (prev) prev.disabled = state.perbankanPydPage === 1;
        if (next) next.disabled = end >= total;
    }

    function renderPerbankanAsetTable() {
        const d = N_DATA.perbankan_syariah;
        const perPage = 8;
        const total = d.perbankan_table.length;
        const start = (state.perbankanAsetPage - 1) * perPage;
        const end = Math.min(start + perPage, total);
        const pagedData = d.perbankan_table.slice(start, end);

        const table = document.getElementById('tableNasPerbankanAset');
        if (table) {
            table.innerHTML = pagedData.map(it => `<tr><td class="px-4 py-3 text-xs font-medium text-slate-700">${it[0]}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${fmt(it[1])}</td><td class="px-4 py-3 text-xs font-black text-slate-900">${it[2]}%</td></tr>`).join('');
        }

        const rangeLabel = document.getElementById('tableNasPerbankanAsetRange');
        if (rangeLabel) rangeLabel.textContent = `${start + 1} - ${end} dari ${total}`;

        const btnPrev = document.getElementById('btnPrevPerbankanAset');
        const btnNext = document.getElementById('btnNextPerbankanAset');
        if (btnPrev) btnPrev.disabled = state.perbankanAsetPage === 1;
        if (btnNext) btnNext.disabled = end >= total;
    }

    function initPerbankanMap() {
        if (!window.L) return;
        const d = N_DATA.perbankan_syariah;
        const TILE = 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
        const TILE_ATTR = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';

        if (nasMapDpk) { nasMapDpk.remove(); nasMapDpk = null; }
        const elDpk = document.getElementById('mapNasMarketDpk');
        if (elDpk) {
            nasMapDpk = L.map('mapNasMarketDpk', { zoomControl: true }).setView([-2.5, 118], 5);
            L.tileLayer(TILE, { attribution: TILE_ATTR, maxZoom: 18 }).addTo(nasMapDpk);
            d.map_dpk.forEach(it => {
                if (it[2] > 0) {
                    const r = Math.max(6, Math.min(20, 5 + it[2] / 5));
                    L.circleMarker([it[3], it[4]], { radius: r, fillColor: C.emerald, color: '#fff', weight: 2, fillOpacity: 0.82 })
                     .bindPopup(`<strong>${it[0]}</strong><br/>Marketshare DPK: <b>${it[2].toFixed(2)}%</b>`)
                     .addTo(nasMapDpk);
                }
            });
        }

        if (nasMapPyd) { nasMapPyd.remove(); nasMapPyd = null; }
        const elPyd = document.getElementById('mapNasMarketPyd');
        if (elPyd) {
            nasMapPyd = L.map('mapNasMarketPyd', { zoomControl: true }).setView([-2.5, 118], 5);
            L.tileLayer(TILE, { attribution: TILE_ATTR, maxZoom: 18 }).addTo(nasMapPyd);
            d.map_pembiayaan.forEach(it => {
                if (it[2] > 0) {
                    const r = Math.max(6, Math.min(20, 5 + it[2] / 5));
                    L.circleMarker([it[3], it[4]], { radius: r, fillColor: C.blue, color: '#fff', weight: 2, fillOpacity: 0.82 })
                     .bindPopup(`<strong>${it[0]}</strong><br/>Marketshare Pembiayaan: <b>${it[2].toFixed(2)}%</b>`)
                     .addTo(nasMapPyd);
                }
            });
        }
    }

    function createJaminanSosialCharts() {
        const d = N_DATA.jaminan_sosial;

        charts.nasJamsosAceh = new Chart(document.getElementById('chartNasJamsosAceh'), {
            type: 'bar',
            data: {
                labels: d.aceh.labels,
                datasets: [
                    {
                        label: 'Investasi Layanan Syariah (Rp Triliun)',
                        data: d.aceh.investasi,
                        backgroundColor: C.cyan,
                        borderRadius: 4,
                        yAxisID: 'y',
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Peserta Layanan Syariah',
                        data: d.aceh.peserta,
                        borderColor: C.rose,
                        backgroundColor: hexRgba(C.rose, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointRadius: 3,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false } }
                }
            }
        });

        charts.nasJamsosPie = new Chart(document.getElementById('chartNasJamsosPie'), {
            type: 'doughnut',
            data: { labels: d.portofolio.labels, datasets: [{ data: d.portofolio.data, backgroundColor: [C.slate, C.cyan], borderWidth: 0 }] },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '75%',
                plugins: { legend: { display: false }, tooltip: tooltipCfg() }
            },
            plugins: [{
                id: 'centerTotal',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#64748b';
                    ctx.font = 'bold 10px Inter';
                    ctx.fillText('Total (Triliun)', chart.width / 2, chart.height / 2 - 10);
                    ctx.fillStyle = '#0f172a';
                    ctx.font = '900 16px Inter';
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.fillText(total.toLocaleString('id-ID', { minimumFractionDigits: 2 }), chart.width / 2, chart.height / 2 + 8);
                    ctx.restore();
                }
            }]
        });

        const lgPie = document.getElementById('jamsosPieLegend');
        if (lgPie) {
            const total = d.portofolio.data.reduce((a, b) => a + b, 0);
            lgPie.innerHTML = d.portofolio.labels.map((l, i) => {
                const val = d.portofolio.data[i];
                const pct = ((val / total) * 100).toFixed(2);
                return `<div class="flex items-center justify-between"><div class="flex items-center gap-2"><span class="h-2 w-2 rounded-full" style="background-color:${[C.slate, C.cyan][i]}"></span><span class="text-[10px] font-bold text-slate-600 uppercase">${l}</span></div><div class="text-right"><span class="text-xs font-black text-slate-900 block">${val.toLocaleString('id-ID')} T</span><span class="text-[10px] text-slate-500">${pct}%</span></div></div>`;
            }).join('');
        }

        charts.nasJamsosArea = new Chart(document.getElementById('chartNasJamsosArea'), {
            type: 'line',
            data: {
                labels: d.pertumbuhan.labels,
                datasets: [
                    {
                        label: 'Konvensional (Triliun)',
                        data: d.pertumbuhan.konvensional,
                        borderColor: C.slate,
                        backgroundColor: hexRgba(C.slate, 0.4),
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Syariah (Triliun)',
                        data: d.pertumbuhan.syariah,
                        borderColor: C.cyan,
                        backgroundColor: hexRgba(C.cyan, 0.4),
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: legendCfg(),
                    tooltip: tooltipCfg({ mode: 'index', intersect: false })
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: { color: GRID } }
                }
            }
        });
    }

    function createPembiayaanBPRSCharts() {
        const d = N_DATA.pembiayaan_bprs;

        // Cards
        document.getElementById('bprsLpdbProv').textContent = d.lpdb.prov;
        document.getElementById('bprsLpdbMitra').textContent = d.lpdb.mitra;
        document.getElementById('bprsLpdbDana').textContent = d.lpdb.dana;

        document.getElementById('bprsPipProv').textContent = d.pip.prov;
        document.getElementById('bprsPipMitra').textContent = d.pip.mitra;
        document.getElementById('bprsPipDana').textContent = d.pip.dana.toFixed(2);

        charts.nasBprsUmkm = new Chart(document.getElementById('chartNasBprsUmkm'), {
            type: 'bar',
            data: {
                labels: d.bprs.labels,
                datasets: [{
                    label: 'Total Pembiayaan (Rp)',
                    data: d.bprs.data,
                    backgroundColor: C.emerald,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID } }
                }
            }
        });
    }

    function createPayrollASNCharts() {
        const d = N_DATA.payroll_asn;
        charts.nasPayrollBar = new Chart(document.getElementById('chartNasPayrollBar'), {
            type: 'bar',
            data: {
                labels: d.bar.labels,
                datasets: [
                    { label: 'Nominal (Rp Miliar)', data: d.bar.nominal, backgroundColor: C.emerald, borderRadius: 4, yAxisID: 'y' },
                    { type: 'line', label: 'Persentase (%)', data: d.bar.persentase, borderColor: C.amber, tension: 0.4, yAxisID: 'y1', pointRadius: 4 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { position: 'left', grid: { color: GRID } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => v + '%' } }
                }
            }
        });
        charts.nasPayrollTrend = new Chart(document.getElementById('chartNasPayrollTrend'), {
            type: 'line',
            data: { labels: d.trend.labels, datasets: [{ label: 'Trend Payroll', ...makeLine(d.trend.data, C.blue) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: lineScale('') }
        });
        charts.nasPayrollPie = new Chart(document.getElementById('chartNasPayrollPie'), {
            type: 'doughnut',
            data: { labels: d.pie.labels, datasets: [{ data: d.pie.data, backgroundColor: [C.emerald, C.slate], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: barLegendCfg(), tooltip: tooltipCfg() } }
        });
        renderPayrollTable();
    }

    function renderPayrollTable() {
        const d = N_DATA.payroll_asn.provinsi;
        const start = (state.payrollPage - 1) * 8;
        const end = start + 8;
        const tbody = document.getElementById('tableNasPayroll');
        if (!tbody) return;
        tbody.innerHTML = d.slice(start, end).map(it => `<tr><td class="px-4 py-3 text-xs font-medium text-slate-800">${it[0]}</td><td class="px-4 py-3 text-xs font-bold text-slate-600 text-right">${(it[1]/1e9).toFixed(2)} M</td><td class="px-4 py-3 text-xs font-bold text-emerald-600 text-right">${it[2]}%</td></tr>`).join('');
        const range = document.getElementById('tableNasPayrollRange');
        if (range) range.textContent = `${start + 1} - ${Math.min(end, d.length)} dari ${d.length}`;
        const btnPrev = document.getElementById('btnPrevPayroll');
        const btnNext = document.getElementById('btnNextPayroll');
        if (btnPrev) btnPrev.disabled = state.payrollPage === 1;
        if (btnNext) btnNext.disabled = end >= d.length;
    }

    function initPayrollMap() {
        if (!window.L) return;
        const d = N_DATA.payroll_asn;
        if (charts.nasPayrollMap) charts.nasPayrollMap.remove();
        charts.nasPayrollMap = L.map('mapNasPayroll').setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(charts.nasPayrollMap);
        d.provinsi.forEach(it => {
            if (it[2] > 0) {
                const r = Math.max(5, Math.min(15, 3 + it[2]/10));
                L.circleMarker([it[3], it[4]], { radius: r, fillColor: C.emerald, color: '#fff', weight: 1, fillOpacity: 0.7 })
                 .bindPopup(`<strong>${it[0]}</strong><br/>Nominal: Rp ${(it[1]/1e9).toFixed(2)} M<br/>Share: ${it[2]}%`)
                 .addTo(charts.nasPayrollMap);
            }
        });
    }

    function createKpbuSyariahCharts() {
        const d = N_DATA.kpbu_syariah;

        // Cards
        document.getElementById('kpbuTotalPembiayaan').textContent = d.total_pembiayaan;
        document.getElementById('kpbuTotalProyek').textContent = d.total_proyek;

        charts.nasKpbuBar = new Chart(document.getElementById('chartNasKpbuBar'), {
            type: 'bar',
            data: {
                labels: d.proyek.labels,
                datasets: [{
                    label: 'Nilai Pembiayaan Syariah (Rp Miliar)',
                    data: d.proyek.data,
                    backgroundColor: C.amber,
                    borderRadius: 4
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { type: 'linear', display: true, position: 'bottom', grid: { color: GRID } },
                    y: { grid: { display: false } }
                }
            }
        });

        renderKpbuTable();
    }

    function renderKpbuTable() {
        const d = N_DATA.kpbu_syariah.proyek;
        const start = (state.kpbuPage - 1) * 8;
        const end = start + 8;
        const tbody = document.getElementById('tableNasKpbu');
        if (!tbody) return;

        const allItems = d.labels.map((lbl, i) => ({
            nama: lbl,
            nilai: d.data[i],
            tahun: d.tahun[i]
        }));
        
        const items = allItems.slice(start, end);
        tbody.innerHTML = items.map(it => `
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-4 py-3 text-xs font-medium text-slate-800">${it.nama}</td>
                <td class="px-4 py-3 text-xs font-bold text-slate-500 text-center">${it.tahun}</td>
                <td class="px-4 py-3 text-xs font-bold text-amber-600 text-right">${it.nilai.toLocaleString('id-ID')}</td>
            </tr>
        `).join('');

        const range = document.getElementById('tableNasKpbuRange');
        if (range) range.textContent = `${start + 1} - ${Math.min(end, allItems.length)} dari ${allItems.length}`;

        const btnPrev = document.getElementById('btnPrevKpbu');
        const btnNext = document.getElementById('btnNextKpbu');
        if (btnPrev) btnPrev.disabled = state.kpbuPage === 1;
        if (btnNext) btnNext.disabled = end >= allItems.length;
    }

    function createPasarModalCharts() {
        const d = N_DATA.pasar_modal;

        // Cards
        document.getElementById('pasarModalTotalAset').textContent = fmt(d.total_aset);

        // Pie
        charts.nasPasarModalPie = new Chart(document.getElementById('chartNasPasarModalPie'), {
            type: 'doughnut',
            data: { labels: d.market_share.labels, datasets: [{ data: d.market_share.data, backgroundColor: [C.slate, C.blue], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false }, tooltip: tooltipCfg() } },
            plugins: [{
                id: 'centerTotal',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = '#64748b';
                    ctx.font = 'bold 10px Inter';
                    ctx.fillText('Total', chart.width / 2, chart.height / 2 - 10);
                    ctx.fillStyle = '#0f172a';
                    ctx.font = '900 16px Inter';
                    const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                    ctx.fillText(`${total}%`, chart.width / 2, chart.height / 2 + 8);
                    ctx.restore();
                }
            }]
        });

        const lgPie = document.getElementById('pasarModalPieLegend');
        if (lgPie) {
            lgPie.innerHTML = d.market_share.labels.map((l, i) => {
                const val = d.market_share.data[i];
                return `<div class="flex items-center gap-2"><span class="h-3 w-3 rounded-md" style="background-color:${[C.slate, C.blue][i]}"></span><span class="text-xs font-bold text-slate-700">${l} (${val}%)</span></div>`;
            }).join('');
        }

        renderPasarModalTable();

        const renderSubTableFixed = (id, labels, v1, y1, v2, y2) => {
            const tbody = document.getElementById(id);
            if (!tbody) return;
            tbody.innerHTML = labels.map((lbl, i) => `
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-2 text-[11px] font-medium text-slate-800 text-center">${lbl}</td>
                    <td class="px-4 py-2 text-[11px] font-bold text-slate-600 text-right">
                        ${v1[i] !== null ? v1[i].toLocaleString('id-ID') : '-'}
                        ${y1 && y1[i] !== null ? `<span class="block text-[9px] ${y1[i] > 0 ? 'text-emerald-500' : 'text-rose-500'}">${y1[i] > 0 ? '+' : ''}${y1[i]}% YoY</span>` : ''}
                    </td>
                    <td class="px-4 py-2 text-[11px] font-bold text-blue-600 text-right">
                        ${v2[i] !== null ? v2[i].toLocaleString('id-ID') : '-'}
                        ${y2 && y2[i] !== null ? `<span class="block text-[9px] ${y2[i] > 0 ? 'text-emerald-500' : 'text-rose-500'}">${y2[i] > 0 ? '+' : ''}${y2[i]}% YoY</span>` : ''}
                    </td>
                </tr>
            `).join('');
        };
        
        renderSubTableFixed('tableNasSaham', d.saham.labels, d.saham.jumlah, d.saham.yoy_jumlah, d.saham.kapitalisasi, d.saham.yoy_kapitalisasi);
        renderSubTableFixed('tableNasReksadana', d.reksadana.labels, d.reksadana.jumlah, d.reksadana.yoy_jumlah, d.reksadana.nab, d.reksadana.yoy_nab);
        renderSubTableFixed('tableNasSukukKorporasi', d.sukuk_korporasi.labels, d.sukuk_korporasi.jumlah, d.sukuk_korporasi.yoy_jumlah, d.sukuk_korporasi.nilai, d.sukuk_korporasi.yoy_nilai);
        renderSubTableFixed('tableNasSukukNegara', d.sukuk_negara.labels, d.sukuk_negara.jumlah, d.sukuk_negara.yoy_jumlah, d.sukuk_negara.nilai, d.sukuk_negara.yoy_nilai);
    }

    function renderPasarModalTable() {
        const d = N_DATA.pasar_modal.tabel_perbankan;
        const start = (state.pasarModalPage - 1) * 6;
        const end = start + 6;
        const tbody = document.getElementById('tableNasPasarModal');
        if (!tbody) return;

        const allItems = d.labels.map((lbl, i) => ({
            periode: lbl,
            aset: d.aset[i],
            share: d.share[i]
        }));
        
        const items = allItems.slice(start, end);
        tbody.innerHTML = items.map(it => `
            <tr class="hover:bg-slate-50/50 transition-colors">
                <td class="px-4 py-3 text-xs font-medium text-slate-800">${it.periode}</td>
                <td class="px-4 py-3 text-xs font-bold text-slate-600 text-right">${it.aset.toLocaleString('id-ID')}</td>
                <td class="px-4 py-3 text-xs font-bold text-blue-600 text-right">${it.share}%</td>
            </tr>
        `).join('');

        const range = document.getElementById('tableNasPasarModalRange');
        if (range) range.textContent = `${start + 1} - ${Math.min(end, allItems.length)} dari ${allItems.length}`;

        const btnPrev = document.getElementById('btnPrevPasarModal');
        const btnNext = document.getElementById('btnNextPasarModal');
        if (btnPrev) btnPrev.disabled = state.pasarModalPage === 1;
        if (btnNext) btnNext.disabled = end >= allItems.length;
    }

    function createTrenPasarModalCharts() {
        const d = N_DATA.tren_pasar_modal;

        charts.nasSukukNegaraCombo = new Chart(document.getElementById('chartNasSukukNegaraCombo'), {
            type: 'bar',
            data: {
                labels: d.sukuk_negara_combo.labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Nilai Outstanding (Triliun Rp)',
                        data: d.sukuk_negara_combo.nilai,
                        borderColor: C.indigo,
                        backgroundColor: hexRgba(C.indigo, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointRadius: 3,
                        order: 1
                    },
                    {
                        label: 'Jumlah Seri Outstanding',
                        data: d.sukuk_negara_combo.jumlah,
                        backgroundColor: C.rose,
                        borderRadius: 4,
                        yAxisID: 'y1',
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false } }
                }
            }
        });

        charts.nasEfek1 = new Chart(document.getElementById('chartNasEfek1'), {
            type: 'line',
            data: { labels: d.efek_1.labels, datasets: [{ label: 'Jumlah Efek', ...makeLine(d.efek_1.data, C.emerald) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('') }
        });

        charts.nasEfek2 = new Chart(document.getElementById('chartNasEfek2'), {
            type: 'line',
            data: { labels: d.efek_2.labels, datasets: [{ label: 'Jumlah Efek', ...makeLine(d.efek_2.data, C.blue) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('') }
        });

        charts.nasKapitalisasiSaham = new Chart(document.getElementById('chartNasKapitalisasiSaham'), {
            type: 'line',
            data: { labels: d.kapitalisasi.labels, datasets: [{ label: 'Kapitalisasi Saham (Triliun)', ...makeLine(d.kapitalisasi.data, C.amber) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('') }
        });

        charts.nasSukukKorporasiCombo = new Chart(document.getElementById('chartNasSukukKorporasiCombo'), {
            type: 'bar',
            data: {
                labels: d.sukuk_korporasi_combo.labels,
                datasets: [
                    {
                        label: 'Nilai Outstanding (Triliun Rp)',
                        data: d.sukuk_korporasi_combo.nilai_outstanding,
                        backgroundColor: C.indigo,
                        borderRadius: 4,
                        yAxisID: 'y',
                        order: 4
                    },
                    {
                        label: 'Nilai Akumulasi (Triliun Rp)',
                        data: d.sukuk_korporasi_combo.nilai_akumulasi,
                        backgroundColor: C.blue,
                        borderRadius: 4,
                        yAxisID: 'y',
                        order: 3
                    },
                    {
                        type: 'line',
                        label: 'Jumlah Sukuk Outstanding',
                        data: d.sukuk_korporasi_combo.jumlah_outstanding,
                        borderColor: C.rose,
                        backgroundColor: hexRgba(C.rose, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointRadius: 3,
                        spanGaps: true,
                        order: 2
                    },
                    {
                        type: 'line',
                        label: 'Jumlah Sukuk Akumulasi',
                        data: d.sukuk_korporasi_combo.jumlah_akumulasi,
                        borderColor: C.emerald,
                        backgroundColor: hexRgba(C.emerald, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y1',
                        pointRadius: 3,
                        spanGaps: true,
                        order: 1
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false } }
                }
            }
        });
    }

    function createInovasiKeuanganCharts() {
        const d = N_DATA.inovasi_keuangan;

        // Scorecards
        const pesertaEl = document.getElementById('taperaPesertaCount');
        const investasiEl = document.getElementById('taperaInvestasiCount');
        if (pesertaEl) pesertaEl.textContent = fmt(d.tapera_combo.peserta[d.tapera_combo.peserta.length - 1]);
        if (investasiEl) investasiEl.textContent = d.tapera_combo.investasi[d.tapera_combo.investasi.length - 1].toFixed(1);

        charts.nasTaperaCombo = new Chart(document.getElementById('chartNasTaperaCombo'), {
            type: 'bar',
            data: {
                labels: d.tapera_combo.labels,
                datasets: [
                    {
                        type: 'line',
                        label: 'Peserta',
                        data: d.tapera_combo.peserta,
                        borderColor: C.violet,
                        backgroundColor: hexRgba(C.violet, 0.1),
                        borderWidth: 2,
                        tension: 0.4,
                        yAxisID: 'y',
                        pointRadius: 2,
                        order: 1
                    },
                    {
                        label: 'Investasi (Rp Miliar)',
                        data: d.tapera_combo.investasi,
                        backgroundColor: C.amber,
                        borderRadius: 4,
                        yAxisID: 'y1',
                        order: 2
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false } }
                }
            }
        });

        charts.nasTaperaMarketshare = new Chart(document.getElementById('chartNasTaperaMarketshare'), {
            type: 'line',
            data: { labels: d.tapera_marketshare.labels, datasets: [{ label: 'Marketshare (%)', ...makeLine(d.tapera_marketshare.data, C.emerald) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('') }
        });
    }

    function createAsuransiSyariahCharts() {
        const d = N_DATA.asuransi_syariah;
        charts.nasAsuransiSyariah = new Chart(document.getElementById('chartNasAsuransiSyariah'), {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'Asuransi Jiwa', ...makeLine(d.jiwa, C.emerald) },
                    { label: 'Asuransi Umum', ...makeLine(d.umum, C.blue) },
                    { label: 'Reasuransi', ...makeLine(d.reasuransi, C.amber) },
                    { label: 'Total', ...makeLine(d.total, C.rose) }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('') }
        });
    }

    function createDapenSyariahCharts() {
        const d = N_DATA.dapen_syariah;
        charts.nasDapenSyariah = new Chart(document.getElementById('chartNasDapenSyariah'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'DPPK-PPMP', data: d.dppk_ppmp, backgroundColor: C.emerald, borderRadius: 4 },
                    { label: 'DPPK-PPIP', data: d.dppk_ppip, backgroundColor: C.blue, borderRadius: 4 },
                    { label: 'DPLK', data: d.dplk, backgroundColor: C.amber, borderRadius: 4 },
                    { label: 'Jamsosnaker', data: d.jamsosnaker, backgroundColor: C.rose, borderRadius: 4 },
                    { label: 'PIS-DPLK', data: d.pis_dplk, backgroundColor: C.violet, borderRadius: 4 }
                ]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, 
                scales: { 
                    x: { stacked: true, grid: { display: false } }, 
                    y: { stacked: true, type: 'linear', display: true, position: 'left', grid: { color: GRID } } 
                } 
            },
            plugins: [{
                id: 'stackedLabels',
                afterDatasetsDraw(chart) {
                    const {ctx} = chart;
                    ctx.save();
                    ctx.font = 'bold 10px Inter';
                    ctx.fillStyle = '#64748b';
                    ctx.textAlign = 'center';
                    const meta = chart.getDatasetMeta(chart.data.datasets.length - 1);
                    meta.data.forEach((bar, i) => {
                        let total = 0;
                        chart.data.datasets.forEach(ds => { total += ds.data[i] || 0; });
                        if (total > 0) ctx.fillText(total.toFixed(2), bar.x, bar.y - 8);
                    });
                    ctx.restore();
                }
            }]
        });
    }

    function createSektorEkonomiCharts() {
        const d = N_DATA.sektor_ekonomi;
        charts.nasSektorPembiayaan = new Chart(document.getElementById('chartNasSektorPembiayaan'), {
            type: 'bar',
            data: {
                labels: d.pembiayaan.labels,
                datasets: [{ label: 'Nilai Pembiayaan', data: d.pembiayaan.data, backgroundColor: C.emerald, borderRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });
        charts.nasSektorPertumbuhan = new Chart(document.getElementById('chartNasSektorPertumbuhan'), {
            type: 'bar',
            data: {
                labels: d.pertumbuhan.labels,
                datasets: [{ label: 'YoY Growth (%)', data: d.pertumbuhan.data, backgroundColor: C.blue, borderRadius: 4 }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });
    }

    function createIKNBSyariahCharts() {
        const d = N_DATA.iknb_syariah;
        charts.nasIknbMarketShare = new Chart(document.getElementById('chartNasIknbMarketShare'), {
            type: 'doughnut',
            data: {
                labels: d.market_share.labels,
                datasets: [{ data: d.market_share.data, backgroundColor: [C.slate, C.emerald], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { 
                    legend: legendCfg('bottom'), 
                    tooltip: {
                        ...tooltipCfg(),
                        callbacks: {
                            label: (c) => ` ${c.label}: ${c.formattedValue} (${((c.raw / d.market_share.data.reduce((a,b)=>a+b,0)) * 100).toFixed(2)}%)`
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerTextIknb',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const {left, right, top, bottom} = chart.chartArea;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;
                    ctx.fillStyle = '#10b981';
                    ctx.font = '900 24px Inter';
                    ctx.fillText('6.3%', centerX, centerY - 5);
                    ctx.fillStyle = '#64748b';
                    ctx.font = 'bold 12px Inter';
                    ctx.fillText('Total Valuasi', centerX, centerY + 20);
                    ctx.restore();
                }
            }]
        });
    }

    function createKinerjaPerbankanCharts() {
        const d = N_DATA.kinerja_perbankan;
        charts.nasPerbankanMarketShareSektor = new Chart(document.getElementById('chartNasPerbankanMarketShareSektor'), {
            type: 'doughnut',
            data: {
                labels: d.market_share_sektor.labels,
                datasets: [{ data: d.market_share_sektor.data, backgroundColor: [C.slate, C.emerald], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { 
                    legend: legendCfg('bottom'), 
                    tooltip: {
                        ...tooltipCfg(),
                        callbacks: {
                            label: (c) => ` ${c.label}: ${c.formattedValue} (${((c.raw / d.market_share_sektor.data.reduce((a,b)=>a+b,0)) * 100).toFixed(2)}%)`
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerTextSektor',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const {left, right, top, bottom} = chart.chartArea;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;
                    ctx.fillStyle = '#10b981';
                    ctx.font = '900 28px Inter';
                    ctx.fillText('12.290', centerX, centerY - 5);
                    ctx.fillStyle = '#64748b';
                    ctx.font = 'bold 13px Inter';
                    ctx.fillText('Total Aset', centerX, centerY + 25);
                    ctx.restore();
                }
            }]
        });
        charts.nasPerbankanMarketShareLembaga = new Chart(document.getElementById('chartNasPerbankanMarketShareLembaga'), {
            type: 'doughnut',
            data: {
                labels: d.market_share_lembaga.labels,
                datasets: [{ data: d.market_share_lembaga.data, backgroundColor: [C.emerald, C.blue, C.amber], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { 
                    legend: legendCfg('bottom'), 
                    tooltip: {
                        ...tooltipCfg(),
                        callbacks: {
                            label: (c) => ` ${c.label}: ${c.formattedValue} (${((c.raw / d.market_share_lembaga.data.reduce((a,b)=>a+b,0)) * 100).toFixed(2)}%)`
                        }
                    }
                }
            },
            plugins: [{
                id: 'centerTextLembaga',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx;
                    ctx.save();
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    const {left, right, top, bottom} = chart.chartArea;
                    const centerX = (left + right) / 2;
                    const centerY = (top + bottom) / 2;
                    ctx.fillStyle = '#0f172a';
                    ctx.font = '900 24px Inter';
                    ctx.fillText('1,067', centerX, centerY - 5);
                    ctx.fillStyle = '#64748b';
                    ctx.font = 'bold 12px Inter';
                    ctx.fillText('Aset Syariah', centerX, centerY + 20);
                    ctx.restore();
                }
            }]
        });
        charts.nasPerbankanTrendGrowth = new Chart(document.getElementById('chartNasPerbankanTrendGrowth'), {
            type: 'line',
            data: {
                labels: d.trend_growth.labels,
                datasets: [
                    { label: 'DPK', ...makeLine(d.trend_growth.dpk, C.emerald) },
                    { label: 'PYD', ...makeLine(d.trend_growth.pyd, C.blue) },
                    { label: 'Aset', ...makeLine(d.trend_growth.aset, C.rose) }
                ]
            },
            options: { 
                responsive: true, maintainAspectRatio: false, 
                plugins: { 
                    legend: legendCfg(), 
                    tooltip: {
                        ...tooltipCfg(),
                        callbacks: { label: (c) => ` ${c.dataset.label}: ${c.raw.toFixed(2)}%` }
                    }
                }, 
                scales: lineScale('%') 
            }
        });
    }

    function createPerkembanganAsetKeuanganCharts() {
        const d = N_DATA.aset_keuangan_syariah;
        charts.nasPerkembanganAsetCombo = new Chart(document.getElementById('chartNasPerkembanganAsetCombo'), {
            type: 'bar',
            data: {
                labels: d.combo.labels,
                datasets: [
                    { label: 'Pasar Modal Syariah', data: d.combo.pasar_modal, backgroundColor: C.blue, borderRadius: 4 },
                    { label: 'IKNB Syariah', data: d.combo.iknb, backgroundColor: C.amber, borderRadius: 4 },
                    { label: 'Perbankan Syariah', data: d.combo.perbankan, backgroundColor: C.emerald, borderRadius: 4 },
                    { type: 'line', label: 'Growth YoY (%)', data: d.combo.growth, borderColor: C.rose, tension: 0.4, yAxisID: 'y1', pointRadius: 4 }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { stacked: false, grid: { display: false } },
                    y: { stacked: false, grid: { color: GRID }, title: { display: true, text: 'Triliun Rp' } },
                    y1: { position: 'right', grid: { display: false }, ticks: { callback: v => v + '%' }, title: { display: true, text: 'Growth (%)' } }
                }
            }
        });

        charts.nasPerkembanganAsetMarketShare = new Chart(document.getElementById('chartNasPerkembanganAsetMarketShare'), {
            type: 'doughnut',
            data: { labels: d.market_share.labels, datasets: [{ data: d.market_share.data, backgroundColor: [C.slate, C.emerald], borderWidth: 0 }] },
            options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: barLegendCfg(), tooltip: tooltipCfg() } }
        });

        const tableBody = document.getElementById('tableNasPerkembanganAsetBody');
        if (tableBody) {
            tableBody.innerHTML = d.combo.labels.map((lbl, i) => `
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 text-xs font-bold text-slate-700">${lbl}</td>
                    <td class="px-4 py-3 text-xs font-medium text-slate-600 text-right">${fmt(d.combo.perbankan[i])}</td>
                    <td class="px-4 py-3 text-xs font-medium text-slate-600 text-right">${fmt(d.combo.iknb[i])}</td>
                    <td class="px-4 py-3 text-xs font-medium text-slate-600 text-right">${fmt(d.combo.pasar_modal[i])}</td>
                    <td class="px-4 py-3 text-xs font-bold text-rose-600 text-right">${d.combo.growth[i].toFixed(2)}%</td>
                </tr>
            `).reverse().join('');
        }

        const breakdownBody = document.getElementById('tableNasTotalAsetBreakdownBody');
        if (breakdownBody) {
            breakdownBody.innerHTML = d.total_aset_breakdown.map(it => `
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 text-[11px] font-bold text-slate-700">${it[0]}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-slate-600 text-right">${fmt(it[1])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-slate-600 text-right">${fmt(it[2])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-slate-600 text-right">${fmt(it[3])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-blue-600 text-right">${fmt(it[4])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-amber-600 text-right">${fmt(it[5])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-amber-600 text-right">${fmt(it[6])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-amber-600 text-right">${fmt(it[7])}</td>
                    <td class="px-4 py-3 text-[11px] font-medium text-amber-600 text-right">${fmt(it[8])}</td>
                </tr>
            `).reverse().join('');
        }

        const iknbBody = document.getElementById('tableNasIknbBreakdownBody');
        if (iknbBody) {
            iknbBody.innerHTML = d.iknb_breakdown.map(it => `
                <tr class="border-b border-slate-100 hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 text-xs font-bold text-slate-700">${it[0]}</td>
                    <td class="px-4 py-3 text-xs font-medium text-blue-600 text-right">${fmt(it[1])}</td>
                    <td class="px-4 py-3 text-xs font-medium text-blue-600 text-right">${fmt(it[2])}</td>
                    <td class="px-4 py-3 text-xs font-medium text-blue-600 text-right">${fmt(it[3])}</td>
                    <td class="px-4 py-3 text-xs font-bold text-rose-600 text-right">${it[4]}%</td>
                    <td class="px-4 py-3 text-xs font-black text-slate-900 text-right">${fmt(it[5])}</td>
                </tr>
            `).reverse().join('');
        }
    }

    function createSyariahDaerahCharts() {
        const d = N_DATA.syariah_daerah.rasio_pdrb;
        charts.nasSyariahDaerahPdrb = new Chart(document.getElementById('chartNasDaerahRasioPdrb'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{ label: 'Rasio (%)', data: d.data, backgroundColor: C.emerald, borderRadius: 4 }]
            },
            options: {
                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: { x: { grid: { color: GRID }, ticks: { callback: v => v + '%' } }, y: { grid: { display: false }, ticks: { font: { size: 9 } } } }
            }
        });
    }

    function createKinerjaBusUusCharts() {
        const d = N_DATA.kinerja_bus_uus;
        const barCfg = (label, data, color) => ({
            type: 'bar',
            data: { labels: d.labels, datasets: [{ label, data, backgroundColor: color, borderRadius: 4 }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });
        const lineCfg = (label, data, color, isPercent=true) => ({
            type: 'line',
            data: { labels: d.labels, datasets: [{ label, ...makeLine(data, color) }] },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: lineScale(isPercent ? '%' : '') }
        });

        charts.nasBusAset = new Chart(document.getElementById('chartNasBusAset'), barCfg('Aset', d.aset.nominal.map(v => v/1000), C.emerald));
        charts.nasBusDpk = new Chart(document.getElementById('chartNasBusDpk'), barCfg('DPK', d.dpk.nominal.map(v => v/1000), C.blue));
        charts.nasBusPyd = new Chart(document.getElementById('chartNasBusPyd'), barCfg('PYD', d.pyd.nominal.map(v => v/1000), C.amber));
        
        charts.nasBusNpf = new Chart(document.getElementById('chartNasBusNpf'), {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'Gross', ...makeLine(d.npf.gross.map(v => v*100), C.rose) },
                    { label: 'Net', ...makeLine(d.npf.net.map(v => v*100), C.orange) }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: lineScale('%') }
        });

        charts.nasBusCar = new Chart(document.getElementById('chartNasBusCar'), lineCfg('CAR', d.car.map(v => v*100), C.violet));
        charts.nasBusFdr = new Chart(document.getElementById('chartNasBusFdr'), lineCfg('FDR', d.fdr.map(v => v*100), C.cyan));
        charts.nasBusBopo = new Chart(document.getElementById('chartNasBusBopo'), lineCfg('BOPO', d.bopo.map(v => v*100), C.rose));
        charts.nasBusRoa = new Chart(document.getElementById('chartNasBusRoa'), lineCfg('ROA', d.roa.map(v => v*100), C.teal));
        charts.nasBusNom = new Chart(document.getElementById('chartNasBusNom'), lineCfg('NOM', d.nom.map(v => v*100), C.indigo));
    }

    function createZisPdbCharts() {
        const d = N_DATA.zis_pdb;
        charts.nasZisPdbCombo = new Chart(document.getElementById('chartNasZisPdbCombo'), {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'ZIS & DSKL (Triliun Rp)', ...makeLine(d.zis_triliun, C.emerald), yAxisID: 'y' },
                    { 
                        label: 'PDB Nasional (Triliun Rp)', ...makeLine(d.pdb_triliun, C.slate, false, true), 
                        yAxisID: 'y', pointRadius: 4, pointHoverRadius: 6 
                    },
                    { 
                        type: 'line', label: 'Rasio ZIS/PDB (%)', data: d.rasio_persen, 
                        borderColor: C.amber, backgroundColor: hexRgba(C.amber, 0.1), 
                        borderWidth: 2, tension: 0.4, yAxisID: 'y1', pointRadius: 4, pointBackgroundColor: '#fff' 
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID }, title: { display: true, text: 'IDR Triliun', font: { size: 10 } } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false }, ticks: { callback: v => v + '%' }, title: { display: true, text: 'Rasio (%)', font: { size: 10 } } }
                }
            }
        });
    }

    function createWakafNasionalCharts() {
        const d = N_DATA.transformasi_wakaf;

        // Lembaga Pie
        charts.nasWakafLembaga = new Chart(document.getElementById('chartNasWakafLembaga'), {
            type: 'doughnut',
            data: {
                labels: d.lembaga_syariah.labels,
                datasets: [{ data: d.lembaga_syariah.data, backgroundColor: [C.emerald, C.blue, C.amber], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: legendCfg('bottom'), tooltip: tooltipCfg() }
            },
            plugins: [{
                id: 'centerTextWakafLembaga',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx; ctx.save();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    const centerX = chart.width / 2; const centerY = chart.height / 2;
                    ctx.fillStyle = '#0f172a'; ctx.font = '900 24px Inter';
                    ctx.fillText(d.lkspwu, centerX, centerY - 5);
                    ctx.fillStyle = '#64748b'; ctx.font = 'bold 12px Inter';
                    ctx.fillText('Total LKSPWU', centerX, centerY + 15);
                    ctx.restore();
                }
            }]
        });

        // CWLD Horizontal
        charts.nasWakafCwld = new Chart(document.getElementById('chartNasWakafCwld'), {
            type: 'bar',
            data: {
                labels: d.cwld.banks.labels,
                datasets: [{ label: 'Total CWLD (Rp)', data: d.cwld.banks.data, backgroundColor: C.emerald, borderRadius: 4 }]
            },
            options: {
                indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: { x: { grid: { color: GRID }, ticks: { callback: v => (v / 1e9).toFixed(1) + ' M' } }, y: { grid: { display: false } } }
            }
        });

        // Penggunaan Tanah Pie
        charts.nasWakafPenggunaan = new Chart(document.getElementById('chartNasWakafPenggunaan'), {
            type: 'doughnut',
            data: {
                labels: d.sertifikasi.penggunaan.labels,
                datasets: [{ data: d.sertifikasi.penggunaan.data, backgroundColor: [C.emerald, C.blue, C.amber, C.violet, C.rose, C.slate], borderWidth: 0 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutout: '70%',
                plugins: { legend: legendCfg('bottom'), tooltip: tooltipCfg() }
            },
            plugins: [{
                id: 'centerTextWakafPenggunaan',
                beforeDraw: (chart) => {
                    const ctx = chart.ctx; ctx.save();
                    ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                    const centerX = chart.width / 2; const centerY = chart.height / 2;
                    ctx.fillStyle = '#0f172a'; ctx.font = '900 24px Inter';
                    ctx.fillText('440.512', centerX, centerY - 5);
                    ctx.fillStyle = '#64748b'; ctx.font = 'bold 12px Inter';
                    ctx.fillText('Total Lokasi', centerX, centerY + 15);
                    ctx.restore();
                }
            }]
        });

        initWakafMap();
    }

    function createWakafUangPdbCharts() {
        const d = N_DATA.wakaf_uang_pdb;
        charts.nasWakafUangPdbCombo = new Chart(document.getElementById('chartNasWakafUangPdbCombo'), {
            type: 'line',
            data: {
                labels: d.labels,
                datasets: [
                    { label: 'Wakaf Uang (Triliun Rp)', ...makeLine(d.wakaf_uang_triliun, C.emerald), yAxisID: 'y' },
                    { label: 'PDB Nasional (Triliun Rp)', ...makeLine(d.pdb_triliun, C.slate, false, true), yAxisID: 'y' },
                    { 
                        type: 'line', label: 'Rasio (%)', data: d.rasio_persen.map(v => v * 100), 
                        borderColor: C.rose, backgroundColor: hexRgba(C.rose, 0.1), 
                        borderWidth: 2, tension: 0.4, yAxisID: 'y1', pointRadius: 4 
                    }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: {
                    x: { grid: { display: false } },
                    y: { type: 'linear', display: true, position: 'left', grid: { color: GRID }, title: { display: true, text: 'IDR Triliun', font: { size: 10 } } },
                    y1: { type: 'linear', display: true, position: 'right', grid: { display: false }, ticks: { callback: v => v.toFixed(3) + '%' }, title: { display: true, text: 'Rasio (%)', font: { size: 10 } } }
                }
            }
        });
    }

    function createPendanaanUmkmSosialCharts() {
        const d = N_DATA.pendanaan_umkm_sosial;
        charts.nasPendanaanUmkmSosial = new Chart(document.getElementById('chartNasPendanaanUmkmSosial'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [{ label: 'Nilai (Miliar Rp)', data: d.data, backgroundColor: C.emerald, borderRadius: 4 }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } }
            }
        });
    }

    function createZisNasionalCharts() {
        const d = N_DATA.zis_nasional;
        const donut = (id, label, labels, data, amount, unit='') => {
            const total = data.reduce((a,b)=>a+b,0);
            return new Chart(document.getElementById(id), {
                type: 'doughnut',
                data: { labels, datasets: [{ data, backgroundColor: [C.emerald, C.blue, C.amber], borderWidth: 0 }] },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '70%',
                    plugins: { 
                        legend: barLegendCfg(), 
                        tooltip: { 
                            ...tooltipCfg(), 
                            callbacks: { label: (c) => ` ${c.label}: ${fmt(c.raw)} ${unit} (${((c.raw/total)*100).toFixed(1)}%)` }
                        }
                    }
                },
                plugins: [{
                    id: 'centerText',
                    beforeDraw: (chart) => {
                        const {left, right, top, bottom} = chart.chartArea;
                        const ctx = chart.ctx;
                        ctx.save(); ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
                        ctx.fillStyle = '#0f172a'; ctx.font = '900 18px Inter';
                        ctx.fillText(fmt(amount) + (unit?' '+unit:''), (left+right)/2, (top+bottom)/2 - 5);
                        ctx.fillStyle = '#64748b'; ctx.font = 'bold 10px Inter';
                        ctx.fillText(label, (left+right)/2, (top+bottom)/2 + 15);
                        ctx.restore();
                    }
                }]
            });
        };

        charts.nasZisDonutPengumpulan = donut('chartNasZisDonutPengumpulan', 'Pengumpulan', d.pengumpulan_wilayah.labels, d.pengumpulan_wilayah.data, d.stats.pengumpulan, 'T');
        charts.nasZisDonutPenyaluran  = donut('chartNasZisDonutPenyaluran', 'Penyaluran', d.penyaluran_wilayah.labels, d.penyaluran_wilayah.data, d.stats.penyaluran, 'T');
        charts.nasZisDonutOperasional = donut('chartNasZisDonutOperasional', 'Operasional', d.operasional_wilayah.labels, d.operasional_wilayah.data, d.stats.operasional, 'T');
        charts.nasZisDonutMustahik    = donut('chartNasZisDonutMustahik', 'Mustahik', d.mustahik_wilayah.labels, d.mustahik_wilayah.data, d.stats.mustahik, 'Jiwa');
        charts.nasZisDonutMuzaki      = donut('chartNasZisDonutMuzaki', 'Muzaki', d.muzaki_wilayah.labels, d.muzaki_wilayah.data, d.stats.muzaki, 'Jiwa');

        charts.nasZisBarWilayah = new Chart(document.getElementById('chartNasZisBarWilayah'), {
            type: 'bar',
            data: {
                labels: d.bar_sosial.labels,
                datasets: [
                    { label: 'Pengumpulan', data: d.bar_sosial.pengumpulan, backgroundColor: C.emerald, borderRadius: 4 },
                    { label: 'Penyaluran', data: d.bar_sosial.penyaluran, backgroundColor: C.blue, borderRadius: 4 },
                    { label: 'Operasional', data: d.bar_sosial.operasional, backgroundColor: C.amber, borderRadius: 4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });

        charts.nasZisBarOrang = new Chart(document.getElementById('chartNasZisBarOrang'), {
            type: 'bar',
            data: {
                labels: d.bar_orang.labels,
                datasets: [
                    { label: 'Muzaki (Juta)', data: d.bar_orang.muzaki, backgroundColor: C.indigo, borderRadius: 4 },
                    { label: 'Mustahik (Juta)', data: d.bar_orang.mustahik, backgroundColor: C.rose, borderRadius: 4 }
                ]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg(), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { grid: { color: GRID } } } }
        });
    }

    function createSertifikasiUmkNasCharts() {
        const d = N_DATA.sertifikasi_umk_nas;
        charts.nasSertifTrend = new Chart(document.getElementById('chartNasSertifTrend'), {
            type: 'line',
            data: {
                labels: d.chart.labels,
                datasets: [
                    { label: 'SH Reguler', ...makeLine(d.chart.reguler, C.blue), fill: false },
                    { label: 'Self-Declare', ...makeLine(d.chart.self_declare, C.emerald), fill: false },
                    { label: 'Total', ...makeLine(d.chart.total, C.rose, true) }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: legendCfg(), tooltip: tooltipCfg() },
                scales: lineScale('')
            }
        });
    }

    function initWakafMap() {
        const d = N_DATA.transformasi_wakaf;
        const mapEl = document.getElementById('nasWakafMap');
        if (!mapEl) return;
        
        if (charts.nasWakafMap) charts.nasWakafMap.remove();
        
        charts.nasWakafMap = L.map('nasWakafMap', { scrollWheelZoom: false }).setView([-2.5, 118], 5);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(charts.nasWakafMap);

        const getColor = (v) => {
            if (v > 50) return C.emerald;
            if (v > 10) return C.blue;
            return C.amber;
        };

        d.sebaran_nazhir.forEach(item => {
            const [prov, val, lat, lng] = item;
            const color = getColor(val);
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px ${hexRgba(color, 0.5)};"></div>`,
                iconSize: [12, 12], iconAnchor: [6, 6]
            });
            L.marker([lat, lng], { icon }).addTo(charts.nasWakafMap)
                .bindPopup(`<div class="p-2 font-sans"><p class="text-[10px] font-bold text-slate-400 uppercase mb-1">${prov}</p><p class="text-sm font-black text-slate-800">${val} Nazhir</p></div>`);
        });
    }

    function createLiterasiEkonomiCharts() {
        const d = N_DATA.literasi_ekonomi_nas;
        charts.nasLiterasiIndeks = new Chart(document.getElementById('chartNasLiterasiIndeks'), {
            type: 'line',
            data: {
                labels: d.chart.labels,
                datasets: [{ label: 'Indeks Literasi', ...makeLine(d.chart.data, C.emerald, true) }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: tooltipCfg() },
                scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: GRID } } }
            }
        });
    }

    function createLayananKomunitasCharts() {
        const d = N_DATA.layanan_komunitas_nas;
        const optsTrend = (color) => ({ responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: tooltipCfg() }, scales: lineScale('') });
        const optsTop5 = { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg('bottom'), tooltip: tooltipCfg() }, scales: lineScale('') };

        // Trends
        charts.nasAgenPonpesTrend = new Chart(document.getElementById('chartNasAgenPonpesTrend'), { type: 'line', data: { labels: d.agen_ponpes.trend.labels, datasets: [{ label: 'Agen', ...makeLine(d.agen_ponpes.trend.data, C.emerald, false) }] }, options: optsTrend(C.emerald) });
        charts.nasAgenBumdesTrend = new Chart(document.getElementById('chartNasAgenBumdesTrend'), { type: 'line', data: { labels: d.agen_bumdes.trend.labels, datasets: [{ label: 'Agen', ...makeLine(d.agen_bumdes.trend.data, C.blue, false) }] }, options: optsTrend(C.blue) });
        charts.nasAgenMasjidTrend = new Chart(document.getElementById('chartNasAgenMasjidTrend'), { type: 'line', data: { labels: d.agen_masjid.trend.labels, datasets: [{ label: 'Agen', ...makeLine(d.agen_masjid.trend.data, C.amber, false) }] }, options: optsTrend(C.amber) });

        // Top 5 Lines
        charts.nasAgenPonpesTop5 = new Chart(document.getElementById('chartNasAgenPonpesTop5'), { type: 'line', data: { labels: d.agen_ponpes.top5.labels, datasets: d.agen_ponpes.top5.datasets.map(ds => ({ label: ds.label, ...makeLine(ds.data, ds.color, false) })) }, options: optsTop5 });
        charts.nasAgenBumdesTop5 = new Chart(document.getElementById('chartNasAgenBumdesTop5'), { type: 'line', data: { labels: d.agen_bumdes.top5.labels, datasets: d.agen_bumdes.top5.datasets.map(ds => ({ label: ds.label, ...makeLine(ds.data, ds.color, false) })) }, options: optsTop5 });
        charts.nasAgenMasjidTop5 = new Chart(document.getElementById('chartNasAgenMasjidTop5'), { type: 'line', data: { labels: d.agen_masjid.top5.labels, datasets: d.agen_masjid.top5.datasets.map(ds => ({ label: ds.label, ...makeLine(ds.data, ds.color, false) })) }, options: optsTop5 });

        // Bar Comparisons (Top 5)
        const makeBarData = (data) => ({
            labels: data.top5.datasets.map(ds => ds.label),
            datasets: [
                { label: 'Q1', data: data.top5.datasets.map(ds => ds.data[0]), backgroundColor: C.emerald },
                { label: 'Q2', data: data.top5.datasets.map(ds => ds.data[1]), backgroundColor: C.blue },
                { label: 'Q3', data: data.top5.datasets.map(ds => ds.data[2]), backgroundColor: C.amber },
                { label: 'Q4', data: data.top5.datasets.map(ds => ds.data[3]), backgroundColor: C.rose }
            ]
        });
        const optsBar = { responsive: true, maintainAspectRatio: false, plugins: { legend: legendCfg('bottom'), tooltip: tooltipCfg() }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, grid: { color: GRID } } } };

        charts.nasAgenBumdesBar = new Chart(document.getElementById('chartNasAgenBumdesBar'), { type: 'bar', data: makeBarData(d.agen_bumdes), options: optsBar });
        charts.nasAgenMasjidBar = new Chart(document.getElementById('chartNasAgenMasjidBar'), { type: 'bar', data: makeBarData(d.agen_masjid), options: optsBar });

        // Transactions (Combo)
        const makeComboTrans = (data, color) => ({
            labels: data.transaksi.labels,
            datasets: [
                { type: 'line', label: 'Volume (Miliar Rp)', data: data.transaksi.volume, borderColor: color, backgroundColor: hexRgba(color, 0.1), fill: true, tension: 0.4, yAxisID: 'y1' },
                { type: 'bar', label: 'Jumlah Transaksi', data: data.transaksi.jumlah, backgroundColor: hexRgba(color, 0.3), borderRadius: 4, yAxisID: 'y' }
            ]
        });
        const optsCombo = {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: legendCfg('bottom'), tooltip: tooltipCfg() },
            scales: {
                y: { type: 'linear', position: 'left', grid: { color: GRID } },
                y1: { type: 'linear', position: 'right', grid: { display: false } },
                x: { grid: { display: false } }
            }
        };

        charts.nasAgenBumdesTrans = new Chart(document.getElementById('chartNasAgenBumdesTrans'), { type: 'bar', data: makeComboTrans(d.agen_bumdes, C.blue), options: optsCombo });
        charts.nasAgenMasjidTrans = new Chart(document.getElementById('chartNasAgenMasjidTrans'), { type: 'bar', data: makeComboTrans(d.agen_masjid, C.amber), options: optsCombo });
    }

    function initLayananKomunitasMaps() {
        const d = N_DATA.layanan_komunitas_nas;
        const configMap = (id, data, color) => {
            const el = document.getElementById(id);
            if (!el) return null;
            const map = L.map(id, { scrollWheelZoom: false }).setView([-2.5, 118], 5);
            L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png').addTo(map);
            data.forEach(it => {
                if (it[3] > 0) {
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div style="background-color: ${color}; width: 10px; height: 10px; border-radius: 50%; border: 2px solid white;"></div>`,
                        iconSize: [10, 10]
                    });
                    L.marker([it[1], it[2]], { icon }).addTo(map)
                        .bindPopup(`<div class="p-2 font-sans"><strong>${it[0]}</strong><br>${it[3]} Agen</div>`);
                }
            });
            return map;
        };

        if (charts.nasAgenPonpesMap) charts.nasAgenPonpesMap.remove();
        if (charts.nasAgenBumdesMap) charts.nasAgenBumdesMap.remove();
        if (charts.nasAgenMasjidMap) charts.nasAgenMasjidMap.remove();

        charts.nasAgenPonpesMap = configMap('nasAgenPonpesMap', d.agen_ponpes.map, C.emerald);
        charts.nasAgenBumdesMap = configMap('nasAgenBumdesMap', d.agen_bumdes.map, C.blue);
        charts.nasAgenMasjidMap = configMap('nasAgenMasjidMap', d.agen_masjid.map, C.amber);
    }

    function renderAgenTable(type = 'ponpes') {
        const d = N_DATA.layanan_komunitas_nas[`agen_${type}`].table;
        const pageKey = `${type}Page`;
        const page = state[pageKey] || 1;
        const perPage = 8;
        const start = (page - 1) * perPage;
        const slice = d.slice(start, start + perPage);
        
        const typeCapitalized = type.charAt(0).toUpperCase() + type.slice(1);
        const tbody = document.getElementById(`nasAgen${typeCapitalized}TableBody`);
        if (!tbody) return;

        tbody.innerHTML = slice.map(r => `
            <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                <td class="py-3 font-semibold text-slate-700">${r[0]}</td>
                <td class="py-3 text-center text-slate-600">${r[1]}</td>
                <td class="py-3 text-center text-slate-600">${r[2]}</td>
                <td class="py-3 text-center text-slate-600">${r[3]}</td>
                <td class="py-3 text-center font-bold text-emerald-600">${r[4]}</td>
            </tr>
        `).join('');

        document.getElementById(`agen${typeCapitalized}Start`).textContent = start + 1;
        document.getElementById(`agen${typeCapitalized}End`).textContent = start + slice.length;
        document.getElementById(`agen${typeCapitalized}Total`).textContent = d.length;
        
        const btns = document.querySelectorAll(`.btn-prev-agen[data-agen-type="${type}"], .btn-next-agen[data-agen-type="${type}"]`);
        btns.forEach(btn => {
            if (btn.classList.contains('btn-prev-agen')) btn.disabled = page === 1;
            else btn.disabled = start + perPage >= d.length;
        });
    }

    function renderAllAgenTables() {
        renderAgenTable('ponpes');
        renderAgenTable('bumdes');
        renderAgenTable('masjid');
    }

    // ── Render ────────────────────────────────────
    function render() {
        Object.values(charts).forEach(c => {
            if (c) {
                if (typeof c.destroy === 'function') c.destroy();
                else if (typeof c.remove === 'function') c.remove();
            }
        });
        charts = {};

        updateTexts();
        if (state.tab === 'nasional') {
            updateNasUI();
            if (state.nasView === 'eksekutif') {
                renderNasionalPanels();
                createNasionalCharts();
            } else if (state.nasView === 'daya-saing') {
                renderDayaSaingPanels();
                createDayaSaingCharts();
            } else if (state.nasView === 'pariwisata') {
                renderPariwisataPanels();
                createPariwisataCharts();
            } else if (state.nasView === 'kih') {
                renderKihPanels();
                createKihCharts();
                setTimeout(initKihMap, 100);
            } else if (state.nasView === 'lph') {
                renderLphPanels();
                createLphCharts();
            } else if (state.nasView === 'kodifikasi') {
                renderKodifikasiPanels();
                createKodifikasiCharts();
            } else if (state.nasView === 'kesehatan') {
                renderKesehatanPanels();
                createKesehatanCharts();
            } else if (state.nasView === 'pembiayaan-umkm') {
                createPembiayaanUmkmCharts();
            } else if (state.nasView === 'rph') {
                renderRphPanels();
                createRphCharts();
            } else if (state.nasView === 'hvc') {
                createHvcCharts();
            } else if (state.nasView === 'khas') {
                renderKhasPanels();
                createKhasCharts();
            } else if (state.nasView === 'modul-umkm') {
                createUmkmIhCharts();
            } else if (state.nasView === 'cold-storage') {
                createIndustriDagingCharts();
            } else if (state.nasView === 'indikator-aktivitas') {
                createAktivitasCharts();
            } else if (state.nasView === 'nilai-ekspor') {
                createEksporPdbCharts();
            } else if (state.nasView === 'logistik') {
                renderLogistikPanels();
                createLogistikCharts();
            } else if (state.nasView === 'percepatan-ekspor') {
                renderEksporProvPanels();
                createPercepatanEksporCharts();
            } else if (state.nasView === 'perbankan-syariah') {
                createPerbankanSyariahCharts();
                setTimeout(initPerbankanMap, 200);
            } else if (state.nasView === 'payroll-asn') {
                createPayrollASNCharts();
                setTimeout(initPayrollMap, 200);
            } else if (state.nasView === 'jaminan-sosial') {
                createJaminanSosialCharts();
            } else if (state.nasView === 'pembiayaan-bprs') {
                createPembiayaanBPRSCharts();
            } else if (state.nasView === 'kpbu-syariah') {
                createKpbuSyariahCharts();
            } else if (state.nasView === 'pasar-modal') {
                createPasarModalCharts();
            } else if (state.nasView === 'tren-pasar-modal') {
                createTrenPasarModalCharts();
            } else if (state.nasView === 'inovasi-keuangan') {
                createInovasiKeuanganCharts();
            } else if (state.nasView === 'asuransi-syariah') {
                createAsuransiSyariahCharts();
            } else if (state.nasView === 'dapen-syariah') {
                createDapenSyariahCharts();
            } else if (state.nasView === 'sektor-ekonomi') {
                createSektorEkonomiCharts();
            } else if (state.nasView === 'iknb-syariah') {
                createIKNBSyariahCharts();
            } else if (state.nasView === 'kinerja-perbankan') {
                createKinerjaPerbankanCharts();
            } else if (state.nasView === 'aset-keuangan') {
                createAsetKeuanganCharts();
            } else if (state.nasView === 'perkembangan-aset-keuangan') {
                createPerkembanganAsetKeuanganCharts();
            } else if (state.nasView === 'syariah-daerah') {
                createSyariahDaerahCharts();
            } else if (state.nasView === 'kinerja-bus-uus') {
                createKinerjaBusUusCharts();
            } else if (state.nasView === 'zis-pdb') {
                createZisPdbCharts();
            } else if (state.nasView === 'transformasi-wakaf') {
                createWakafNasionalCharts();
            } else if (state.nasView === 'wakaf-uang') {
                createWakafUangPdbCharts();
            } else if (state.nasView === 'pendanaan-umkm-sosial') {
                createPendanaanUmkmSosialCharts();
            } else if (state.nasView === 'zis-nasional') {
                createZisNasionalCharts();
            } else if (state.nasView === 'sertifikasi-umk-nas') {
                createSertifikasiUmkNasCharts();
            } else if (state.nasView === 'literasi-ekonomi') {
                createLiterasiEkonomiCharts();
            } else if (state.nasView === 'layanan-komunitas') {
                createLayananKomunitasCharts();
                setTimeout(() => {
                    initLayananKomunitasMaps();
                    renderAllAgenTables();
                }, 200);
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
        const toggle = (id, active) => { const el = document.getElementById(id); if (el) el.classList.toggle('hidden', !active); };
        const isWakafUang      = state.nasView === 'wakaf-uang';
        const isPendanaanUmkm  = state.nasView === 'pendanaan-umkm-sosial';
        const isZisNasional    = state.nasView === 'zis-nasional';
        const isSertifUmkNas   = state.nasView === 'sertifikasi-umk-nas';
        const isLiterasiEkonomi = state.nasView === 'literasi-ekonomi';
        const isLayananKomunitas = state.nasView === 'layanan-komunitas';
        const isEksekutif      = state.nasView === 'eksekutif';

        toggle('nasView-eksekutif', state.nasView === 'eksekutif');
        toggle('nasView-daya-saing', state.nasView === 'daya-saing');
        toggle('nasView-pariwisata', state.nasView === 'pariwisata');
        toggle('nasView-kih', state.nasView === 'kih');
        toggle('nasView-lph', state.nasView === 'lph');
        toggle('nasView-kodifikasi', state.nasView === 'kodifikasi');
        toggle('nasView-kesehatan', state.nasView === 'kesehatan');
        toggle('nasView-pembiayaan-umkm', state.nasView === 'pembiayaan-umkm');
        toggle('nasView-rph', state.nasView === 'rph');
        toggle('nasView-hvc', state.nasView === 'hvc');
        toggle('nasView-khas', state.nasView === 'khas');
        toggle('nasView-umkm-ih', state.nasView === 'umkm-ih');
        toggle('nasView-industri-daging', state.nasView === 'industri-daging');
        toggle('nasView-aktivitas-ekonomi', state.nasView === 'aktivitas-ekonomi');
        toggle('nasView-ekspor-pdb', state.nasView === 'ekspor-pdb');
        toggle('nasView-logistik', state.nasView === 'logistik');
        toggle('nasView-percepatan-ekspor', state.nasView === 'percepatan-ekspor');
        toggle('nasView-aset-keuangan', state.nasView === 'aset-keuangan');
        toggle('nasView-perbankan-syariah', state.nasView === 'perbankan-syariah');
        toggle('nasView-jaminan-sosial', state.nasView === 'jaminan-sosial');
        toggle('nasView-pembiayaan-bprs', state.nasView === 'pembiayaan-bprs');
        toggle('nasView-payroll-asn', state.nasView === 'payroll-asn');
        toggle('nasView-kpbu-syariah', state.nasView === 'kpbu-syariah');
        toggle('nasView-pasar-modal', state.nasView === 'pasar-modal');
        toggle('nasView-tren-pasar-modal', state.nasView === 'tren-pasar-modal');
        toggle('nasView-inovasi-keuangan', state.nasView === 'inovasi-keuangan');
        toggle('nasView-asuransi-syariah', state.nasView === 'asuransi-syariah');
        toggle('nasView-dapen-syariah', state.nasView === 'dapen-syariah');
        toggle('nasView-sektor-ekonomi', state.nasView === 'sektor-ekonomi');
        toggle('nasView-iknb-syariah', state.nasView === 'iknb-syariah');
        toggle('nasView-kinerja-perbankan', state.nasView === 'kinerja-perbankan');
        toggle('nasView-perkembangan-aset-keuangan', state.nasView === 'perkembangan-aset-keuangan');
        toggle('nasView-syariah-daerah', state.nasView === 'syariah-daerah');
        toggle('nasView-kinerja-bus-uus', state.nasView === 'kinerja-bus-uus');
        toggle('nasView-zis-pdb', state.nasView === 'zis-pdb');
        toggle('nasView-transformasi-wakaf', state.nasView === 'transformasi-wakaf');
        toggle('nasView-wakaf-uang', isWakafUang);
        toggle('nasView-pendanaan-umkm-sosial', isPendanaanUmkm);
        toggle('nasView-zis-nasional', isZisNasional);
        toggle('nasView-sertifikasi-umk-nas', isSertifUmkNas);
        toggle('nasView-literasi-ekonomi', isLiterasiEkonomi);
        toggle('nasView-layanan-komunitas', isLayananKomunitas);

        const activeBtn = document.querySelector(`.nas-nav-btn[data-view="${state.nasView}"]`);
        const placeholderView = document.getElementById('nasView-placeholder');
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

            const dsBtn = e.target.closest('.ds-year-btn');
            if (dsBtn) {
                state.dsYear = dsBtn.dataset.dsYear;
                renderDayaSaingPanels();
            }
        });

        document.addEventListener('change', (e) => {
            if (e.target && e.target.id === 'dsYearSelector') {
                state.dsYear = e.target.value;
                renderDayaSaingPanels();
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

        const btnPrev = document.getElementById('btnPrevPerbankanAset');
        const btnNext = document.getElementById('btnNextPerbankanAset');
        if (btnPrev) {
            btnPrev.addEventListener('click', () => {
                if (state.perbankanAsetPage > 1) { state.perbankanAsetPage--; renderPerbankanAsetTable(); }
            });
        }
        if (btnNext) {
            btnNext.addEventListener('click', () => {
                const total = N_DATA.perbankan_syariah.perbankan_table.length;
                if (state.perbankanAsetPage * 8 < total) { state.perbankanAsetPage++; renderPerbankanAsetTable(); }
            });
        }

        // DPK table pagination
        document.addEventListener('click', (e) => {
            if (e.target.closest('#btnPrevPerbankanDpk')) {
                if (state.perbankanDpkPage > 1) { state.perbankanDpkPage--; renderPerbankanDpkTable(); }
            }
            if (e.target.closest('#btnNextPerbankanDpk')) {
                const total = N_DATA.perbankan_syariah.map_dpk.length;
                if (state.perbankanDpkPage * 8 < total) { state.perbankanDpkPage++; renderPerbankanDpkTable(); }
            }
            if (e.target.closest('#btnPrevPerbankanPyd')) {
                if (state.perbankanPydPage > 1) { state.perbankanPydPage--; renderPerbankanPydTable(); }
            }
            if (e.target.closest('#btnNextPerbankanPyd')) {
                const total = N_DATA.perbankan_syariah.map_pembiayaan.length;
                if (state.perbankanPydPage * 8 < total) { state.perbankanPydPage++; renderPerbankanPydTable(); }
            }
            if (e.target.closest('#btnPrevPayroll')) {
                if (state.payrollPage > 1) { state.payrollPage--; renderPayrollTable(); }
            }
            if (e.target.closest('#btnNextPayroll')) {
                const total = N_DATA.payroll_asn.provinsi.length;
                if (state.payrollPage * 8 < total) { state.payrollPage++; renderPayrollTable(); }
            }
            if (e.target.closest('#btnPrevKpbu')) {
                if (state.kpbuPage > 1) { state.kpbuPage--; renderKpbuTable(); }
            }
            if (e.target.closest('#btnNextKpbu')) {
                const total = N_DATA.kpbu_syariah.proyek.labels.length;
                if (state.kpbuPage * 8 < total) { state.kpbuPage++; renderKpbuTable(); }
            }
            if (e.target.closest('#btnPrevPasarModal')) {
                if (state.pasarModalPage > 1) { state.pasarModalPage--; renderPasarModalTable(); }
            }
            if (e.target.closest('#btnNextPasarModal')) {
                const total = N_DATA.pasar_modal.tabel_perbankan.labels.length;
                if (state.pasarModalPage * 6 < total) { state.pasarModalPage++; renderPasarModalTable(); }
            }
            if (e.target.closest('.btn-prev-agen')) {
                const type = e.target.closest('.btn-prev-agen').dataset.agenType;
                if (state[`${type}Page`] > 1) { state[`${type}Page`]--; renderAgenTable(type); }
            }
            if (e.target.closest('.btn-next-agen')) {
                const type = e.target.closest('.btn-next-agen').dataset.agenType;
                const total = N_DATA.layanan_komunitas_nas[`agen_${type}`].table.length;
                if (state[`${type}Page`] * 8 < total) { state[`${type}Page`]++; renderAgenTable(type); }
            }
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
