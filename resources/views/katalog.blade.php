<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Antasari Mart - Katalog Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans relative overflow-x-hidden">

    <nav class="bg-green-700 text-white p-4 shadow-md flex justify-between items-center sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" class="text-2xl hover:text-green-200 transition-colors focus:outline-none" title="Buka Menu">
                🍔
            </button>
            <h1 class="text-xl font-bold tracking-wide flex items-center gap-2">🏪 Antasari Mart</h1>
        </div>
        
        <div class="flex items-center gap-2 text-xs font-semibold">
            @auth
                @if(Auth::user()->role === 'admin')
                    <span class="bg-amber-500 text-slate-900 px-3 py-1 rounded-full font-black animate-pulse">👑 ADMIN KOPERASI (FREE)</span>
                @else
                    <span class="bg-green-800 px-3 py-1 rounded-full">👤 {{ Auth::user()->name }}</span>
                @endif
            @endauth
            @guest
                <span class="bg-green-600/50 px-3 py-1 rounded-full text-green-100">Mode Pengunjung</span>
            @endguest
        </div>
    </nav>

    <main class="container mx-auto p-4 max-w-lg md:max-w-6xl">
        @if(session('success'))
            <div class="bg-green-500 text-white p-3 rounded-xl mb-4 text-sm font-bold shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        <div class="bg-gradient-to-r bg-green-600 text-white p-6 rounded-2xl shadow-sm mb-6">
            <h2 class="text-lg font-black">Selamat Datang di Antasari Mart!</h2>
            <p class="text-xs text-green-100 mt-1">Platform kreativitas & produk lokal usaha kreatif mahasiswa UIN Antasari Banjarmasin.</p>
        </div>

        <h3 class="text-md font-bold text-gray-700 mb-4">🛍️ Jelajahi Semua Produk Kampus</h3>
        
        @if($products->isEmpty())
            <div class="bg-white text-center py-12 px-4 rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">Belum ada produk jualan mahasiswa yang diunggah saat ini.</p>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col justify-between hover:shadow-md transition-shadow relative">
                        <div class="w-full h-40 bg-gray-100 overflow-hidden relative">
                            <img src="{{ asset('uploads/products/' . $product->gambar) }}?t={{ time() }}" 
                                 alt="{{ $product->nama_produk }}" 
                                 class="w-full h-full object-cover"
                                 onerror="this.onerror=null; this.src='https://placehold.co/400x400?text=Format+Salah';">
                        </div>
                        <div class="p-3">
                            <span class="text-[9px] bg-green-50 text-green-700 px-2 py-0.5 rounded-md font-bold uppercase">{{ $product->kategori }}</span>
                            <h4 class="font-bold text-sm text-gray-800 mt-1 line-clamp-2 min-h-[2.5rem]">{{ $product->nama_produk }}</h4>
                            <div class="mt-3 pt-2 border-t border-gray-50 flex items-center justify-between">
                                <p class="text-sm font-black text-green-600">Rp{{ number_format($product->harga_jual, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </main>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden transition-opacity duration-300 opacity-0"></div>

    <div id="sidebarMenu" class="fixed left-0 top-0 h-full w-72 bg-white shadow-2xl z-50 transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col justify-between">
        <div>
            <div class="bg-green-700 p-4 text-white flex justify-between items-center">
                <span class="font-black tracking-wide text-xs uppercase">📋 ANTASARI MART MENU</span>
                <button onclick="toggleSidebar()" class="font-bold hover:text-red-200 text-sm">✕ Close</button>
            </div>

            <div class="p-4">
                @guest
                    <div class="text-center py-4">
                        <p class="text-xs text-gray-500 mb-4">Masuk ke akun Anda untuk mulai mengelola sistem pasar kampus.</p>
                        <a href="/login" class="block w-full bg-green-600 text-white text-center font-bold py-2 rounded-xl text-sm shadow">🚪 Masuk / Login</a>
                    </div>
                @endguest

                @auth
                    <div class="mb-4 pb-3 border-b border-gray-100">
                        <p class="font-bold text-gray-800 text-sm">👋 Halo, {{ Auth::user()->name }}</p>
                        <span class="inline-block text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded font-black uppercase mt-1">Role: {{ Auth::user()->role }}</span>
                    </div>

                    <ul class="space-y-1 text-xs font-semibold text-gray-700">
                        <li>
                            <a href="/" class="block p-2.5 bg-green-50 text-green-700 rounded-lg font-bold">🏠 Katalog Utama</a>
                        </li>
                        
                        @if(Auth::user()->role === 'admin')
                            <li class="pt-4 pb-1 text-[10px] font-black text-slate-400 uppercase tracking-wider border-t mt-3">⚙️ PANEL UTAMA KOPERASI</li>
                            <li>
                                <a href="/admin/dashboard" class="block p-2.5 hover:bg-slate-100 rounded-lg flex items-center justify-between">
                                    <span>📦 Monitoring Stok Tenant (Free)</span>
                                    <span class="text-[9px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold">Buka</span>
                                </a>
                            </li>
                            <li>
                                <a href="/admin/dashboard" class="block p-2.5 hover:bg-slate-100 rounded-lg flex items-center justify-between">
                                    <span>📝 Catatan Keuangan CaLK (Free)</span>
                                    <span class="text-[9px] bg-green-100 text-green-700 px-1.5 py-0.5 rounded font-bold">Buka</span>
                                </a>
                            </li>

                            <li class="pt-3 pb-1 text-[10px] font-black text-amber-600 uppercase tracking-wider flex items-center gap-1">👑 FITUR INVESTASI (PRO AKUNTANSI)</li>
                            <li>
                                <button onclick="openProModal()" class="w-full text-left p-2.5 hover:bg-amber-50 text-amber-800 rounded-lg flex items-center justify-between focus:outline-none">
                                    <span>⚖️ Laporan Neraca Keuangan</span>
                                    <span class="text-[9px] bg-amber-500 text-slate-900 px-1.5 py-0.5 rounded font-black">🔒 PRO</span>
                                </button>
                            </li>
                            <li>
                                <button onclick="openProModal()" class="w-full text-left p-2.5 hover:bg-amber-50 text-amber-800 rounded-lg flex items-center justify-between focus:outline-none">
                                    <span>📈 Hasil Usaha (PHU / Laba Rugi)</span>
                                    <span class="text-[9px] bg-amber-500 text-slate-900 px-1.5 py-0.5 rounded font-black">🔒 PRO</span>
                                </button>
                            </li>
                            <li>
                                <button onclick="openProModal()" class="w-full text-left p-2.5 hover:bg-amber-50 text-amber-800 rounded-lg flex items-center justify-between focus:outline-none">
                                    <span>💸 Laporan Arus Kas (Cash Flow)</span>
                                    <span class="text-[9px] bg-amber-500 text-slate-900 px-1.5 py-0.5 rounded font-black">🔒 PRO</span>
                                </button>
                            </li>
                            <li>
                                <button onclick="openProModal()" class="w-full text-left p-2.5 hover:bg-amber-50 text-amber-800 rounded-lg flex items-center justify-between focus:outline-none">
                                    <span>🤝 Promosi Ekonomi Anggota</span>
                                    <span class="text-[9px] bg-amber-500 text-slate-900 px-1.5 py-0.5 rounded font-black">🔒 PRO</span>
                                </button>
                            </li>
                        @endif
                        
                        @if(Auth::user()->role === 'seller')
                            <li class="pt-3 text-[10px] font-bold text-gray-400 uppercase">Menu Toko</li>
                            <li><a href="/home" class="block p-2.5 hover:bg-gray-100 rounded-lg">🏪 Kelola Dasbor Toko</a></li>
                        @endif
                    </ul>
                @endauth
            </div>
        </div>

        @auth
            <div class="p-4 border-t bg-gray-50">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 rounded-xl text-xs transition-colors shadow">🚪 Keluar Sistem</button>
                </form>
            </div>
        @endauth
    </div>

    <div id="proModalOverlay" onclick="closeProModal()" class="fixed inset-0 bg-black/60 z-50 hidden opacity-0 transition-opacity duration-300"></div>

    <div id="proModalContent" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl w-11/12 max-w-lg z-50 hidden scale-95 transition-all duration-300 overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-yellow-600 p-4 text-white flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="text-lg">👑</span>
                <span class="font-black tracking-wide text-xs">AKTIVASI LISENSI PREMIUM ANTASARI MART PRO</span>
            </div>
            <button onclick="closeProModal()" class="font-bold text-white hover:text-amber-200">✕</button>
        </div>

        <div class="p-5 space-y-4 max-h-[75vh] overflow-y-auto text-xs text-gray-700">
            <div class="bg-amber-50 border border-amber-200 p-3 rounded-xl text-center">
                <p class="text-[10px] text-amber-800 font-bold uppercase tracking-wider">Biaya Sewa Fitur Premium</p>
                <p class="text-3xl font-black text-amber-600 mt-0.5">Rp500.000 <span class="text-xs text-gray-400 font-medium">/ 1 Tahun Lisensi</span></p>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-1">📋 Persyaratan Akuntansi PRO:</h4>
                <ul class="list-disc pl-5 space-y-1 text-gray-500">
                    <li>Lisensi berlaku penuh selama 365 Hari (1 Tahun).</li>
                    <li>Membuka akses penuh modul Neraca, PHU/Laba Rugi, Cash Flow, & Promosi Anggota.</li>
                    <li>Mendapatkan pembaruan sistem dan pembetulan error gratis dari developer.</li>
                </ul>
            </div>

            <div class="bg-gray-50 border p-3 rounded-xl">
                <h4 class="font-bold text-slate-800 mb-2 uppercase tracking-wide">PILIHAN METODE TRANSFER:</h4>
                <div class="space-y-1">
                    <div class="flex justify-between bg-white p-2 rounded border">
                        <span>🏦 Bank Mandiri Syariah (BSI)</span>
                        <span class="font-bold text-slate-900">7123-4567-89 (A.n Nurmiyaty)</span>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <a href="https://wa.me/628225555xxxx?text=Halo%20Nurmiyaty,%20Koperasi%20kami%20ingin%20konfirmasi%20pembayaran%20sewa%20PRO%20Antasari%20Mart%20sebesar%20Rp500.000%20untuk%201%20tahun." 
                   target="_blank" 
                   class="block text-center w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition-colors shadow">
                    💬 Kirim Bukti Transfer & Aktifkan Versi PRO via WhatsApp
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebarMenu = document.getElementById('sidebarMenu');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            if (sidebarMenu.classList.contains('-translate-x-full')) {
                sidebarMenu.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                setTimeout(() => sidebarOverlay.classList.remove('opacity-0'), 10);
            } else {
                sidebarMenu.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('opacity-0');
                setTimeout(() => sidebarOverlay.classList.add('hidden'), 300);
            }
        }

        function openProModal() {
            const overlay = document.getElementById('proModalOverlay');
            const content = document.getElementById('proModalContent');
            overlay.classList.remove('hidden');
            content.classList.remove('hidden');
            setTimeout(() => {
                overlay.classList.remove('opacity-0');
                content.classList.remove('scale-95');
            }, 10);
        }

        function closeProModal() {
            const overlay = document.getElementById('proModalOverlay');
            const content = document.getElementById('proModalContent');
            overlay.classList.add('opacity-0');
            content.classList.add('scale-95');
            setTimeout(() => {
                overlay.classList.add('hidden');
                content.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>