<header class="bg-white shadow-sm border-b">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fas fa-ship text-2xl text-blue-600"></i>
                <h1 class="text-2xl font-bold text-gray-900">Sub<span class="text-blue-500">sea</span></h1>
            </div>
            <div class="flex items-center">
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="#" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600" id="riwayat-pemesanan">Riwayat Reservasi</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600" id="">Tiket</a>
                    <a class="bg-red-500 px-3 py-1.5 text-white shadow-md font-bold rounded-lg" id="logout" onclick="logout()">Logout</a>
                </nav>
                <a href="/login" id="login">
                    <button class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 cursor-pointer shadow-md font-bold">
                        Masuk
                    </button>
                </a>
            </div>
        </div>
    </div>
</header>


<script>
    if (token) {
        loginBtn.classList.add('hidden')
        logoutBtn.classList.add('block')
    } else {
        loginBtn.classList.add('block')
        logoutBtn.classList.add('hidden')
    }
</script>