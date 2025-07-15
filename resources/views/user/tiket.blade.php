@extends('layouts.user')

@section('content')
<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fas fa-ship text-2xl text-blue-600"></i>
                <h1 class="text-2xl font-bold text-gray-900">Sub<span class="text-blue-500">sea</span></h1>
            </div>
            <div class="flex items-center">
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="/tiket" class="text-gray-700 hover:text-blue-600">Tiket</a>
                    <a class="bg-red-500 px-3 py-1.5 text-white shadow-md font-bold rounded-lg cursor-pointer" id="logout" onclick="logout()">Logout</a>
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

<main class="max-w-6xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Riwayat Tiket</h1>

    <div id="loading" class="text-center py-10">
        <div class="animate-spin h-8 w-8 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-4"></div>
        <p class="text-gray-500">Mengambil data tiket...</p>
    </div>

    <div id="ticketList" class="grid gap-6 md:grid-cols-2 hidden"></div>
</main>

<!-- Modal Tiket -->
<div id="ticketModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center px-4">
    <div class="bg-white max-w-lg w-full rounded-lg p-6 relative">
        <button id="closeModal" class="absolute top-3 right-4 text-gray-500 hover:text-black text-xl">&times;</button>
        <h2 class="text-xl font-bold mb-4 text-gray-800">Detail Tiket</h2>
        <div class="grid grid-cols-2 gap-4 text-sm text-gray-700">
            <div class="flex items-center gap-x-1"><span class="font-medium">ID Tiket:</span>
                <div id="modal_kapal">-</div>
            </div>
            <div class="flex items-center gap-x-1"><span class="font-medium">Tanggal:</span>
                <div id="modal_waktu">-</div>
            </div>
            <div class="flex items-center gap-x-1"><span class="font-medium">Nama:</span>
                <div id="modal_nama">-</div>
            </div>
            <div class="flex items-center gap-x-1"><span class="font-medium">Jumlah Tiket:</span>
                <div id="modal_jumlah">-</div>
            </div>
            <div class="flex items-center gap-x-1"><span class="font-medium">Total Harga:</span>
                <div id="modal_total">-</div>
            </div>
            <div class="flex items-center gap-x-1"><span class="font-medium">Status Verifikasi:</span>
                <span id="modal_verifikasi" class="inline-block text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-700">-</span>
            </div>
        </div>
        <div class="pt-6 text-right">
            <button id="btnDownload" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2 rounded-md cursor-pointer">
                Unduh Tiket
            </button>
        </div>
    </div>
</div>

<!-- Hidden Area for PNG Download -->
<div id="downloadArea" class="hidden p-6 w-[600px] bg-white rounded shadow text-gray-800 text-sm">
    <h2 class="text-lg font-bold mb-4">Tiket Digital</h2>
    <p><strong>ID Tiket:</strong> <span id="img_id">-</span></p>
    <p><strong>Tanggal:</strong> <span id="img_tanggal">-</span></p>
    <p><strong>Jumlah Tiket:</strong> <span id="img_jumlah">-</span></p>
    <p><strong>Total Harga:</strong> <span id="img_total">-</span></p>
    <p><strong>Status Verifikasi:</strong> <span id="img_status">-</span></p>
    <p><strong>Metode Pembayaran:</strong> <span id="img_metode">-</span></p>
