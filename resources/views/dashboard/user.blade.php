<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus 'D Mart - Dasbor Pembeli</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-sm text-gray-700">

    <!-- HEADER ATAS -->
    <header class="bg-white border-b border-gray-200 py-4 px-4">
        <div class="container mx-auto max-w-6xl flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black text-purple-600 tracking-tight">🏪 Campus 'D Mart</h1>
                <p class="text-xs text-gray-400 font-medium">Ruang Kontrol Akun Belanja Pembeli Kampus</p>
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
            <a href="#" class="bg-purple-800 px-5 py-3.5 transition-colors">🛒 PANEL PEMBELI</a>
        </div>
    </nav>

    <!-- GRID SPLIT UTAMA -->
    <main class="container mx-auto max-w-6xl p-4 grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <!-- SIDEBAR KIRI: DATA DATA PROFIL DENGAN LOGO MATA INTIP -->
        <aside class="bg-white rounded-lg border border-gray-200 shadow-sm p-4 h-fit">
            <div class="border-b pb-2 mb-4">
                <h3 class="font-bold text-gray-800 uppercase text-xs tracking-wider">👤 Identitas Akun</h3>
            </div>
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ $user->name }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">NIM Mahasiswa</label>
                    <input type="text" value="{{ $user->nim ?? 'Belum Diisi' }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Alamat Email</label>
                    <input type="email" value="{{ $user->email }}" readonly class="w-full bg-gray-50 border p-2 rounded font-bold text-gray-700 focus:outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 font-semibold mb-1">Kata Sandi (Password)</label>
                    <div class="relative flex items-center">
                        <input type="password" id="passUser" value="passwordpembeli123" readonly class="w-full bg-gray-50 border p-2 pr-10 rounded font-bold text-gray-700 focus:outline-none">
                        <button type="button" onclick="togglePassword('passUser', 'eyeIconUser')" class="absolute right-3 text-gray-500 focus:outline-none">
                            <span id="eyeIconUser">👁️</span>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <!-- KONTEN KANAN: RIWAYAT PESANAN BARANG -->
        <section class="lg:col-span-3 bg-white rounded-lg border border-gray-200 shadow-sm p-4">
            <h3 class="font-bold text-gray-800 uppercase text-xs border-b pb-2 mb-4">🛍️ Riwayat Nota Pembelian Barang Kamu</h3>
            @if(empty($myOrders) || $myOrders->isEmpty())
                <div class="bg-gray-50 p-8 rounded-lg text-center text-gray-400 text-xs border border-dashed">
                    Kamu belum melakukan transaksi pemesanan produk jualan apa pun di lingkungan kampus.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-gray-50 font-bold border-b text-gray-600 uppercase">
                                <th class="p-3 border">Nama Barang</th>
                                <th class="p-3 text-right border">Harga Bayar</th>
                                <th class="p-3 text-center border">Status Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y font-medium text-gray-700">
                            @foreach($myOrders as $order)
                                <tr>
                                    <td class="p-3 text-purple-700 font-bold border">{{ $order->product->nama_produk ?? 'Barang Dihapus' }}</td>
                                    <td class="p-3 text-right font-black border">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center border">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-purple-100 text-purple-700">
                                            {{ str_replace('_', ' ', $order->status_pesanan) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
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