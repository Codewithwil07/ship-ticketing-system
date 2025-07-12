<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>


    <script>
        // --- Fungsi untuk memvalidasi dan mendapatkan token (di sisi Blade) ---
        function getAndValidateToken() {
            const token = localStorage.getItem('token');
            const role = localStorage.getItem('role'); // Ambil role juga

            if (!token) {
                console.log('No token found in localStorage.');
                return null; // Tidak ada token
            }

            try {
                // Decode bagian payload (bagian kedua JWT)
                const payloadBase64 = token.split('.')[1];
                const decodedPayload = JSON.parse(atob(payloadBase64));

                // Ambil waktu kadaluarsa (exp) dan konversi ke milidetik
                const expirationTime = decodedPayload.exp * 1000;

                // Periksa apakah token sudah kadaluarsa
                if (Date.now() >= expirationTime) {
                    console.warn('Token has expired. Removing from localStorage.');
                    localStorage.removeItem('token');
                    localStorage.removeItem('role'); // Hapus role juga
                    localStorage.removeItem('user'); // Hapus data user jika ada
                    return null; // Token kadaluarsa
                }

                // Periksa apakah role ada dan cocok (jika ini untuk rute admin)
                if (!role || role !== 'admin') {
                    console.warn('Role not found or not admin. Removing token.');
                    localStorage.removeItem('token');
                    localStorage.removeItem('role');
                    localStorage.removeItem('user');
                    return null; // Bukan admin
                }

                return token; // Token valid dan role cocok
            } catch (error) {
                console.error('Error decoding or validating token:', error);
                localStorage.removeItem('token');
                localStorage.removeItem('role');
                localStorage.removeItem('user');
                return null; // Token rusak
            }
        }

        // --- Logika Redirect di awal load halaman ---
        const validToken = getAndValidateToken();

        // Jika tidak ada token yang valid atau bukan admin, redirect ke login
        if (!validToken) {
            // Ini akan memicu redirect jika token tidak ada, kadaluarsa, atau role bukan admin
            window.location.href = '/login';
        }
        // Jika token valid dan role admin, biarkan halaman dimuat
    </script>
</head>

<body class="bg-gray-100 text-gray-800">
    @include('components.admin.navbar')
    <div class="flex">
        @include('components.admin.sidebar')
        <main class="p-6 w-full">
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>

</html>