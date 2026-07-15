<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus 'D Mart - Dasbor Tenant Jualan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-sm text-gray-700">

    <!-- HEADER ATAS -->
    <header class="bg-white border-b border-gray-200 py-4 px-4">
        <div class="container mx-auto max-w-6xl flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-purple-600 tracking-tight">🏪 Campus 'D Mart</h1>
                <p class="text-xs text-gray-400 font-medium">Dasbor Manajemen Lapak Wirausaha Mahasiswa</p>
            </div>
            <form action="/logout" method="POST" class="inline">
                @csrf
                <button type="submit" class="text-red-600 hover:underline font-bold text-xs">🚪 Keluar</button>
            </form>
        </div>
    </header>

    <!-- HORIZONTAL NAV -->
    <nav class="bg-purple-600 text-white shadow">
        <div class="container mx-auto max-w-6xl flex text-xs font-bold uppercase tracking-wide">
            <a href="/" class="hover:bg-purple-700 px-5 py-3.5 transition-colors">HOME / KEMBALI KE PASAR</a>
            <a href="#" class="bg-purple-800 px-5 py-3.5 transition-colors">📦 PANEL TENANT SELLER</a>
        </div>
    </nav>

    <!-- CONTENT CONTAINER -->
    <main class="container mx-auto max-w-6xl p-4 grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- SIDEBAR KIRI: DATA PROFIL SELLER DENGAN LOGO MATA INTIP -->
        <aside class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 h-fit">
            <div class="border-b pb-2 mb-4">
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">👤 Profil Toko Tenant</h3>
            </div>
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Nama Pemilik Usaha</label>
                    <input type="text" value="{{ $user->name }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">NIM Pengusaha</label>
                    <input type="text" value="{{ $user->nim ?? 'Belum Diisi' }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Email Komersial Lapak</label>
                    <input type="email" value="{{ $user->email }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Kata Sandi Lapak</label>
                    <div class="relative flex items-center">
                        <input type="password" id="passSeller" value="passwordseller456" readonly class="w-full bg-gray-50 border p-2 pr-10 rounded font-bold text-gray-700 focus:outline-none">
                        <button type="button" onclick="togglePassword('passSeller', 'eyeIconSeller')" class="absolute right-3 text-gray-500 focus:outline-none">
                            <span id="eyeIconSeller">👁️</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN KANAN: MANAJEMEN DAGANGAN & PESANAN MASUK -->
        <section class="lg:col-span-3 space-y-6">
            @if(session('success'))
                <div class="bg-purple-600 text-white p-3 rounded-lg text-xs font-bold shadow-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif

            <!-- Banner Akumulasi Keuntungan Bersih -->
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200">
                    <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Keuntungan Bersih Lapak</p>
                    <p class="text-xl font-black text-purple-600 mt-1">Rp{{ number_format($totalKeuntungan, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">*Berdasarkan nota selesai</p>
                </div>
                <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">Total Etalase Jualan</p>
                        <p class="text-xl font-black text-amber-500 mt-1">{{ $myProducts->count() }} Barang</p>
                    </div>
                    <a href="/product/create" class="text-center bg-purple-600 hover:bg-purple-700 text-white text-[10px] font-black py-1 rounded transition-colors uppercase tracking-wider block mt-2">
                        ➕ Tambah Barang Baru
                    </a>
                </div>
            </div>

            <!-- Tabel Pesanan Masuk (Bawaan Codingan Awal yang Disinkronkan Warnanya) -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                <div class="bg-gray-50 p-3 font-bold text-gray-800 border-b border-b-purple-600 text-xs uppercase tracking-wider">
                    📦 Daftar Pesanan Masuk dari Pelanggan Kampus
                </div>
                @if($incomingOrders->isEmpty())
                    <div class="bg-white text-center py-8 px-4 text-gray-400 text-xs">Belum ada pesanan masuk untuk tokomu saat ini.</div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-gray-200 font-bold text-gray-600 uppercase">
                                    <th class="p-3">Pembeli</th>
                                    <th class="p-3">Produk</th>
                                    <th class="p-3">Metode</th>
                                    <th class="p-3">Status</th>
                                    <th class="p-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 font-medium text-gray-700">
                                @foreach($incomingOrders as $order)
                                    <tr>
                                        <td class="p-3">
                                            <span class="font-bold text-gray-900 block">{{ $order->pembeli->name }}</span>
                                            <span class="text-[10px] text-gray-400">NIM: {{ $order->pembeli->nim ?? '-' }}</span>
                                        </td>
                                        <td class="p-3">
                                            <span class="block">{{ $order->product->nama_produk ?? 'Produk Dihapus' }}</span>
                                            <span class="block font-black text-purple-600">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="p-3"><span class="bg-purple-50 text-purple-700 px-2 py-0.5 rounded font-bold">{{ $order->metode_pembayaran }}</span></td>
                                        <td class="p-3">
                                            <span class="px-2 py-0.5 text-[10px] font-bold rounded uppercase
                                                {{ $order->status_pesanan === 'menunggu_konfirmasi' ? 'bg-amber-100 text-amber-800' : '' }}
                                                {{ $order->status_pesanan === 'diproses' ? 'bg-orange-100 text-orange-800' : '' }}
                                                {{ $order->status_pesanan === 'selesai' ? 'bg-green-100 text-green-800' : '' }}
                                            ">
                                                {{ str_replace('_', ' ', $order->status_pesanan) }}
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <form action="/order/update-status/{{ $order->id }}" method="POST" class="flex gap-1 items-center">
                                                @csrf
                                                <select name="status" class="p-1 border border-gray-300 rounded text-[11px] focus:outline-none">
                                                    <option value="menunggu_konfirmasi" {{ $order->status_pesanan === 'menunggu_konfirmasi' ? 'selected' : '' }}>Menunggu</option>
                                                    <option value="diproses" {{ $order->status_pesanan === 'diproses' ? 'selected' : '' }}>Proses</option>
                                                    <option value="selesai" {{ $order->status_pesanan === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                </select>
                                                <button type="submit" class="bg-purple-600 text-white text-[10px] px-2 py-1.5 rounded font-bold hover:bg-purple-700">Set</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </section>
    </main>

    <script>
        function togglePassword(inputId, eyeId) {
            const input = document.getElementById(inputId);
            const eye = document.getElementById(eyeId);
            if (input.type === "password") {
                input.type = "text";
                eye.textContent = "🔒";
            } else {
                input.type = "password";
                eye.textContent = "👁️";
            }
        }
    </script>
</body>
</html>