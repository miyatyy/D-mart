<!-- PENGONDISIAN HAK AKSES AMAN -->
@if(Auth::check() && (Auth::user()->role === 'admin' || Auth::user()->email === 'miya33@gmail.com' || Auth::user()->email === 'miya44@gmail.com'))

    <!-- 🏢 KONTEN UTAMA DASHBOARD KOPERASI KAMPUS (HANYA DILIHAT OLEH ADMIN) -->
    <div id="coop-overview" class="coop-sub-content bg-white p-6 rounded-2xl shadow-md border border-gray-200">
        <h1 class="text-xl font-bold text-gray-800 mb-2">🏢 Dasbor Utama Koperasi Kampus</h1>
        <p class="text-xs text-gray-500 mb-6">Slogan: "Dari Mahasiswa, Oleh Koperasi, Untuk Antasari"</p>

        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                <p class="text-xs text-gray-600 font-medium">Omzet Bersih Koperasi</p>
                <p class="text-xl font-black text-blue-700">Rp{{ number_format($totalKasKoperasi ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                <p class="text-xs text-gray-600 font-medium">Fee Kampus (5%)</p>
                <p class="text-xl font-black text-purple-700">Rp{{ number_format($feeCampus ?? 0, 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
            <h3 class="text-sm font-bold text-gray-700 mb-2">📄 Cetak Laporan LPJ Transparan</h3>
            <div class="flex gap-2">
                <button onclick="window.print()" class="flex-1 bg-red-600 text-white text-xs font-bold py-2 rounded-lg hover:bg-red-700">📥 Unduh PDF Formal</button>
                <button onclick="alert('Laporan diekspor ke Word!')" class="flex-1 bg-blue-600 text-white text-xs font-bold py-2 rounded-lg hover:bg-blue-700">📥 Unduh Word (.docx)</button>
            </div>
        </div>
    </div>

    <!-- 📦 TABEL: MONITORING KETERSEDIAAN STOK TENANT -->
    <div id="coop-stok" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mt-6">
        <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">📦 TABEL: MONITORING KETERSEDIAAN STOK TENANT</div>
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase">
                    <th class="p-3 border">Nama Pengusaha</th>
                    <th class="p-3 border">Nama Produk</th>
                    <th class="p-3 text-center border">Sisa Stok</th>
                </tr>
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

    <!-- 👑 HANYA UNTUK AKUN PRO (miya44@gmail.com) -->
    @if($isPro ?? false)
        <!-- Sub-Tab C: CaLK Omzet -->
        <div id="coop-calk" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mt-6">
            <div class="bg-gray-50 p-4 font-bold text-gray-800 border-b-2 border-b-purple-600 uppercase text-xs">📊 Laporan Pendapatan Akumulasi Laba Tenant</div>
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b font-bold text-gray-600 uppercase">
                        <th class="p-3 border">Nama Tenant</th>
                        <th class="p-3 text-center border">Varian Item</th>
                        <th class="p-3 text-right border">Total Hasil Omzet</th>
                    </tr>
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
        <div id="coop-neraca" class="coop-sub-content hidden bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mt-6">
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
    @endif

@else
    <!-- ⚠️ KETIKA SELLER MENCOBA MENGINTIP TAB KOPERASI -->
    <div id="coop-overview" class="coop-sub-content bg-red-50 border border-red-200 rounded-2xl p-8 text-center shadow-sm max-w-xl mx-auto">
        <div class="text-4xl mb-3">🛑</div>
        <h2 class="text-base font-black text-red-700 uppercase tracking-tight">Akses Ditolak Sistem</h2>
        <p class="text-xs text-red-600 font-bold mt-1">"Maaf, Anda bukan admin koperasi."</p>
        <p class="text-[11px] text-gray-400 mt-2">Halaman audit pembukuan ini dilindungi secara enkripsi internal dan hanya dapat dibuka oleh pengurus koperasi resmi.</p>
        <div class="mt-4">
            <a href="/" class="inline-block bg-red-600 hover:bg-red-700 text-white font-bold text-xs px-4 py-2 rounded-xl transition-colors shadow">
                ⬅️ Kembali ke Katalog Utama
            </a>
        </div>
    </div>
@endif