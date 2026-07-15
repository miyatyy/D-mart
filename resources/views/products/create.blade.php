<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Campus 'D Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <nav class="bg-purple-700 text-white p-4 shadow-md mb-6">
        <div class="container mx-auto flex justify-between items-center max-w-lg">
            <h1 class="text-xl font-bold">➕ Tambah Produk</h1>
            <a href="/home" class="text-sm bg-purple-800 hover:bg-purple-900 px-3 py-1 rounded-full transition-colors font-medium">
                ← Kembali
            </a>
        </div>
    </nav>

    <main class="container mx-auto p-4 max-w-lg">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
            
            @if($errors->any())
                <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg mb-4 border border-red-200 font-medium">
                    ⚠️ {{ $errors->first() }}
                </div>
            @endif
            
            <form action="/product/store" method="POST" enctype="multipart/form-data">
                @csrf 

                <!-- Nama Produk -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-xs font-bold mb-1">Nama Produk</label>
                    <input type="text" name="nama_produk" value="{{ old('nama_produk') }}" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" placeholder="Contoh: Buket Jilbab Polos" required>
                </div>

                <!-- Harga -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1">Harga Modal (Rp)</label>
                        <input type="number" name="harga_modal" value="{{ old('harga_modal') }}" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="harga_jual" value="{{ old('harga_jual') }}" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" required>
                    </div>
                </div>

                <!-- Stok & Kategori -->
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1">Stok Barang</label>
                        <input type="number" name="stok" value="{{ old('stok') }}" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-xs font-bold mb-1">Kategori</label>
                        <select name="kategori" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" required>
                            <option value="aksesoris">Aksesoris / Kerajinan</option>
                            <option value="makanan">Makanan / Minuman</option>
                            <option value="jasa">Jasa / Cetak Tugas</option>
                            <option value="koperasi">Atribut Koperasi</option>
                        </select>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="mb-4">
                    <label class="block text-gray-700 text-xs font-bold mb-1">Lokasi Detail Pengambilan</label>
                    <input type="text" name="lokasi_spesifik" value="{{ old('lokasi_spesifik') }}" class="w-full p-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-purple-600" placeholder="Contoh: Gedung Tarbiyah Lt. 2" required>
                </div>

                <!-- Foto -->
                <div class="mb-6">
                    <label class="block text-gray-700 text-xs font-bold mb-1">Foto Produk</label>
                    <input type="file" name="gambar" accept="image/*" class="w-full p-2 border border-gray-200 rounded-lg text-sm bg-gray-50 cursor-pointer" required>
                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG/PNG (Maks 2MB).</p>
                </div>

                <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 rounded-lg transition-all shadow-md">
                    Simpan Produk
                </button>
            </form>
        </div>
    </main>

</body>
</html>