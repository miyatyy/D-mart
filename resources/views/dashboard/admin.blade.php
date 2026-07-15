<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus 'D Mart - Panel Administrasi Koperasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background-color: #fff; color: #000; font-size: 12px; }
            header, nav, button, .no-print, aside { display: none !important; }
            .tab-content.hidden { display: none !important; }
            .tab-content:not(.hidden) { display: block !important; width: 100% !important; }
            th, td { border: 1px solid #000 !important; padding: 6px !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-sm text-gray-700">

    <!-- HEADER ATAS (SERAGAM BRANDING) -->
    <header class="bg-white border-b border-gray-200 py-4 px-4 no-print">
        <div class="container mx-auto max-w-6xl flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-2xl font-black text-purple-600 tracking-tight flex items-center gap-2">🏪 Campus 'D Mart</h1>
                <p class="text-xs text-gray-400 font-medium">Panel Administrasi & Akuntansi Internal Koperasi</p>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
                <span class="bg-purple-100 text-purple-700 px-3 py-1.5 rounded-full border border-purple-200">👑 LISENSI PREMIUM PRO ACTIVE</span>
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:underline font-bold">🚪 Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <!-- HORIZONTAL NAV -->
    <nav class="bg-purple-600 text-white shadow no-print">
        <div class="container mx-auto max-w-6xl flex flex-wrap text-xs font-bold uppercase tracking-wide">
            <a href="/" class="hover:bg-purple-700 px-5 py-3.5">HOME</a>
            <a href="/admin/dashboard" class="bg-purple-800 px-5 py-3.5">Dasbor Utama Koperasi</a>
            <button onclick="exportActiveTabToWord()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3.5 font-black ml-auto normal-case">📝 Ekspor ke Word</button>
            <button onclick="window.print()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-3.5 font-black normal-case">📕 Ekspor ke PDF</button>
        </div>
    </nav>

    <!-- CONTAINER UTAMA -->
    <main class="container mx-auto max-w-6xl p-4 grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- SIDEBAR KIRI: PROFIL ADMIN DENGAN LOGO MATA INTIP + SWITCHER MENU -->
        <aside class="space-y-4 no-print">
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-4">
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider border-b pb-2 mb-3">👤 Akun Eksekutif Admin</h3>
                <div class="space-y-3 text-xs">
                    <div>
                        <label class="block text-gray-400 font-semibold mb-0.5">Nama Pemeriksa</label>
                        <input type="text" value="{{ $user->name }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-semibold mb-0.5">NIM Pengurus</label>
                        <input type="text" value="{{ $user->nim ?? 'Belum Diisi' }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 font-semibold mb-0.5">Email Internal</label>
                        <input type="email" value="{{ $user->email }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                    </div>
                </div>
            </div>

            <!-- Tab Switcher Menu Koperasi -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden text-xs font-semibold">
                <div class="bg-gray-50 border-b p-3 font-bold text-gray-800 uppercase">📊 Menu Pembukuan</div>
                <button onclick="switchTab('tab-koperasi-utama')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4">🏢 Ikhtisar Ringkasan Koperasi</button>
                <button onclick="switchTab('tab-monitoring')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4">📦 Monitoring Stok Tenant</button>
                <button onclick="switchTab('tab-calk')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4">📝 Rekapitulasi CaLK Omzet</button>
                <button onclick="switchTab('tab-neraca')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 block pl-4">⚖️ Laporan Neraca Keuangan</button>
            </div>
        </aside>

        <!-- AREA UTAMA KANAN (TATA TABEL & RINGKASAN DATA KOPERASI) -->
        <section class="lg:col-span-3 space-y-6" id="report-wrapper">
            
            <div class="hidden print:block mb-4 text-center">
                <h1 class="text-lg font-black uppercase">KOPERASI KONSUMEN UNIVERSITAS CAMPUS 'D MART</h1>
                <div class="border-t-2 border-black my-2"></div>
            </div>

            <!-- 🌟 INTEGRASI FILE KOPERASI: TAB RINGKASAN UTAMA KOPERASI KAMPUS -->
            <div id="tab-koperasi-utama" class="tab-content bg-white rounded-lg border border-gray-200 shadow-sm p-6">
                <h2 class="text-base font-black text-gray-800 mb-1 flex items-center gap-2">🏢 DASBOR UTAMA KOPERASI KAMPUS</h2>
                <p class="text-xs text-gray-400 mb-5">Slogan: "Dari Mahasiswa, Oleh Koperasi, Untuk Antasari"</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                        <p class="text-xs text-purple-700 font-bold uppercase tracking-wider">Omzet Bersih Koperasi</p>
                        <p class="text-xl font-black text-purple-700 mt-1">Rp{{ number_format($totalKasKoperasi ?? 15500000, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                        <p class="text-xs text-amber-700 font-bold uppercase tracking-wider">Fee Kampus Terdistribusi (5%)</p>
                        <p class="text-xl font-black text-amber-700 mt-1">Rp{{ number_format($feeCampus ?? 775000, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h3 class="text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">📄 Cetak Laporan LPJ Transparan Internal</h3>
                    <div class="flex gap-2">
                        <button onclick="window.print()" class="flex-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 rounded-lg transition-colors">📥 Unduh PDF Formal</button>
                        <button onclick="exportActiveTabToWord()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-2 rounded-lg transition-colors">📥 Unduh Word (.doc)</button>
                    </div>
                </div>
            </div>

            <!-- TAB 1: MONITORING STOK -->
            <div id="tab-monitoring" class="tab-content bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hidden">
                <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">📦 TABEL: MONITORING KETERSEDIAAN STOK TENANT</div>
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase"><th class="p-3 border">Nama Pengusaha</th><th class="p-3 border">Nama Produk</th><th class="p-3 text-center border">Sisa Stok</th></tr>
                    </thead>
                    <tbody class="divide-y font-medium text-gray-700 bg-white">
                        @foreach($products as $product)
                            <tr>
                                <td class="p-3 font-bold border">{{ $product->user->name ?? 'Tanpa Nama' }}</td>
                                <td class="p-3 text-purple-700 font-semibold border">{{ $product->nama_produk }}</td>
                                <td class="p-3 text-center font-bold text-amber-600 border">{{ $product->stok }} Pcs</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB 2: CATATAN KEUANGAN CaLK -->
            <div id="tab-calk" class="tab-content bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hidden">
                <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">📊 Tabel: Rekapitulasi Pendapatan Akumulasi Laba Tenant</div>
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase"><th class="p-3 border">Nama Tenant</th><th class="p-3 text-center border">Varian Item</th><th class="p-3 text-right border">Total Hasil Omzet</th></tr>
                    </thead>
                    <tbody class="divide-y font-medium text-gray-700 bg-white">
                        @foreach($tenants as $tenant)
                            <tr>
                                <td class="p-3 font-bold border">{{ $tenant->name }}</td>
                                <td class="p-3 text-center border">{{ $tenant->total_produk }} Item</td>
                                <td class="p-3 text-right font-black text-green-600 border">Rp{{ number_format($tenant->total_omzet, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- TAB 3: NERACA -->
            <div id="tab-neraca" class="tab-content bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden hidden">
                <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">⚖️ Laporan Neraca Keuangan Koperasi Aktif (PREMIUM)</div>
                <div class="grid grid-cols-1 md:grid-cols-2 divide-x p-4 gap-4 text-xs bg-white">
                    <div>
                        <h4 class="font-bold text-green-700 border-b pb-1 mb-2">🚀 AKTIVA (HARTA KOPERASI)</h4>
                        <div class="flex justify-between py-1"><span>Kas Utama Operasional</span> <span class="font-bold">Rp15.500.000</span></div>
                        <div class="flex justify-between py-1 border-t font-black mt-2 pt-1 bg-gray-50 p-1"><span>TOTAL HARTA</span> <span>Rp15.500.000</span></div>
                    </div>
                    <div class="md:pl-4">
                        <h4 class="font-bold text-red-700 border-b pb-1 mb-2">💼 PASIVA (UTANG & EKUITAS)</h4>
                        <div class="flex justify-between py-1"><span>Simpanan Wajib Anggota</span> <span class="font-bold">Rp15.500.000</span></div>
                        <div class="flex justify-between py-1 border-t font-black mt-2 pt-1 bg-gray-50 p-1"><span>TOTAL PASIVA</span> <span>Rp15.500.000</span></div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        let currentActiveTabId = 'tab-koperasi-utama';
        function switchTab(tabId) {
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            currentActiveTabId = tabId;
        }
        function exportActiveTabToWord() {
            var tabHtml = document.getElementById(currentActiveTabId).innerHTML;
            var blob = new Blob(['\ufeff' + tabHtml], { type: 'application/msword' });
            var url = URL.createObjectURL(blob);
            var dl = document.createElement('a');
            dl.href = url;
            dl.download = 'Laporan_' + currentActiveTabId + '.doc';
            dl.click();
        }
    </script>
</body>
</html>