</div>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const riwayat = document.getElementById('riwayat-pemesanan')
        const loginBtn = document.getElementById('login')
        const logoutBtn = document.getElementById('logout')
        const token = localStorage.getItem('token');

        if (token) {
            loginBtn.classList.add('hidden')
            logoutBtn.classList.add('block')
        } else {
            loginBtn.classList.add('block')
            logoutBtn.classList.add('hidden')
        }


        // === ELEMEN DOM ===
        const ticketList = document.getElementById('ticketList');
        const loading = document.getElementById('loading');
        const modal = document.getElementById('ticketModal');
        const closeModalBtn = document.getElementById('closeModal');

        const user = localStorage.getItem('user')
        const username = JSON.parse(user)


        // === DATA & STATE ===
        let ticketsData = []; // Simpan data tiket di sini

        // === FUNGSI-FUNGSI ===
        const formatRupiah = val => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(val);

        const openModal = (ticketId) => {
            const data = ticketsData.find(t => t.id === ticketId);
            if (!data) return;

            document.getElementById('modal_kapal').textContent = `Tiket #${data.id}`;
            document.getElementById('modal_nama').textContent = `Nama ${username.name}`;
            document.getElementById('modal_waktu').textContent = data.tanggal;
            document.getElementById('modal_jumlah').textContent = data.jumlah_tiket;
            document.getElementById('modal_total').textContent = formatRupiah(data.total_harga);

            const badge = document.getElementById('modal_verifikasi');
            badge.textContent = data.status_verifikasi;
            badge.className = 'inline-block text-xs font-semibold px-3 py-1 rounded-full'; // Reset class
            if (data.status_verifikasi === 'diterima') badge.classList.add('bg-green-100', 'text-green-700');
            else if (data.status_verifikasi === 'menunggu') badge.classList.add('bg-yellow-100', 'text-yellow-700');
            else badge.classList.add('bg-red-100', 'text-red-700');

            // Data untuk diunduh
            document.getElementById('img_id').textContent = `#${data.id}`;
            document.getElementById('img_tanggal').textContent = data.tanggal;
            // ... isi sisa data untuk diunduh ...

            document.getElementById('btnDownload').onclick = () => downloadTiketAsPNG();
            modal.classList.remove('hidden');
        };

        const downloadTiketAsPNG = () => {
            const area = document.getElementById('downloadArea');
            area.classList.remove('hidden');
            html2canvas(area).then(canvas => {
                const link = document.createElement('a');
                link.download = `tiket-${document.getElementById('img_id').textContent}.png`;
                link.href = canvas.toDataURL();
                link.click();
                area.classList.add('hidden');
            });
        };

        const setupEventListeners = () => {
            // Tambahkan event listener ke semua tombol "Lihat Detail"
            document.querySelectorAll('.btn-view-detail').forEach(button => {
                button.addEventListener('click', () => {
                    const ticketId = parseInt(button.dataset.ticketId);
                    openModal(ticketId);
                });
            });
        };

        // === FUNGSI UTAMA (INIT) ===
        // Ganti fungsi fetchTickets() yang lama dengan yang ini

        async function fetchTickets() {
            console.log('1. Memulai fungsi fetchTickets...'); // LOG 1

            try {
                console.log('2. Mengirim request ke API...'); // LOG 2
                const res = await fetch('/api/user/pembayarans', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
                console.log('3. Menerima respons dari API:', res); // LOG 3

                if (!res.ok) {
                    // Jika status bukan 2xx (misal 401, 404, 500)
                    console.error('Respon tidak OK!', res.status, res.statusText);
                    throw new Error('Gagal memuat data tiket. Status: ' + res.status);
                }

                console.log('4. Mencoba parsing respons sebagai JSON...'); // LOG 4
                const data = await res.json();
                console.log('5. Data berhasil di-parse:', data); // LOG 5

                ticketsData = data;

                loading.classList.add('hidden');
                ticketList.classList.remove('hidden');

                if (data.length === 0) {
                    ticketList.innerHTML = `<p class="text-center col-span-full text-gray-600">Anda belum memiliki riwayat tiket.</p>`;
                    return;
                }

                // ... (sisa kode untuk render tiket tidak berubah) ...
                const today = new Date().toISOString().split('T')[0];
                ticketList.innerHTML = data.map(item => {
                    const isExpired = item.tanggal < today;
                    const label = isExpired ? 'Kadaluarsa' : 'Aktif';
                    const labelColor = isExpired ? 'red' : 'green';

                    return `
                <div class="rounded-lg p-4 bg-white shadow-md flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Tiket Id ${item.id}</h3>
                        <p class="text-sm text-gray-600">Nama: ${username.name}</p>
                        <p class="text-sm text-gray-600">Tanggal: ${item.tanggal}</p>
                        <p class="text-sm text-gray-600">Jumlah: ${item.jumlah_tiket} tiket</p>
                        <span class="inline-block mt-2 text-xs font-semibold px-3 py-1 rounded-full bg-${labelColor}-100 text-${labelColor}-700">
                            ${label}
                        </span>
                    </div>
                    <div class="text-right mt-4">
                        <button class="btn-view-detail cursor-pointer text-sm px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md font-semibold" data-ticket-id="${item.id}">
                            Lihat Detail
                        </button>
                    </div>
                </div>
            `;
                }).join('');

                setupEventListeners();


            } catch (e) {
                console.error('❌ Terjadi error di blok catch:', e); // LOG ERROR
                loading.innerHTML = `<p class="text-red-500 font-semibold">${e.message}</p>`;
            }
        }
        // === EKSEKUSI AWAL ===
        if (!token) {
            window.location.href = '/login';
        } else {
            // Toggle tombol login/logout
            loginBtn.classList.add('hidden');
            logoutBtn.classList.remove('hidden');
            // Ambil data tiket
            fetchTickets();
        }

        // Event listener untuk modal
        closeModalBtn.addEventListener('click', () => modal.classList.add('hidden'));
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.add('hidden');
        });
    });

    function logout() {
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        localStorage.removeItem('id');
        localStorage.removeItem('role');
        window.location.href = '/login';
    }
</script>