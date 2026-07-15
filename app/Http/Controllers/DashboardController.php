<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // 🏪 1. HALAMAN UTAMA KATALOG (Etalase Publik Pertama Kali Dibuka)
    public function index()
    {
        $products = Product::latest()->get();
        $lokasiKampus = 'Banjarmasin'; 

        // 👑 SARAN SINKRONISASI: Cek status PRO saat pertama kali masuk halaman home
        $isPro = false;
        if (Auth::check() && Auth::user()->email === 'miya44@gmail.com') {
            $isPro = true;
        }

        // Mengirimkan data dasar katalog untuk pengunjung umum/tamu beserta status lisensi
        return view('home', [
            'products' => $products, 
            'user' => Auth::user(), 
            'lokasiKampus' => $lokasiKampus,
            'isPro' => $isPro,
            'activeTab' => 'tab-katalog'
        ]);

        $promoBanners = \App\Models\Ad::where('status', 'aktif')->latest()->limit(10)->get();
    
    return view('home', [
        'products' => Product::latest()->get(),
        'promoBanners' => $promoBanners, // <--- INI WAJIB ADA
        'lokasiKampus' => 'Banjarmasin',
        'isPro' => $this->isUserPro(Auth::user()),
        'activeTab' => 'tab-katalog'
    ]);
    }

    // 📦 2. DASBOR UTAMA SELLER / TENANT (Wajib melewati proteksi login)
    public function sellerDashboard()
    {
        // Intersepsi Keamanan: Jika sesi login kosong, langsung tendang ke form login backend
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        // Memuat daftar seluruh barang dagangan untuk kebutuhan tabel kontrol CRUD
        $products = Product::latest()->get(); 
        

        // 👑 SARAN SINKRONISASI: Cek status PRO saat membuka dasbor seller
        $isPro = false;
        if ($user->email === 'miya44@gmail.com') {
            $isPro = true;
        }

        return view('home', [
            'products' => $products,
            'user' => $user,
            'lokasiKampus' => 'Banjarmasin',
            'isPro' => $isPro,
            'activeTab' => 'tab-tenant-seller' // Memerintahkan JS langsung membuka tab kontrol seller
        ]);
    }   

    // 📊 3. DASBOR UTAMA ADMIN KOPERASI (Deteksi Akun miya33 Regular vs miya44 PRO + PROTEKSI AKSES SELLER)
    public function adminDashboard()
    {
        // Intersepsi Keamanan: Jika sesi login kosong, langsung tendang ke form login backend
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Silakan login terlebih dahulu sebagai pengurus.');
        }

        $user = Auth::user();

        // 🌟 TINGKAT KEAMANAN TERTINGGI BACKEND: Jika perannya BUKAN admin dan emailnya bukan miya33 / miya44, BLOKIR & TENDANG!
        if ($user->role !== 'admin' && $user->email !== 'miya33@gmail.com' && $user->email !== 'miya44@gmail.com') {
            return redirect('/')->with('error', 'Akses ditolak! Anda bukan admin koperasi.');
        }

        $products = Product::with('user')->latest()->get();
        $tenants = User::whereIn('role', ['seller', 'dosen'])->get();

        // Algoritma menghitung akumulasi pembukuan keuangan koperasi kampus secara real-time
        $totalKasKoperasi = 0;
        foreach ($tenants as $tenant) {
            $tenantProductIds = Product::where('user_id', $tenant->id)->pluck('id');
            $omzetSelesai = Order::whereIn('product_id', $tenantProductIds)->where('status_pesanan', 'selesai')->sum('total_harga');
            $tenant->total_omzet = $omzetSelesai;
            $tenant->total_produk = $tenantProductIds->count();
            $totalKasKoperasi += $omzetSelesai;
        }

        $feeCampus = $totalKasKoperasi * 0.05;

        // 🌟 LOGIKA PENGUNCI KEAMANAN LISENSI AKUN PRO
        $isPro = false;
        if ($user->email === 'miya44@gmail.com') {
            $isPro = true; // Hanya email miya44 yang diberikan hak melihat sub-tab CaLK dan Neraca Keuangan
        }

        // Data dilempar ke 'home', lalu otomatis dialirkan masuk ke dalam file include 'koperasi'
        return view('home', [
            'user' => $user,
            'products' => $products,
            'tenants' => $tenants,
            'totalKasKoperasi' => $totalKasKoperasi,
            'feeCampus' => $feeCampus,
            'lokasiKampus' => 'Banjarmasin',
            'isPro' => $isPro, 
            'activeTab' => 'tab-admin-koperasi' // Memerintahkan JS langsung mengaktifkan tab kontrol koperasi
        ]);
    }

    // --- ✏️ ENGINE AKSI CRUD: AMBIL DATA UNTUK FORM EDIT PRODUK ---
    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('product_edit', ['product' => $product]);
    }

    // --- 💾 ENGINE AKSI CRUD: SIMPAN PERUBAHAN DATA (UPDATE) ---
    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'kategori' => 'required|string',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer',
            'lokasi_spesifik' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $product->nama_produk = $request->nama_produk;
        $product->kategori = $request->kategori;
        $product->harga_jual = $request->harga_jual;
        $product->stok = $request->stok;
        $product->lokasi_spesifik = $request->lokasi_spesifik;

        // Cek penanganan jika ada berkas foto baru yang diunggah oleh tenant
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari public directory agar server tidak penuh
            if ($product->gambar && file_exists(public_path('uploads/products/' . $product->gambar))) {
                unlink(public_path('uploads/products/' . $product->gambar));
            }
            $imageName = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $request->file('gambar')->move(public_path('uploads/products'), $imageName);
            $product->gambar = $imageName;
        }

        $product->save();
        return redirect()->route('seller.dashboard')->with('success', 'Produk berhasil diperbarui!');
    }

    // --- 🗑️ ENGINE AKSI CRUD: HAPUS PRODUK PERMANEN (DELETE) ---
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Hapus aset file gambar dari public storage lokal Windows
        if ($product->gambar && file_exists(public_path('uploads/products/' . $product->gambar))) {
            unlink(public_path('uploads/products/' . $product->gambar));
        }
        
        $product->delete();
        return redirect()->route('seller.dashboard')->with('success', 'Produk berhasil dihapus!');
    }

    public function getSalesChartData()
    {
        // Pastikan hanya seller yang bisa akses
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Ambil ID produk milik seller yang sedang login
        $productIds = Product::where('user_id', Auth::id())->pluck('id');

        // Mengambil data pesanan 7 hari terakhir
        $salesData = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as total')
            )
            ->whereIn('product_id', $productIds)
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Memetakan data agar sesuai dengan label 7 hari terakhir
        $labels = [];
        $data = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('D'); // Nama hari
            
            $dayData = $salesData->firstWhere('date', $date);
            $data[] = $dayData ? $dayData->total : 0;
        }

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
} 