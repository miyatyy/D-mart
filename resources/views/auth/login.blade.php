<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Antasari Mart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-200 w-full max-w-sm">
        <h2 class="text-2xl font-black text-green-700 text-center mb-1">🛒 Antasari Mart</h2>
        <p class="text-xs text-gray-500 text-center mb-6">"Dari Mahasiswa, Oleh Koperasi, Untuk Antasari"</p>

        @if($errors->any())
            <div class="bg-red-50 text-red-600 text-xs p-3 rounded-lg mb-4 border border-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-xs font-bold mb-1">NIM atau Email</label>
                <input type="text" name="login_input" class="w-full p-2.5 text-sm border border-gray-300 rounded-lg focus:border-green-600 outline-none" placeholder="Masukkan NIM atau Email Anda" autocomplete="off" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-xs font-bold mb-1">Password</label>
                <div class="relative flex items-center">
                    <input type="password" id="password" name="password" class="w-full p-2.5 pr-10 text-sm border border-gray-300 rounded-lg focus:border-green-600 outline-none" placeholder="Masukkan password" required>
                    <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon')" class="absolute right-3 text-gray-400 hover:text-gray-600 focus:outline-none text-sm">
                        <span id="eye-icon">👁️</span>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-lg text-sm transition-colors shadow mb-4">
                Masuk ke Sistem
            </button>
        </form>

        <div class="text-center">
            <p class="text-xs text-gray-500">Belum punya akun? <a href="/register" class="text-green-600 font-bold hover:underline">Daftar Akun Baru</a></p>
        </div>
    </div>

    <script>
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