<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus 'D Mart - Panel Manajemen Tenant & Koperasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <style>
        @media print {
            body { background-color: #fff; color: #000; font-size: 12px; }
            header, nav, button, .no-print, aside, .btn-crud-pro { display: none !important; }
            .tab-content.hidden { display: none !important; }
            .tab-content:not(.hidden) { display: block !important; width: 100% !important; }
            th, td { border: 1px solid #000 !important; padding: 6px !important; }
        }
    </style>
</head>
<body class="bg-gray-100 font-sans text-sm text-gray-700">

    <!-- HEADER ATAS (SERAGAM BRANDING) -->
    <header class="bg-white border-b border-gray-200 py-4 px-4 relative no-print">
        <div class="container mx-auto max-w-6xl flex flex-col md:flex-row justify-between items-center gap-4">
            
            <div class="flex items-center gap-3 w-full md:w-auto justify-between md:justify-start">
                <!-- 👤 PORTAL AKSES DROPDOWN -->
                <div class="relative z-50">
                    <button onclick="toggleAuthMenu()" class="w-10 h-10 bg-purple-100 hover:bg-purple-200 text-purple-700 font-bold rounded-full flex items-center justify-center text-lg shadow-sm border border-purple-200 focus:outline-none">
                        👤
                    </button>
                    
                    <div id="authDropdownMenu" class="absolute left-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 hidden py-2 text-xs">
                        <div class="px-3 py-2 border-b border-gray-50 bg-gray-50/50">
                            <p class="font-black text-gray-700 uppercase tracking-wider text-[10px]">Portal Akses Kampus</p>
                        </div>
                        
                        @guest
                            <div class="px-4 py-1.5 text-gray-400 font-medium">Masuk Ke Dasbor:</div>
                            <a href="/login" onclick="window.location.href='/login'; return false;" class="block px-4 py-2 hover:bg-purple-50 text-purple-700 font-bold">🛒 1. User / Pembeli</a>
                            <a href="/login" onclick="window.location.href='/login'; return false;" class="block px-4 py-2 hover:bg-purple-50 text-green-700 font-bold">📦 2. Tenant / Seller</a>
                            <a href="/login" onclick="window.location.href='/login'; return false;" class="block px-4 py-2 hover:bg-purple-50 text-amber-600 font-bold">📊 3. Admin Koperasi</a>
                            <div class="border-t my-1"></div>
                            <a href="/login" class="block px-4 py-2.5 hover:bg-purple-50 text-gray-600 hover:text-purple-700 font-bold">🔑 Masuk ke Akun</a>
                            <a href="/register" class="block px-4 py-2.5 hover:bg-purple-50 text-gray-600 hover:text-purple-700 font-bold">🤝 Daftar Tenant Baru</a>
                        @endguest
                        
                        @auth
                            <div class="px-4 py-1.5 text-gray-400 font-medium">Masuk Ke Dasbor:</div>
                            <a href="#" onclick="switchMainTab('tab-katalog')" class="block px-4 py-2 hover:bg-purple-50 text-purple-700 font-bold">🛒 1. User / Pembeli</a>
                            <a href="#" onclick="switchMainTab('tab-tenant-seller')" class="block px-4 py-2 hover:bg-purple-50 text-green-700 font-bold">📦 2. Tenant / Seller</a>
                            <a href="#" onclick="switchMainTab('tab-admin-koperasi')" class="block px-4 py-2 hover:bg-purple-50 text-amber-600 font-bold">📊 3. Admin Koperasi</a>
                            
                            <div class="border-t my-1"></div>
                            <form action="/logout" method="POST" class="block w-full">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-red-50 text-red-600 font-bold">🚪 Keluar Sistem</button>
                            </form>
                        @endauth
                    </div>
                </div>

                <div class="text-left">
                    <h1 class="text-2xl font-black text-purple-600 tracking-tight">🏪 Campus 'D Mart</h1>
                    <p class="text-xs text-gray-400 font-medium">Pusat Belanja & Kreativitas Kampus Resmi</p>
                </div>
            </div>
            
            <div class="w-full md:max-w-md flex shadow-sm rounded-lg overflow-hidden border border-gray-300">
                <input type="text" id="search-input" onkeyup="filterProducts()" placeholder="Cari Produk Kampus..." class="w-full px-4 py-2 text-xs focus:outline-none bg-gray-50">
                <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 flex items-center justify-center transition-colors">🔍</button>
            </div>

            <div class="relative z-40">
                <button onclick="toggleLocationMenu()" id="active-location-btn" class="bg-purple-50 text-purple-700 px-3 py-1.5 rounded-full border border-purple-200 font-bold text-xs flex items-center gap-1 shadow-sm focus:outline-none">
                    📍 Wilayah: <span id="current-selected-campus">Semua Kampus</span> ▼
                </button>
                <div id="locationDropdownMenu" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 hidden py-1 text-xs font-semibold">
                    <button onclick="selectCampus('Semua Kampus')" class="w-full text-left px-4 py-2 hover:bg-purple-50 text-gray-700 block">🌍 Semua Kampus</button>
                    <button onclick="selectCampus('Banjarmasin')" class="w-full text-left px-4 py-2 hover:bg-purple-50 text-purple-700 block">🏫 Kampus 1 (Banjarmasin)</button>
                    <button onclick="selectCampus('Banjarbaru')" class="w-full text-left px-4 py-2 hover:bg-purple-50 text-purple-700 block">🏢 Kampus 2 (Banjarbaru)</button>
                </div>
            </div>
        </div>
    </header>

    <!-- NAV HORIZONTAL UTAMA -->
    <nav class="bg-purple-600 text-white shadow no-print">
        <div class="container mx-auto max-w-6xl flex flex-wrap text-xs font-bold uppercase tracking-wide">
            <button onclick="switchMainTab('tab-katalog')" id="btn-tab-katalog" class="nav-btn bg-purple-800 px-5 py-3.5 focus:outline-none">HOME</button>
            <button onclick="switchMainTab('tab-cara-pemesanan')" id="btn-tab-cara-pemesanan" class="nav-btn hover:bg-purple-700 px-5 py-3.5 focus:outline-none">Cara Pemesanan</button>
            <button onclick="switchMainTab('tab-katalog')" class="hover:bg-purple-700 px-5 py-3.5 focus:outline-none">Katalog</button>
            <button onclick="switchMainTab('tab-pricelist')" id="btn-tab-pricelist" class="nav-btn hover:bg-purple-700 px-5 py-3.5 focus:outline-none">Pricelist</button>
            <button onclick="switchMainTab('tab-keranjang')" id="btn-tab-keranjang" class="nav-btn hover:bg-purple-700 px-5 py-3.5 focus:outline-none">Keranjang (<span id="cart-count">0</span>)</button>
        </div>
    </nav>

    <!-- CONTAINER SPLIT -->
    <main class="container mx-auto max-w-6xl p-4 grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- SIDEBAR KIRI -->
        <aside class="space-y-5 no-print">
            <div id="standard-sidebar-category" class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b p-3 font-bold text-gray-800 text-xs uppercase tracking-wider">📁 Kategori</div>
                <ul class="divide-y text-xs font-semibold text-gray-600">
                    <li><a href="#" onclick="selectCategory('Semua')" class="block p-3 hover:bg-purple-50 hover:text-purple-700 pl-4">📦 Semua Kategori</a></li>
                    <li><a href="#" onclick="selectCategory('Kuliner')" class="block p-3 hover:bg-purple-50 hover:text-purple-700 pl-4">🍔 Kuliner & Camilan</a></li>
                    <li><a href="#" onclick="selectCategory('Kerajinan')" class="block p-3 hover:bg-purple-50 hover:text-purple-700 pl-4">🎨 Kerajinan & Kawat Bulu</a></li>
                </ul>
            </div>

            <div id="seller-sidebar-menu" class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden text-xs font-semibold hidden">
                <div class="bg-amber-500 text-slate-900 p-3 font-black uppercase">📦 MENU SELLER CENTER</div>
                <button onclick="switchSellerSubTab('seller-produk')" class="w-full text-left p-3 hover:bg-purple-50 border-b block pl-4 text-gray-700 font-bold">🛍️ 1. Manajemen Produk</button>
                <button onclick="switchSellerSubTab('seller-pesanan')" class="w-full text-left p-3 hover:bg-purple-50 border-b block pl-4 text-gray-700 font-bold">📝 2. Manajemen Pesanan</button>
                <button onclick="switchSellerSubTab('seller-keuangan')" class="w-full text-left p-3 hover:bg-purple-50 border-b block pl-4 text-gray-700 font-bold">💰 3. Keuangan & Dompet</button>
                <button onclick="switchSellerSubTab('seller-performa')" class="w-full text-left p-3 hover:bg-purple-50 border-b block pl-4 text-gray-700 font-bold">📈 4. Performa Lapak Toko</button>
                <button onclick="switchSellerSubTab('seller-iklan')" class="w-full text-left p-3 hover:bg-purple-50 block pl-4 text-gray-700 font-bold">📢 5. Iklan Banner Gambar</button>
            </div>
            
            <div id="coop-sidebar-menu" class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden text-xs font-semibold hidden">
                <div class="bg-purple-50 border-b p-3 font-bold text-purple-700 uppercase">📊 Menu Pembukuan</div>
                <button onclick="switchCoopSubTab('coop-overview')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 font-bold text-gray-700">🏢 Ikhtisar Koperasi</button>
                <button onclick="switchCoopSubTab('coop-stok')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 font-bold text-gray-700">📦 Monitoring Stok Tenant</button>
                
                @if($isPro ?? false)
                    <button onclick="switchCoopSubTab('coop-calk')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 font-bold text-gray-700">📝 Rekapitulasi CaLK Omzet</button>
                    <button onclick="switchCoopSubTab('coop-neraca')" class="w-full text-left p-3 hover:bg-purple-50 border-b block pl-4 text-gray-700 font-bold">⚖️ Laporan Neraca Keuangan</button>
                    
                    <div class="bg-purple-900 text-white px-3 py-1 font-bold tracking-tight uppercase text-[9px] mt-2">✨ Modul Administrasi PRO</div>
                    <button onclick="switchCoopSubTab('pro-anggota')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 text-gray-700 font-bold">👥 1. Administrasi Anggota</button>
                    <button onclick="switchCoopSubTab('pro-pengurus')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 text-gray-700 font-bold">👔 2. Admin Pengurus & Pengawas</button>
                    <button onclick="switchCoopSubTab('pro-keuangan')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 text-gray-700 font-bold">💰 3. Keuangan Lanjutan</button>
                    <button onclick="switchCoopSubTab('pro-inventaris')" class="w-full text-left p-3 hover:bg-purple-50 hover:text-purple-700 border-b block pl-4 text-gray-700 font-bold">🗃️ 4. Inventaris & Aset</button>
                    <button onclick="switchCoopSubTab('pro-legalitas')" class="w-full text-left p-3 hover:bg-purple-50 block pl-4 text-gray-700 font-bold">📜 5. Legalitas & Surat (AD/ART)</button>
                @endif
            </div>
        </aside>

        <!-- AREA UTAMA KANAN -->
        <section class="lg:col-span-3 space-y-6">
            
            <!-- TAB AREA 1: KATALOG UTAMA -->
            <div id="tab-katalog" class="main-tab-content">
                <div class="bg-white border border-gray-200 rounded-t-lg p-3 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs tracking-wider">
                    🛍️ Etalase Produk Terbaru Terdaftar (Semua Wilayah)
                </div>
                <div id="product-grid-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 bg-white p-4 border border-t-0 border-gray-200 rounded-b-lg">
                    @foreach($products as $product)
                        <div class="product-card border border-gray-200 rounded-lg overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow bg-gray-50/30"
                             data-lokasi="{{ $product->lokasi_spesifik }}"
                             data-nama="{{ strtolower($product->nama_produk) }}"
                             data-kategori="{{ $product->kategori }}">
                            <div class="w-full h-40 bg-gray-200 overflow-hidden relative">
                                <span class="absolute top-2 left-2 bg-purple-600 text-white text-[9px] font-bold uppercase px-2 py-0.5 rounded shadow">{{ strtoupper($product->kategori) }}</span>
                                @if($product->gambar)
                                    <img src="{{ asset('uploads/products/' . $product->gambar) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                            <div class="p-3 flex-1 flex flex-col justify-between space-y-2">
                                <div>
                                    <h4 class="font-bold text-xs text-gray-800 line-clamp-2 min-h-[2rem]">{{ $product->nama_produk }}</h4>
                                    <p class="text-[11px] text-purple-600 font-bold mt-1">📍 {{ $product->lokasi_spesifik }}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-purple-600">Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</p>
                                    <button onclick="addToCart('{{ $product->nama_produk }}', {{ $product->harga_jual }})" class="w-full mt-2 bg-purple-600 hover:bg-purple-700 text-white text-[10px] font-bold py-1.5 rounded uppercase">➕ Keranjang</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 📦 TAB AREA 2: PANEL MANAJEMEN SELLER CENTER -->
            <div id="tab-tenant-seller" class="main-tab-content hidden space-y-4">
                @if(Auth::check() && (Auth::user()->email === 'miya33@gmail.com' || Auth::user()->email === 'miya44@gmail.com' || Auth::user()->role === 'admin'))
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-8 text-center shadow-sm max-w-xl mx-auto">
                        <div class="text-4xl mb-3">🔒</div>
                        <h2 class="text-base font-black text-amber-700 uppercase tracking-tight">Portal Manajemen Produk Dikunci</h2>
                        <p class="text-xs text-amber-600 font-bold mt-1">"Dilarang masuk dan memanipulasi lapak dagangan selain Seller."</p>
                        <p class="text-[11px] text-gray-400 mt-2">Sebagai admin koperasi, Anda tetap dapat memantau stok serta pendapatan fee wirausaha secara transparan pada menu Admin Koperasi.</p>
                    </div>
                @else
                    
                    <!-- SELLER SUB-TAB 1: MANAJEMEN PRODUK -->
                    <div id="seller-produk" class="seller-sub-content space-y-4">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                            <div>
                                <h3 class="font-black text-gray-800 text-base uppercase tracking-tight">🛍️ Manajemen Produk Dagangan Lapak</h3>
                                <p class="text-xs text-gray-400">Atur ketersediaan status, detail spesifikasi produk, dan penyesuaian harga jual.</p>
                            </div>
                            <a href="/product/create" class="bg-amber-500 hover:bg-amber-600 text-slate-900 text-xs font-black px-4 py-2.5 rounded-xl shadow-md transition-all uppercase">➕ Tambah Produk Tenant</a>
                        </div>

                        <!-- 🌟 BAR FILTER STATUS SHOPEE STYLE YANG SEKARANG MEMILIKI PENGHITUNG OTOMATIS -->
                        <div class="bg-white border rounded-lg p-1.5 flex flex-wrap gap-1 text-xs font-bold text-gray-500 shadow-sm">
                            <button id="btn-seller-filter-live" onclick="filterSellerProductStatus('live')" class="seller-prod-tab-btn bg-purple-600 text-white px-4 py-2 rounded-lg">Live (<span id="cnt-live">0</span>)</button>
                            <button id="btn-seller-filter-habis" onclick="filterSellerProductStatus('habis')" class="seller-prod-tab-btn hover:bg-gray-100 px-4 py-2 rounded-lg">Habis (<span id="cnt-habis">0</span>)</button>
                            <button id="btn-seller-filter-diperiksa" onclick="filterSellerProductStatus('diperiksa')" class="seller-prod-tab-btn hover:bg-gray-100 px-4 py-2 rounded-lg">Sedang Diperiksa (0)</button>
                            <button id="btn-seller-filter-tindakan" onclick="filterSellerProductStatus('tindakan')" class="seller-prod-tab-btn hover:bg-gray-100 px-4 py-2 rounded-lg">Perlu Tindakan (0)</button>
                            <button id="btn-seller-filter-diarsipkan" onclick="filterSellerProductStatus('diarsipkan')" class="seller-prod-tab-btn hover:bg-gray-100 px-4 py-2 rounded-lg">Diarsipkan (0)</button>
                        </div>

                        <div id="seller-product-main-container" class="bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 font-bold text-gray-600 border-b uppercase">
                                            <th class="p-3 border text-center w-20">Foto</th>
                                            <th class="p-3 border">Detail Nama & Deskripsi Item</th>
                                            <th class="p-3 border">Kategori</th>
                                            <th class="p-3 text-right border">Atur Harga Jual</th>
                                            <th class="p-3 text-center border">Stok</th>
                                            <th class="p-3 text-center border">Aksi CRUD</th>
                                        </tr>
                                    </thead>
                                    <!-- 🌟 DIKASIH ID PADA BODY TABEL SELLER AGAR BISA DISISIR JAVASCRIPT -->
                                    <tbody id="seller-product-table-body" class="divide-y font-medium text-gray-700 bg-white">
                                        @foreach($products as $product)
                                            <!-- Ditambahkan attribute data-stok-count guna memudahkan engine refactory mendeteksi angka -->
                                            <tr class="seller-product-row" data-stok-count="{{ $product->stok ?? 0 }}">
                                                <td class="p-2 border text-center">
                                                    <div class="w-12 h-12 rounded-lg bg-gray-100 overflow-hidden mx-auto border border-gray-200">
                                                        @if($product->gambar)
                                                            <img src="{{ asset('uploads/products/' . $product->gambar) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center text-[9px] text-gray-400 font-bold">No Pix</div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="p-3 border align-middle">
                                                    <div class="font-bold text-gray-800 text-xs">{{ $product->nama_produk }}</div>
                                                    <div class="text-[10px] text-gray-400 font-medium mt-0.5">Lokasi: {{ $product->lokasi_spesifik }}</div>
                                                </td>
                                                <td class="p-3 border align-middle"><span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase">{{ $product->kategori }}</span></td>
                                                <td class="p-3 text-right font-black text-purple-600 border align-middle">Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</td>
                                                <td class="p-3 text-center font-bold text-amber-600 border align-middle data-stok-text-cell">{{ $product->stok ?? 0 }} Pcs</td>
                                                <td class="p-3 text-center border align-middle">
                                                    <div class="flex items-center justify-center gap-1.5">
                                                        <a href="/product/edit/{{ $product->id }}" class="bg-blue-50 text-blue-700 font-bold px-2 py-1 rounded border border-blue-200 hover:bg-blue-100 transition-colors">✏️ Edit</a>
                                                        <form action="/product/delete/{{ $product->id }}" method="POST" onsubmit="return confirm('Hapus item dagangan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-50 text-red-700 font-bold px-2 py-1 rounded border border-red-200 hover:bg-red-100 transition-colors">🗑️ Hapus</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- SELLER SUB-TAB 2: MANAJEMEN PESANAN -->
                    <div id="seller-pesanan" class="seller-sub-content hidden space-y-4">
                        <div class="bg-white border p-4 rounded-lg shadow-sm">
                            <h3 class="font-black text-gray-800 text-sm uppercase">📋 Lembar Kontrol Pesanan Konsumen</h3>
                            <p class="text-xs text-gray-400">Proses pengiriman pesanan pembeli secara terstruktur dan transparan.</p>
                        </div>
                        <div class="bg-white border rounded-lg p-1.5 flex flex-wrap gap-1 text-xs font-bold text-gray-500 shadow-sm">
                            <button class="bg-purple-600 text-white px-4 py-2 rounded-lg">Belum Bayar (0)</button>
                            <button class="hover:bg-gray-100 px-4 py-2 rounded-lg">Perlu Dikirim (1)</button>
                            <button class="hover:bg-gray-100 px-4 py-2 rounded-lg">Dikirim (0)</button>
                            <button class="hover:bg-gray-100 px-4 py-2 rounded-lg">Selesai (2)</button>
                            <button class="hover:bg-gray-100 px-4 py-2 rounded-lg">Pembatalan (0)</button>
                        </div>
                        <div class="bg-white border rounded-lg p-4 space-y-3 shadow-sm">
                            <div class="border rounded-lg p-3 bg-gray-50/50 text-xs font-medium space-y-2">
                                <div class="flex justify-between border-b pb-1.5 text-[11px] text-gray-400">
                                    <span>No. Pesanan: **TRX-88294711**</span>
                                    <span class="text-amber-600 font-bold uppercase">📦 Perlu Dikirim</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="font-bold text-gray-800">Buket Bunga matahari kawat bulu</div>
                                        <div class="text-gray-400 text-[11px]">Jumlah: 1 Pcs x Rp60.000</div>
                                    </div>
                                    <button onclick="alert('Mencetak label pengiriman alamat mahasiswa...')" class="bg-purple-600 text-white px-3 py-1.5 rounded-lg font-bold text-[11px]">Atur Pengiriman</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SELLER SUB-TAB 3: KEUANGAN & SALDO DOMPET -->
                    <div id="seller-keuangan" class="seller-sub-content hidden space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white border p-4 rounded-xl shadow-sm border-l-4 border-l-purple-600">
                                <div class="text-xs text-gray-400 font-bold uppercase">Saldo Penjual Saat Ini</div>
                                <div class="text-xl font-black text-purple-600 mt-1">Rp450.000</div>
                                <button onclick="alert('Permintaan penarikan dana dikirim ke Admin Kampus!')" class="w-full mt-3 bg-purple-600 text-white font-bold text-xs py-1.5 rounded-lg hover:bg-purple-700">Tarik Saldo Dompet</button>
                            </div>
                            <div class="bg-white border p-4 rounded-xl shadow-sm border-l-4 border-l-green-600">
                                <div class="text-xs text-gray-400 font-bold uppercase">Total Dana Dilepas (Selesai)</div>
                                <div class="text-xl font-black text-green-600 mt-1">Rp1.200.000</div>
                                <p class="text-[10px] text-gray-400 mt-3">Dana bersih yang sudah sukses ditarik ke rekening.</p>
                            </div>
                            <div class="bg-white border p-4 rounded-xl shadow-sm border-l-4 border-l-amber-500">
                                <div class="text-xs text-gray-400 font-bold uppercase">Rekening Bank Utama Anda</div>
                                <div class="text-xs font-black text-gray-800 mt-2">Bank Syariah Indonesia (BSI)</div>
                                <div class="text-xs font-mono text-gray-500 mt-0.5">7122******a.n Miya Tenant</div>
                            </div>
                        </div>
                    </div>

                    <!-- SELLER SUB-TAB 4: PERFORMA LAPAK TOKO -->
                    <div id="seller-performa" class="seller-sub-content hidden space-y-4">
                        <div class="bg-white border p-4 rounded-lg shadow-sm">
                            <h3 class="font-black text-gray-800 text-sm uppercase">📈 Statistik & Performa Penjualan Real-time</h3>
                            <p class="text-xs text-gray-400">Analisis data kunjungan guna meningkatkan omzet konversi toko kampus Anda.</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div class="bg-white border p-3 rounded-xl shadow-sm">
                                <div class="text-[11px] text-gray-400 font-bold uppercase">Pengunjung Toko</div>
                                <div class="text-lg font-black text-purple-600 mt-1">142 Orang</div>
                            </div>
                            <div class="bg-white border p-3 rounded-xl shadow-sm">
                                <div class="text-[11px] text-gray-400 font-bold uppercase">Produk Dilihat</div>
                                <div class="text-lg font-black text-purple-600 mt-1">510 Kali</div>
                            </div>
                            <div class="bg-white border p-3 rounded-xl shadow-sm">
                                <div class="text-[11px] text-gray-400 font-bold uppercase">Pesanan Masuk</div>
                                <div class="text-lg font-black text-amber-500 mt-1">3 Transaksi</div>
                            </div>
                            <div class="bg-white border p-3 rounded-xl shadow-sm">
                                <div class="text-[11px] text-gray-400 font-bold uppercase">Tingkat Konversi</div>
                                <div class="text-lg font-black text-green-600 mt-1">2.15%</div>
                            </div>
                        </div>
                    </div>

                    <!-- SELLER SUB-TAB 5: IKLAN BANNER GAMBAR -->
                    <div id="seller-iklan" class="seller-sub-content hidden space-y-4">
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                            <div>
                                <h3 class="font-black text-gray-800 text-sm uppercase">📢 Modul Promosi: Kampus Ad-Center</h3>
                                <p class="text-xs text-gray-400">Naikkan posisi etalase produk menggunakan Iklan Banner Gambar tanpa membebani penyimpanan hosting server.</p>
                            </div>
                            <button onclick="alert('Membuka form unggah file gambar banner iklan...')" class="bg-amber-500 hover:bg-amber-600 text-slate-900 font-black text-xs px-4 py-2 rounded-xl shadow transition-colors">➕ Pasang Iklan Banner Baru</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border rounded-xl overflow-hidden bg-white shadow-sm flex flex-col justify-between">
                                <div class="p-3 bg-gray-50 border-b flex justify-between items-center text-xs font-bold">
                                    <span>Banner Promo Kreatif Bulu</span>
                                    <span class="bg-green-100 text-green-700 px-1.5 py-0.5 rounded text-[10px]">Aktif Meluncur</span>
                                </div>
                                <div class="w-full h-32 bg-purple-100 flex items-center justify-center text-purple-700 font-bold text-xs p-4 text-center border-b select-none">
                                    🖼️ [ GAMBAR IKLAN: BANNER PROMO DISKON KERAJINAN 15% ]
                                </div>
                                <div class="p-3 flex justify-between items-center text-[11px] font-medium text-gray-500 bg-gray-50/50">
                                    <span>Metrik Klik: **45 Klik**</span>
                                    <button onclick="alert('Iklan dihentikan')" class="text-red-600 font-bold hover:underline">Hentikan Promosi</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 📊 TAB AREA 3: ADMIN KOPERASI KAMPUS -->
            <div id="tab-admin-koperasi" class="main-tab-content hidden space-y-6">
                @if(Auth::check() && (Auth::user()->email === 'miya33@gmail.com' || Auth::user()->email === 'miya44@gmail.com' || Auth::user()->role === 'admin'))
                    
                    <!-- Sub-Tab A: Ikhtisar Keuangan -->
                    <div id="coop-overview" class="coop-sub-content bg-white rounded-lg border shadow-sm p-6">
                        <div id="pro-report-capture" class="bg-white p-2">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b pb-4 mb-4 gap-2">
                                <div>
                                    <h2 class="text-base font-black text-gray-800">🏢 DASBOR UTAMA KOPERASI KAMPUS</h2>
                                    <p class="text-xs text-gray-400">Slogan: "Dari Mahasiswa, Oleh Koperasi, Untuk Antasari"</p>
                                </div>
                                <a href="/product/create" class="bg-purple-600 hover:bg-purple-700 text-white text-xs font-black px-4 py-2.5 rounded-xl shadow-md transition-all uppercase">➕ TAMBAH PRODUK INTERNAL KOPERASI</a>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                                    <p class="text-xs text-purple-700 font-bold uppercase">Omzet Bersih Koperasi</p>
                                    <p class="text-xl font-black text-purple-700 mt-1">Rp{{ number_format($totalKasKoperasi ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <div class="bg-amber-50 p-4 rounded-xl border border-amber-200">
                                    <p class="text-xs text-amber-700 font-bold uppercase">Fee Kampus Terdistribusi (5%)</p>
                                    <p class="text-xl font-black text-amber-700 mt-1">Rp{{ number_format($feeCampus ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 border p-4 rounded-xl mt-4">
                            <h3 class="text-xs font-black text-gray-700 uppercase tracking-wide mb-2">📄 Cetak Laporan LPJ Transparan</h3>
                            @if(Auth::user()->email === 'miya44@gmail.com')
                                <div class="flex flex-col sm:flex-row gap-2">
                                    <button onclick="exportSpecificTabToPDF('pro-report-capture', 'Laporan_Ikhtisar_Koperasi')" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold text-xs py-2 rounded-lg shadow transition-colors">📥 Unduh PDF Formal (PRO)</button>
                                    <button onclick="exportSpecificTabToWord('pro-report-capture', 'Laporan_Ikhtisar_Koperasi')" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs py-2 rounded-lg shadow transition-colors">📥 Unduh Word .docx (PRO)</button>
                                </div>
                                <p class="text-[10px] text-green-600 font-bold mt-2">🌟 Status Akun Anda: PRO Super Premium. Fitur Cetak Aktif!</p>
                            @else
                                <div class="flex flex-col sm:flex-row gap-2 opacity-50">
                                    <button disabled class="flex-1 bg-gray-400 text-white font-bold text-xs py-2 rounded-lg cursor-not-allowed">📥 Unduh PDF Formal</button>
                                    <button disabled class="flex-1 bg-gray-400 text-white font-bold text-xs py-2 rounded-lg cursor-not-allowed">📥 Unduh Word (.docx)</button>
                                </div>
                                <p class="text-[10px] text-amber-600 font-bold mt-2">⚠️ Fitur Ekspor PDF/Word terkunci. Hanya tersedia untuk Lisensi PRO Super Premium (miya44@gmail.com).</p>
                            @endif
                        </div>
                    </div>

                    <!-- Sub-Tab B: Monitoring Stok Tenant -->
                    <div id="coop-stok" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
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
                                        <td class="p-3 text-center font-bold text-amber-600 border">{{ $product->stok ?? 0 }} Pcs</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($isPro ?? false)
                        <!-- Sub-Tab C: CaLK Omzet -->
                        <div id="coop-calk" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">📊 Laporan Pendapatan Akumulasi Laba Tenant</div>
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase"><th class="p-3 border">Nama Tenant</th><th class="p-3 text-center border">Varian Item</th><th class="p-3 text-right border">Total Hasil Omzet</th></tr>
                                </thead>
                                <tbody class="divide-y font-medium text-gray-700 bg-white">
                                    @if(isset($tenants))
                                        @foreach($tenants as $tenant)
                                            <tr>
                                                <td class="p-3 font-bold border">{{ $tenant->name }}</td>
                                                <td class="p-3 text-center border">{{ $tenant->total_produk }} Item</td>
                                                <td class="p-3 text-right font-black text-green-600 border">Rp{{ number_format($tenant->total_omzet, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Sub-Tab D: Neraca Keuangan -->
                        <div id="coop-neraca" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">⚖️ Laporan Neraca Keuangan Koperasi Aktif</div>
                            <div class="grid grid-cols-1 md:grid-cols-2 divide-x p-4 gap-4 text-xs bg-white">
                                <div>
                                    <h4 class="font-bold text-green-700 border-b pb-1 mb-2">🚀 AKTIVA (HARTA KOPERASI)</h4>
                                    <div class="flex justify-between py-1"><span>Kas Utama Operasional</span> <span class="font-bold">Rp15.500.000</span></div>
                                    <div class="flex justify-between py-1 border-t font-black mt-2 pt-1 bg-gray-50 p-1"><span>TOTAL HARTA</span> <span>Rp15.500.000</span></div>
                                </div>
                                <div>
                                    <h4 class="font-bold text-red-700 border-b pb-1 mb-2">💼 PASIVA (UTANG & EKUITAS)</h4>
                                    <div class="flex justify-between py-1"><span>Simpanan Wajib Anggota</span> <span class="font-bold">Rp15.500.000</span></div>
                                    <div class="flex justify-between py-1 border-t font-black mt-2 pt-1 bg-gray-50 p-1"><span>TOTAL PASIVA</span> <span>Rp15.500.000</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Modul Administrasi Anggota -->
                        <div id="pro-anggota" class="coop-sub-content hidden bg-white rounded-lg border shadow-sm p-5 space-y-4">
                            <div class="border-b pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <h3 class="font-black text-purple-700 text-sm">👥 MODUL: ADMINISTRASI ANGGOTA</h3>
                                <div class="flex flex-wrap gap-2 btn-crud-pro">
                                    <button onclick="exportSpecificTabToPDF('print-area-anggota', 'Laporan_Administrasi_Anggota')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📄 PDF</button>
                                    <button onclick="exportSpecificTabToWord('print-area-anggota', 'Laporan_Administrasi_Anggota')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📝 Word</button>
                                    <button onclick="window.print()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">🖨️ Cetak</button>
                                    <button onclick="openProModal('anggota')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-[10px] px-3 py-1 rounded shadow">➕ Tambah</button>
                                </div>
                            </div>
                            <div id="print-area-anggota" class="border rounded-lg overflow-hidden bg-white p-2">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead><tr class="bg-gray-50 border-b font-black text-gray-500"><th class="p-2 border">ID Anggota</th><th class="p-2 border">Nama Lengkap</th><th class="p-2 border">Iuran Pokok</th><th class="p-2 border text-center btn-crud-pro">Aksi CRUD</th></tr></thead>
                                    <tbody id="table-pro-anggota" class="divide-y font-semibold text-gray-600 bg-white">
                                        <tr id="row-anggota-1">
                                            <td class="p-2 border data-field-1">KP-01</td>
                                            <td class="p-2 border font-bold text-slate-800 data-field-2">Miya Super Premium</td>
                                            <td class="p-2 border data-field-3">Rp5.000.000</td>
                                            <td class="p-2 border text-center space-x-2 btn-crud-pro">
                                                <button onclick="editProRow('anggota', 'anggota-1')" class="text-blue-600 hover:underline font-bold">✏️ Edit</button>
                                                <button onclick="deleteProRow('anggota-1')" class="text-red-600 hover:underline">🗑️ Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modul Administrasi Pengurus -->
                        <div id="pro-pengurus" class="coop-sub-content hidden bg-white rounded-lg border shadow-sm p-5 space-y-4">
                            <div class="border-b pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <h3 class="font-black text-purple-700 text-sm">👔 MODUL: ADMINISTRASI PENGURUS & PENGAWAS</h3>
                                <div class="flex flex-wrap gap-2 btn-crud-pro">
                                    <button onclick="exportSpecificTabToPDF('print-area-pengurus', 'Laporan_Administrasi_Pengurus')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📄 PDF</button>
                                    <button onclick="exportSpecificTabToWord('print-area-pengurus', 'Laporan_Administrasi_Pengurus')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📝 Word</button>
                                    <button onclick="window.print()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">🖨️ Cetak</button>
                                    <button onclick="openProModal('pengurus')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-[10px] px-3 py-1 rounded shadow">➕ Tambah</button>
                                </div>
                            </div>
                            <div id="print-area-pengurus" class="border rounded-lg overflow-hidden bg-white p-2">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead><tr class="bg-gray-50 border-b font-black text-gray-500"><th class="p-2 border">Nama Dokumen / Rapat</th><th class="p-2 border">Hasil Notulensi Ringkas</th><th class="p-2 border">Jenis Berkas</th><th class="p-2 border text-center btn-crud-pro">Aksi CRUD</th></tr></thead>
                                    <tbody id="table-pro-pengurus" class="divide-y font-semibold text-gray-600 bg-white">
                                        <tr id="row-pengurus-1">
                                            <td class="p-2 border font-bold data-field-1">Rapat Anggota Tahunan (RAT) 2026</td>
                                            <td class="p-2 border data-field-2">Pengesahan alokasi dana fee kampus 5% secara real-time selesai disepakati.</td>
                                            <td class="p-2 border data-field-3">Notulen Formal</td>
                                            <td class="p-2 border text-center space-x-2 btn-crud-pro">
                                                <button onclick="editProRow('pengurus', 'pengurus-1')" class="text-blue-600 hover:underline font-bold">✏️ Edit</button>
                                                <button onclick="deleteProRow('pengurus-1')" class="text-red-600 hover:underline">🗑️ Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modul Keuangan Lanjutan -->
                        <div id="pro-keuangan" class="coop-sub-content hidden bg-white rounded-lg border shadow-sm p-5 space-y-4">
                            <div class="border-b pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <h3 class="font-black text-purple-700 text-sm">💰 MODUL: ARSIPE KAS & BUKTI TRANSAKSI</h3>
                                <div class="flex flex-wrap gap-2 btn-crud-pro">
                                    <button onclick="exportSpecificTabToPDF('print-area-keuangan', 'Laporan_Arsip_Keuangan')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📄 PDF</button>
                                    <button onclick="exportSpecificTabToWord('print-area-keuangan', 'Laporan_Arsip_Keuangan')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📝 Word</button>
                                    <button onclick="window.print()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">🖨️ Cetak</button>
                                    <button onclick="openProModal('keuangan')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-[10px] px-3 py-1 rounded shadow">➕ Tambah</button>
                                </div>
                            </div>
                            <div id="print-area-keuangan" class="border rounded-lg overflow-hidden bg-white p-2">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead><tr class="bg-gray-50 border-b font-black text-gray-500"><th class="p-2 border">Nomor Kuitansi</th><th class="p-2 border">Deskripsi Alur Kas</th><th class="p-2 text-right border">Jumlah Saldo</th><th class="p-2 border text-center btn-crud-pro">Aksi CRUD</th></tr></thead>
                                    <tbody id="table-pro-keuangan" class="divide-y font-semibold text-gray-600 bg-white">
                                        <tr id="row-keuangan-1">
                                            <td class="p-2 border font-mono font-bold text-purple-700 data-field-1">KW-001/DMART</td>
                                            <td class="p-2 border data-field-2">Penerimaan dana bagi hasil penjualan camilan mahasiswa</td>
                                            <td class="p-2 text-right border font-black text-green-600 data-field-3">Rp250.000</td>
                                            <td class="p-2 border text-center space-x-2 btn-crud-pro">
                                                <button onclick="editProRow('keuangan', 'keuangan-1')" class="text-blue-600 hover:underline font-bold">✏️ Edit</button>
                                                <button onclick="deleteProRow('keuangan-1')" class="text-red-600 hover:underline">🗑️ Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modul Inventaris Koperasi -->
                        <div id="pro-inventaris" class="coop-sub-content hidden bg-white rounded-lg border shadow-sm p-5 space-y-4">
                            <div class="border-b pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <h3 class="font-black text-purple-700 text-sm">🗃️ MODUL: INVENTARIS BARANG & ASET KOPERASI</h3>
                                <div class="flex flex-wrap gap-2 btn-crud-pro">
                                    <button onclick="exportSpecificTabToPDF('print-area-inventaris', 'Laporan_Inventaris_Aset')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📄 PDF</button>
                                    <button onclick="exportSpecificTabToWord('print-area-inventaris', 'Laporan_Inventaris_Aset')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📝 Word</button>
                                    <button onclick="window.print()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">🖨️ Cetak</button>
                                    <button onclick="openProModal('inventaris')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-[10px] px-3 py-1 rounded shadow">➕ Tambah</button>
                                </div>
                            </div>
                            <div id="print-area-inventaris" class="border rounded-lg overflow-hidden bg-white p-2">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead><tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase"><th class="p-2 border">Nama Aset / Barang</th><th class="p-2 border">Kategori Prasarana</th><th class="p-2 text-center border">Kuantitas</th><th class="p-2 border text-center btn-crud-pro">Aksi CRUD</th></tr></thead>
                                    <tbody id="table-pro-inventaris" class="divide-y font-semibold text-gray-600 bg-white">
                                        <tr id="row-inventaris-1">
                                            <td class="p-2 border font-bold data-field-1">Printer Kasir Bluetooth E-50</td>
                                            <td class="p-2 border data-field-2">Aset Elektronik Utama</td>
                                            <td class="p-2 text-center border text-amber-600 font-bold data-field-3">2 Unit</td>
                                            <td class="p-2 border text-center space-x-2 btn-crud-pro">
                                                <button onclick="editProRow('inventaris', 'inventaris-1')" class="text-blue-600 hover:underline font-bold">✏️ Edit</button>
                                                <button onclick="deleteProRow('inventaris-1')" class="text-red-600 hover:underline">🗑️ Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Modul Legalitas AD/ART -->
                        <div id="pro-legalitas" class="coop-sub-content hidden bg-white rounded-lg border shadow-sm p-5 space-y-4">
                            <div class="border-b pb-2 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <h3 class="font-black text-purple-700 text-sm">📜 MODUL: KORESPONDENSI ARSIP SURAT & REGULASI</h3>
                                <div class="flex flex-wrap gap-2 btn-crud-pro">
                                    <button onclick="exportSpecificTabToPDF('print-area-legalitas', 'Laporan_Arsip_Legalitas')" class="bg-red-600 hover:bg-red-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📄 PDF</button>
                                    <button onclick="exportSpecificTabToWord('print-area-legalitas', 'Laporan_Arsip_Legalitas')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">📝 Word</button>
                                    <button onclick="window.print()" class="bg-slate-700 hover:bg-slate-800 text-white font-bold text-[10px] px-2.5 py-1 rounded shadow">🖨️ Cetak</button>
                                    <button onclick="openProModal('legalitas')" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-[10px] px-3 py-1 rounded shadow">➕ Tambah</button>
                                </div>
                            </div>
                            <div id="print-area-legalitas" class="border rounded-lg overflow-hidden bg-white p-2">
                                <table class="w-full text-left text-xs border-collapse">
                                    <thead><tr class="bg-gray-50 border-b font-black text-gray-500"><th class="p-2 border">Nomor Surat Resmi</th><th class="p-2 border">Perihal Urusan AD/ART</th><th class="p-2 text-center border">Jenis Surat</th><th class="p-2 border text-center btn-crud-pro">Aksi CRUD</th></tr></thead>
                                    <tbody id="table-pro-legalitas" class="divide-y font-semibold text-gray-600 bg-white">
                                        <tr id="row-legalitas-1">
                                            <td class="p-2 border font-bold data-field-1">004/SK-DIR/KOP/2026</td>
                                            <td class="p-2 border data-field-2">Pemberlakuan revisi lembar lampiran AD/ART Koperasi Inti Kampus</td>
                                            <td class="p-2 text-center border data-field-3">Surat Keputusan (SK)</td>
                                            <td class="p-2 border text-center space-x-2 btn-crud-pro">
                                                <button onclick="editProRow('legalitas', 'legalitas-1')" class="text-blue-600 hover:underline font-bold">✏️ Edit</button>
                                                <button onclick="deleteProRow('legalitas-1')" class="text-red-600 hover:underline">🗑️ Hapus</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                @else
                    <div class="bg-red-50 border border-red-200 rounded-2xl p-8 text-center shadow-sm max-w-xl mx-auto">
                        <div class="text-4xl mb-3">🛑</div>
                        <h2 class="text-base font-black text-red-700 uppercase tracking-tight">Akses Terbatasi</h2>
                        <p class="text-xs text-red-600 font-bold mt-1">"Dilarang masuk selain admin koperasi."</p>
                        <p class="text-[11px] text-gray-400 mt-2">Seluruh pembukuan keuangan, hitungan fee 5%, dan data finansial koperasi kampus dikunci otomatis di panel audit ini.</p>
                    </div>
                @endif
            </div>

            <!-- TAB PENDUKUNG -->
            <div id="tab-cara-pemesanan" class="main-tab-content hidden bg-white border p-5 text-xs rounded-lg">Menu Cara Pemesanan Kampus Aktif.</div>
            <div id="tab-pricelist" class="main-tab-content hidden bg-white border p-5 text-xs rounded-lg">Menu Pricelist Kampus Aktif.</div>
            <div id="tab-keranjang" class="main-tab-content hidden bg-white border p-5 text-xs rounded-lg">Menu Keranjang Belanja Aktif.</div>
        </section>
    </main>

    <!-- MODAL FORM INPUT -->
    <div id="proFormModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center hidden no-print">
        <div class="bg-white rounded-2xl border max-w-md w-full p-6 space-y-4 shadow-2xl">
            <div class="flex justify-between items-center border-b pb-2">
                <h3 id="modal-title-text" class="font-black text-purple-700 text-sm uppercase">Input Data Administrasi</h3>
                <button onclick="closeProModal()" class="text-gray-400 hover:text-gray-600 font-bold text-sm">✕</button>
            </div>
            
            <input type="hidden" id="current-active-modul-type">
            <input type="hidden" id="current-edit-row-id">
            
            <div class="space-y-3">
                <div>
                    <label id="label-input-satu" class="block text-xs font-bold text-gray-600 mb-1">Kolom Pertama</label>
                    <input type="text" id="modal-input-field-satu" class="w-full border rounded-lg p-2 text-xs bg-gray-50 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label id="label-input-dua" class="block text-xs font-bold text-gray-600 mb-1">Kolom Kedua</label>
                    <input type="text" id="modal-input-field-dua" class="w-full border rounded-lg p-2 text-xs bg-gray-50 focus:outline-none focus:border-purple-600">
                </div>
                <div>
                    <label id="label-input-tiga" class="block text-xs font-bold text-gray-600 mb-1">Kolom Ketiga</label>
                    <input type="text" id="modal-input-field-tiga" class="w-full border rounded-lg p-2 text-xs bg-gray-50 focus:outline-none focus:border-purple-600">
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button onclick="closeProModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-600 font-bold text-xs px-4 py-2 rounded-lg">Batal</button>
                <button onclick="submitProHtmlCrudEngine()" class="bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs px-4 py-2 rounded-lg shadow">Simpan Data</button>
            </div>
        </div>
    </div>

    <!-- JS TAB SYSTEM AND FILTERS -->
    <script>
        let selectedCampus = 'Semua Kampus';
        let selectedCategory = 'Semua';
        let uniqueRowCounter = 2; 
        let currentSellerActiveFilter = 'live'; // State filter aktif bawaan Shopee

        window.onload = function() {
            const userEmail = "{{ Auth::check() ? Auth::user()->email : '' }}";
            const userRole = "{{ Auth::check() ? Auth::user()->role : 'guest' }}";
            
            if (userEmail === 'miya33@gmail.com' || userEmail === 'miya44@gmail.com' || userRole === 'admin') {
                switchMainTab('tab-admin-koperasi');
            } else if (userEmail === 'miya22@gmail.com' || userRole === 'seller' || userRole === 'dosen') {
                switchMainTab('tab-tenant-seller');
                // 🌟 JALANKAN REFACTORY REKAPITULASI HITUNGAN STATUS BARANG OTOMATIS
                runAutomaticSellerProductRefactory();
            } else {
                switchMainTab('tab-katalog');
            }
        };

        // 🌟 ENGINE REFACTORY OTOMATIS SISI SELLER (MEMILAH STOK 0 PCS KE TAB HABIS)
        function runAutomaticSellerProductRefactory() {
            const rows = document.querySelectorAll('.seller-product-row');
            let liveCounter = 0;
            let habisCounter = 0;

            rows.forEach(row => {
                const stokValue = parseInt(row.getAttribute('data-stok-count')) || 0;
                
                if (stokValue === 0) {
                    row.setAttribute('data-shopee-status', 'habis');
                    habisCounter++;
                } else {
                    row.setAttribute('data-shopee-status', 'live');
                    liveCounter++;
                }
            });

            // Update digit angka counter di dalam tombol filter atas secara real-time
            document.getElementById('cnt-live').textContent = liveCounter;
            document.getElementById('cnt-habis').textContent = habisCounter;

            // Jalankan filter visual awal
            applySellerVisualFilter();
        }

        // 🛍️ ENGINE PENYARINGAN VISUAL SHOPEE CENTER LAPAK
        function filterSellerProductStatus(statusType) {
            currentSellerActiveFilter = statusType;
            
            document.querySelectorAll('.seller-prod-tab-btn').forEach(btn => {
                btn.className = "seller-prod-tab-btn hover:bg-gray-100 px-4 py-2 rounded-lg text-gray-500 font-bold";
            });
            
            document.getElementById(`btn-seller-filter-${statusType}`).className = "seller-prod-tab-btn bg-purple-600 text-white px-4 py-2 rounded-lg font-bold";
            
            applySellerVisualFilter();
        }

        function applySellerVisualFilter() {
            const rows = document.querySelectorAll('.seller-product-row');
            const mainTableContainer = document.getElementById('seller-product-main-container');
            
            let renderingRowsCount = 0;

            rows.forEach(row => {
                const rowStatus = row.getAttribute('data-shopee-status');
                
                if (currentSellerActiveFilter === 'live' && rowStatus === 'live') {
                    row.classList.remove('hidden');
                    renderingRowsCount++;
                } else if (currentSellerActiveFilter === 'habis' && rowStatus === 'habis') {
                    row.classList.remove('hidden');
                    renderingRowsCount++;
                } else {
                    row.classList.add('hidden');
                }
            });

            // Handle sisa tab samping reguler (diperiksa, tindakan, diarsipkan) yang masih kosong
            if (['diperiksa', 'tindakan', 'diarsipkan'].includes(currentSellerActiveFilter)) {
                mainTableContainer.classList.add('hidden');
            } else {
                mainTableContainer.classList.remove('hidden');
            }
        }

        function toggleLocationMenu() { document.getElementById('locationDropdownMenu').classList.toggle('hidden'); }
        function toggleAuthMenu() { document.getElementById('authDropdownMenu').classList.toggle('hidden'); }
        
        function switchMainTab(tabId) {
            document.querySelectorAll('.main-tab-content').forEach(tab => tab.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            
            const coopSidebar = document.getElementById('coop-sidebar-menu');
            const sellerSidebar = document.getElementById('seller-sidebar-menu');
            const standardSidebar = document.getElementById('standard-sidebar-category');
            
            if(tabId === 'tab-admin-koperasi') {
                coopSidebar.classList.remove('hidden');
                sellerSidebar.classList.add('hidden');
                standardSidebar.classList.add('hidden');
                switchCoopSubTab('coop-overview');
            } else if(tabId === 'tab-tenant-seller') {
                sellerSidebar.classList.remove('hidden');
                coopSidebar.classList.add('hidden');
                standardSidebar.classList.add('hidden');
                switchSellerSubTab('seller-produk');
                runAutomaticSellerProductRefactory(); // trigger ulang kalkulasi
            } else {
                standardSidebar.classList.remove('hidden');
                coopSidebar.classList.add('hidden');
                sellerSidebar.classList.add('hidden');
            }
        }
        
        function switchSellerSubTab(subTabId) {
            document.querySelectorAll('.seller-sub-content').forEach(sub => sub.classList.add('hidden'));
            const targetElement = document.getElementById(subTabId);
            if (targetElement) {
                targetElement.classList.remove('hidden');
            }
        }

        function switchCoopSubTab(subTabId) {
            document.querySelectorAll('.coop-sub-content').forEach(sub => sub.classList.add('hidden'));
            const targetElement = document.getElementById(subTabId);
            if (targetElement) {
                targetElement.classList.remove('hidden');
                document.getElementById('coop-sidebar-menu').classList.remove('hidden');
            }
        }

        function openProModal(modulType) {
            document.getElementById('current-active-modul-type').value = modulType;
            document.getElementById('current-edit-row-id').value = ''; 
            setModalLabels(modulType);
            document.getElementById('modal-input-field-satu').value = '';
            document.getElementById('modal-input-field-dua').value = '';
            document.getElementById('modal-input-field-tiga').value = '';
            document.getElementById('proFormModal').classList.remove('hidden');
        }

        function setModalLabels(modulType) {
            if (modulType === 'anggota') {
                document.getElementById('modal-title-text').textContent = '👥 Tambah Anggota Koperasi Baru';
                document.getElementById('label-input-satu').textContent = 'ID Anggota Baru (Contoh: KP-03)';
                document.getElementById('label-input-dua').textContent = 'Nama Lengkap Anggota';
                document.getElementById('label-input-tiga').textContent = 'Besar Simpanan Pokok (Contoh: Rp5.000.000)';
            } else if (modulType === 'pengurus') {
                document.getElementById('modal-title-text').textContent = '👔 Catat Keputusan / Notulen Rapat';
                document.getElementById('label-input-satu').textContent = 'Nama Rapat / Judul SK';
                document.getElementById('label-input-dua').textContent = 'Notulensi Hasil Keputusan Ringkas';
                document.getElementById('label-input-tiga').textContent = 'Jenis Berkas (Contoh: Notulen Formal)';
            } else if (modulType === 'keuangan') {
                document.getElementById('modal-title-text').textContent = '💰 Input Arus Bukti Kas Kuitansi';
                document.getElementById('label-input-satu').textContent = 'Nomor Seri Kuitansi (Contoh: KW-002)';
                document.getElementById('label-input-dua').textContent = 'Deskripsi Alur Kas Masuk/Keluar';
                document.getElementById('label-input-tiga').textContent = 'Jumlah Saldo Transaksi';
            } else if (modulType === 'inventaris') {
                document.getElementById('modal-title-text').textContent = '🗃️ Daftarkan Inventaris Barang/Aset';
                document.getElementById('label-input-satu').textContent = 'Nama Aset Baru';
                document.getElementById('label-input-dua').textContent = 'Kategori Sarana Prasarana';
                document.getElementById('label-input-tiga').textContent = 'Kuantitas / Jumlah Unit (Contoh: 5 Unit)';
            } else if (modulType === 'legalitas') {
                document.getElementById('modal-title-text').textContent = '📜 Pengarsipan Surat Keluar/Masuk Baru';
                document.getElementById('label-input-satu').textContent = 'Nomor Surat Koperasi';
                document.getElementById('label-input-dua').textContent = 'Perihal / Isi Ringkas Regulasi';
                document.getElementById('label-input-tiga').textContent = 'Jenis Surat Resmi (Contoh: Surat Edaran)';
            }
        }

        function closeProModal() {
            document.getElementById('proFormModal').classList.add('hidden');
        }

        function editProRow(modulType, rowId) {
            document.getElementById('current-active-modul-type').value = modulType;
            document.getElementById('current-edit-row-id').value = rowId;
            setModalLabels(modulType);
            document.getElementById('modal-title-text').textContent = '✏️ Edit Data Administrasi Koperasi';

            const txtSatu = document.querySelector(`#row-${rowId} .data-field-1`).textContent;
            const txtDua = document.querySelector(`#row-${rowId} .data-field-2`).textContent;
            const txtTiga = document.querySelector(`#row-${rowId} .data-field-3`).textContent;

            document.getElementById('modal-input-field-satu').value = txtSatu;
            document.getElementById('modal-input-field-dua').value = txtDua;
            document.getElementById('modal-input-field-tiga').value = txtTiga;
            document.getElementById('proFormModal').classList.remove('hidden');
        }

        function submitProHtmlCrudEngine() {
            const type = document.getElementById('current-active-modul-type').value;
            const editRowId = document.getElementById('current-edit-row-id').value;
            const val1 = document.getElementById('modal-input-field-satu').value;
            const val2 = document.getElementById('modal-input-field-dua').value;
            const val3 = document.getElementById('modal-input-field-tiga').value;
            
            if (!val1 || !val2 || !val3) {
                alert('Semua kolom data wajib diisi demi validitas audit koperasi!');
                return;
            }
            
            if(editRowId) {
                document.querySelector(`#row-${editRowId} .data-field-1`).textContent = val1;
                document.querySelector(`#row-${editRowId} .data-field-2`).textContent = val2;
                document.querySelector(`#row-${editRowId} .data-field-3`).textContent = val3;
            } else {
                const targetTableBody = document.getElementById(`table-pro-${type}`);
                const newRowId = `${type}-${uniqueRowCounter}`;
                
                let htmlRow = '';
                if (type === 'anggota') {
                    htmlRow = `<tr id="row-${newRowId}"><td class="p-2 border data-field-1">${val1}</td><td class="p-2 border font-bold text-slate-800 data-field-2">${val2}</td><td class="p-2 border data-field-3">${val3}</td><td class="p-2 border text-center space-x-2 btn-crud-pro"><button onclick="editProRow('anggota', '${newRowId}')" class="text-blue-600 font-bold hover:underline">✏️ Edit</button><button onclick="deleteProRow('${newRowId}')" class="text-red-600 hover:underline">🗑️ Hapus</button></td></tr>`;
                } else if (type === 'keuangan') {
                    htmlRow = `<tr id="row-${newRowId}"><td class="p-2 border font-mono font-bold text-purple-700 data-field-1">${val1}</td><td class="p-2 border data-field-2">${val2}</td><td class="p-2 text-right border font-black text-green-600 data-field-3">${val3}</td><td class="p-2 border text-center space-x-2 btn-crud-pro"><button onclick="editProRow('keuangan', '${newRowId}')" class="text-blue-600 font-bold hover:underline">✏️ Edit</button><button onclick="deleteProRow('${newRowId}')" class="text-red-600 hover:underline">🗑️ Hapus</button></td></tr>`;
                } else if (type === 'inventaris') {
                    htmlRow = `<tr id="row-${newRowId}"><td class="p-2 border font-bold data-field-1">${val1}</td><td class="p-2 border data-field-2">${val2}</td><td class="p-2 text-center border text-amber-600 font-bold data-field-3">${val3}</td><td class="p-2 border text-center space-x-2 btn-crud-pro"><button onclick="editProRow('inventaris', '${newRowId}')" class="text-blue-600 font-bold hover:underline">✏️ Edit</button><button onclick="deleteProRow('${newRowId}')" class="text-red-600 hover:underline">🗑️ Hapus</button></td></tr>`;
                } else if (type === 'legalitas') {
                    htmlRow = `<tr id="row-${newRowId}"><td class="p-2 border font-bold data-field-1">${val1}</td><td class="p-2 border data-field-2">${val2}</td><td class="p-2 text-center border data-field-3">${val3}</td><td class="p-2 border text-center space-x-2 btn-crud-pro"><button onclick="editProRow('legalitas', '${newRowId}')" class="text-blue-600 font-bold hover:underline">✏️ Edit</button><button onclick="deleteProRow('${newRowId}')" class="text-red-600 hover:underline">🗑️ Hapus</button></td></tr>`;
                } else {
                    htmlRow = `<tr id="row-${newRowId}"><td class="p-2 border data-field-1">${val1}</td><td class="p-2 border data-field-2">${val2}</td><td class="p-2 border data-field-3">${val3}</td><td class="p-2 border text-center space-x-2 btn-crud-pro"><button onclick="editProRow('${type}', '${newRowId}')" class="text-blue-600 font-bold hover:underline">✏️ Edit</button><button onclick="deleteProRow('${newRowId}')" class="text-red-600 hover:underline">🗑️ Hapus</button></td></tr>`;
                }
                
                targetTableBody.insertAdjacentHTML('beforeend', htmlRow);
                uniqueRowCounter++;
            }
            closeProModal();
        }

        function deleteProRow(rowId) {
            if (confirm('Apakah Anda yakin ingin menghapus arsip administrasi resmi ini?')) {
                const tr = document.getElementById(`row-${rowId}`);
                if (tr) tr.remove();
            }
        }

        function exportSpecificTabToPDF(elementId, customFileName) {
            const element = document.getElementById(elementId);
            const printTitle = element.querySelector('.print-only');
            if(printTitle) printTitle.classList.remove('hidden');

            const opt = {
                margin:       0.5,
                filename:     `${customFileName}_2026.pdf`,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
            };
            
            html2pdf().set(opt).from(element).save().then(() => {
                if(printTitle) printTitle.classList.add('hidden');
            });
        }

        function exportSpecificTabToWord(elementId, customFileName) {
            const element = document.getElementById(elementId);
            const clonedElement = element.cloneNode(true);
            clonedElement.querySelectorAll('.btn-crud-pro').forEach(btn => btn.remove());
            
            const printTitle = clonedElement.querySelector('.print-only');
            if(printTitle) printTitle.classList.remove('hidden');

            const htmlContent = clonedElement.innerHTML;
            const fullContent = `
                <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                <head><title>Ekspor Berkas Koperasi</title><style>body { font-family: Arial, sans-serif; } table { width:100%; border-collapse:collapse; } th, td { border:1px solid #ccc; padding:8px; text-align:left; }</style></head>
                <body>
                    <h2 style="text-align:center; text-transform:uppercase; font-size:14px;">Campus 'D Mart - Sistem Administrasi Resmi</h2>
                    <hr/>
                    ${htmlContent}
                </body>
                </html>
            `;
            const blob = new Blob(['\ufeff' + fullContent], { type: 'application/msword' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${customFileName}_2026.doc`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function selectCampus(campusName) {
            selectedCampus = campusName;
            document.getElementById('current-selected-campus').textContent = campusName === 'Semua Kampus' ? 'Semua Kampus' : (campusName === 'Banjarmasin' ? 'Kampus 1' : 'Kampus 2');
            document.getElementById('locationDropdownMenu').classList.add('hidden');
            filterProducts();
        }

        function filterProducts() {
            const searchKeyword = document.getElementById('search-input').value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const lokasi = card.getAttribute('data-lokasi').toLowerCase();
                const nama = card.getAttribute('data-nama');
                
                let matchLokasi = false;
                if (selectedCampus === 'Semua Kampus') matchLokasi = true;
                else if (selectedCampus === 'Banjarmasin' && (lokasi.includes('banjarmasin') || lokasi.includes('kampus 1') || lokasi.includes('k1'))) matchLokasi = true;
                else if (selectedCampus === 'Banjarbaru' && (lokasi.includes('banjarbaru') || lokasi.includes('kampus 2') || lokasi.includes('k2'))) matchLokasi = true;

                const matchSearch = nama.includes(searchKeyword);
                card.classList.toggle('hidden', !(matchLokasi && matchSearch));
            });
        }
    </script>
</body>
</html>