<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Antasari Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center min-h-screen p-4">
    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 w-full max-w-sm">
        <h2 class="text-xl font-black text-green-700 text-center mb-1">🛒 Antasari Mart</h2>
        <p class="text-xs text-gray-500 text-center mb-6">Mulai ekosistem digital mandiri kampusmu</p>

        @if($errors->any())
    <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg mb-4 border border-red-200">
        {{ $errors->first() }}
    </div>
@endif

        <form action="/register" method="POST">
            @csrf

            <div class="mb-3" id="nim-field">
                <label class="block text-gray-700 text-xs font-bold mb-1">NIM Mahasiswa</label>
                <input type="text" id="nim-input" name="nim" class="w-full p-2 text-sm border rounded-lg focus:border-green-600 outline-none" placeholder="Contoh: 2301040..." required>
            </div>

            <div class="mb-3">
                <label class="block text-gray-700 text-xs font-bold mb-1">Nama Lengkap</label>
                <input type="text" name="name" class="w-full p-2 text-sm border rounded-lg focus:border-green-600 outline-none" placeholder="Nama Lengkap" required>
            </div>
            
            <div class="mb-3">
                <label class="block text-gray-700 text-xs font-bold mb-1">Email</label>
                <input type="email" name="email" class="w-full p-2 text-sm border rounded-lg focus:border-green-600 outline-none" placeholder="name@example.com" required>
            </div>
            
            <div class="mb-3">
                <label class="block text-gray-700 text-xs font-bold mb-1">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="reg-password" name="password" class="w-full p-2 pr-10 text-sm border rounded-lg focus:border-green-600 outline-none" placeholder="Min 6 Karakter" required>
                    <button type="button" onclick="togglePasswordVisibility('reg-password', 'reg-eye-icon')" class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none text-sm">
                        <span id="reg-eye-icon">👁️</span>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-2 mb-6">
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1">Daftar Sebagai</label>
                    <select id="role-select" name="role" onchange="checkRole()" class="w-full p-2 text-sm border rounded-lg outline-none" required>
                        <option value="pembeli">Pembeli</option>
                        <option value="seller">Tenant Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                        <option value="admin">Admin Koperasi</option> </select>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 text-xs font-bold mb-1">Lokasi Kampus</label>
                    <select name="lokasi_kampus" class="w-full p-2 text-sm border rounded-lg outline-none" required>
                        <option value="Banjarmasin">Banjarmasin</option>
                        <option value="Banjarbaru">Banjarbaru</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 rounded-lg text-sm transition-colors shadow">
                Daftar Akun
            </button>
        </form>
    </div>

    <script>
        // Fungsi deteksi Role Dosen untuk hilangkan NIM
        function checkRole() {
            const roleSelect = document.getElementById('role-select');
            const nimField = document.getElementById('nim-field');
            const nimInput = document.getElementById('nim-input');

            if (roleSelect.value === 'dosen') {
                nimField.style.display = 'none';
                nimInput.removeAttribute('required');
                nimInput.value = ''; 
            } else {
                nimField.style.display = 'block';
                nimInput.setAttribute('required', 'required');
            }
        }

        function togglePasswordVisibility(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerText = "🔒";
            } else {
                passwordInput.type = "password";
                eyeIcon.innerText = "👁️";
            }
        }
    </script>
</body>
</html>