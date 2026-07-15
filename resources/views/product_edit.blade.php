<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Campus 'D Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-xs text-gray-700 p-6">

    <div class="max-w-xl mx-auto bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="bg-purple-600 p-4 text-white">
            <h2 class="text-sm font-black uppercase">✏️ Form Perubahan Data Produk</h2>
            <p class="text-[10px] text-purple-200">Perbarui informasi detail barang dagangan tenant Anda.</p>
        </div>

        <form action="/product/update/{{ $product->id }}" method="POST" enctype="multipart/form-data" class="p-4 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-bold text-gray-700 mb-1">Nama Produk / Jualan</label>
                <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" required class="w-full border p-2 rounded-lg bg-gray-50 focus:outline-none focus:border-purple-500 font-semibold">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Kategori</label>
                    <select name="kategori" required class="w-full border p-2 rounded-lg bg-gray-50 focus:outline-none focus:border-purple-500 font-semibold">
                        <option value="Kuliner" {{ $product->kategori == 'Kuliner' ? 'selected' : '' }}>🍔 Kuliner & Camilan</option>
                        <option value="Kerajinan" {{ $product->kategori == 'Kerajinan' ? 'selected' : '' }}>🎨 Kerajinan & Kawat Bulu</option>
                        <option value="Aksesoris" {{ $product->kategori == 'Aksesoris' ? 'selected' : '' }}>💎 Aksesoris & Souvenir</option>
                    </select>
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Ketersediaan Stok</label>
                    <input type="number" name="stok" value="{{ $product->stok }}" required class="w-full border p-2 rounded-lg bg-gray-50 text-center font-bold">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block font-bold text-gray-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" value="{{ $product->harga_jual }}" required class="w-full border p-2 rounded-lg bg-gray-50 text-right font-black text-purple-600">
                </div>
                <div>
                    <label class="block font-bold text-gray-700 mb-1">📍 Lokasi Spesifik Kampus</label>
                    <input type="text" name="lokasi_spesifik" value="{{ $product->lokasi_spesifik }}" placeholder="Contoh: gd tarbiyah lt.3" required class="w-full border p-2 rounded-lg bg-gray-50 font-semibold">
                </div>
            </div>

            <div>
                <label class="block font-bold text-gray-700 mb-1">Foto Thumbnail Produk</label>
                <div class="flex items-center gap-3">
                    @if($product->gambar)
                        <img src="{{ asset('uploads/products/' . $product->gambar) }}" class="w-14 h-14 object-cover rounded-lg border">
                    @endif
                    <input type="file" name="gambar" class="text-[11px] block w-full file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                </div>
                <p class="text-[10px] text-gray-400 mt-1">*Kosongkan jika tidak ingin mengganti gambar produk lama.</p>
            </div>

            <div class="border-t pt-4 flex gap-2">
                <a href="/home" class="flex-1 bg-gray-200 text-gray-700 font-bold py-2 rounded-xl text-center hover:bg-gray-300 transition-colors">Batal</a>
                <button type="submit" class="flex-1 bg-purple-600 text-white font-bold py-2 rounded-xl hover:bg-purple-700 shadow transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>

</body>
</html>