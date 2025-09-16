@extends('layouts.user')
@section('content')
<header class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
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
            <button id="btnDownload" class="bg-emerald-600 hidden hover:bg-emerald-700 text-white text-sm font-bold px-4 py-2 rounded-md cursor-pointer">
                Unduh Tiket
            </button>
        </div>
    </div>
</div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const loginBtn = document.getElementById('login')
        const logoutBtn = document.getElementById('logout')
        const token = localStorage.getItem('token');

        if (token) {
            loginBtn.classList.add('hidden')
            logoutBtn.classList.remove('hidden')
        } else {
            loginBtn.classList.remove('hidden')
            logoutBtn.classList.add('hidden')
        }

        // DOM
        const ticketList = document.getElementById('ticketList');
        const loading = document.getElementById('loading');
        const modal = document.getElementById('ticketModal');
        const closeModalBtn = document.getElementById('closeModal');
        const btnDownload = document.getElementById('btnDownload');

        const user = localStorage.getItem('user');
        const username = JSON.parse(user);

        let ticketsData = [];

        const formatRupiah = val => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(val);

        const openModal = (ticketId) => {
            const data = ticketsData.find(t => t.id === ticketId);
            if (!data) return;

            document.getElementById('modal_kapal').textContent = `${data.id}`;
            document.getElementById('modal_nama').textContent = username.name;
            document.getElementById('modal_waktu').textContent = data.tanggal;
            document.getElementById('modal_jumlah').textContent = data.jumlah_tiket;
            document.getElementById('modal_total').textContent = formatRupiah(data.total_harga);

            const badge = document.getElementById('modal_verifikasi');
            badge.textContent = data.status_verifikasi;
            badge.className = 'inline-block text-xs font-semibold px-3 py-1 rounded-full';
            if (data.status_verifikasi === 'diterima') badge.style.backgroundColor = '#d1fae5', badge.style.color = '#065f46';
            else if (data.status_verifikasi === 'menunggu') badge.style.backgroundColor = '#fef3c7', badge.style.color = '#78350f';
            else badge.style.backgroundColor = '#fee2e2', badge.style.color = '#991b1b';

            btnDownload.classList.toggle('hidden', data.status_verifikasi !== 'diterima');
            btnDownload.onclick = () => downloadTiketCanvas(data, username.name);

            modal.classList.remove('hidden');
        };

        const setupEventListeners = () => {
            document.querySelectorAll('.btn-view-detail').forEach(button => {
                button.addEventListener('click', () => {
                    const ticketId = parseInt(button.dataset.ticketId);
                    openModal(ticketId);
                });
            });
        };

        async function fetchTickets() {
            try {
                const res = await fetch('/api/user/pembayarans', {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) {
                    if (res.status === 401) window.location.href = '/login';
                    throw new Error('Gagal memuat data tiket. Status: ' + res.status);
                }

                const data = await res.json();
                ticketsData = data;

                loading.classList.add('hidden');
                ticketList.classList.remove('hidden');

                if (data.length === 0) {
                    ticketList.innerHTML = `<p class="text-center col-span-full text-gray-600">Anda belum memiliki riwayat tiket.</p>`;
                    return;
                }

                const today = new Date().toISOString().split('T')[0];
                ticketList.innerHTML = data.map(item => {
                    const isExpired = item.tanggal < today;
                    const label = isExpired ? 'Kadaluarsa' : 'Aktif';
                    const labelColor = isExpired ? 'red' : 'green';
                    let verificationBadgeColor = '#d1d5db';
                    let verificationTextColor = '#374151';
                    if (item.status_verifikasi === 'diterima') {
                        verificationBadgeColor = '#d1fae5';
                        verificationTextColor = '#065f46';
                    } else if (item.status_verifikasi === 'menunggu') {
                        verificationBadgeColor = '#fef3c7';
                        verificationTextColor = '#78350f';
                    } else if (item.status_verifikasi === 'ditolak') {
                        verificationBadgeColor = '#fee2e2';
                        verificationTextColor = '#991b1b';
                    }

                    return `
                    <div class="rounded-lg p-4 bg-white shadow-md flex flex-col justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">ID Tiket: ${item.id}</h3>
                            <p class="text-sm text-gray-600">Nama: ${username.name}</p>
                            <p class="text-sm text-gray-600">Tanggal: ${item.tanggal}</p>
                            <p class="text-sm text-gray-600">Jumlah: ${item.jumlah_tiket} tiket</p>
                            <span style="display:inline-block; margin-top:0.5rem; padding:0.2rem 0.5rem; border-radius:0.25rem; background-color:${labelColor}-100; color:${labelColor}-700; font-size:0.75rem; font-weight:600">
                                ${label}
                            </span>
                            <span style="display:inline-block; margin-top:0.5rem; margin-left:0.5rem; padding:0.2rem 0.5rem; border-radius:0.25rem; background-color:${verificationBadgeColor}; color:${verificationTextColor}; font-size:0.75rem; font-weight:600;">
                                ${item.status_verifikasi}
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
                console.error('❌ Terjadi error:', e);
                loading.innerHTML = `<p class="text-red-500 font-semibold">${e.message}</p>`;
            }
        }

        if (!token) {
            window.location.href = '/login';
        } else {
            loginBtn.classList.add('hidden');
            logoutBtn.classList.remove('hidden');
            fetchTickets();
        }

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

    // ======= Canvas-based download tiket =======
    function downloadTiketCanvas(data, username) {
        const width = 600;
        const height = 350;
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');

        // Fungsi formatRupiah global
        function formatRupiah(val) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(val);
        }

        // Background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, width, height);

        // Border
        ctx.strokeStyle = '#d1d5db';
        ctx.lineWidth = 2;
        ctx.strokeRect(0, 0, width, height);

        // Header
        ctx.fillStyle = '#1f2937';
        ctx.font = 'bold 22px sans-serif';
        ctx.fillText('Tiket Digital', 20, 40);

        ctx.font = '16px sans-serif';
        ctx.fillStyle = '#374151';
        ctx.fillText(`ID Tiket: #${data.id}`, 20, 75);
        ctx.fillText(`Nama: ${username || '-'}`, 20, 105);
        ctx.fillText(`Tanggal: ${data.tanggal}`, 20, 135);
        ctx.fillText(`Jumlah Tiket: ${data.jumlah_tiket}`, 20, 165);
        ctx.fillText(`Total Harga: ${formatRupiah(data.total_harga)}`, 20, 195);

        // Status Badge
        const status = data.status_verifikasi;
        let badgeColor = '#d1d5db';
        let textColor = '#374151';
        if (status === 'diterima') {
            badgeColor = '#d1fae5';
            textColor = '#065f46';
        } else if (status === 'menunggu') {
            badgeColor = '#fef3c7';
            textColor = '#78350f';
        } else if (status === 'ditolak') {
            badgeColor = '#fee2e2';
            textColor = '#991b1b';
        }

        ctx.fillStyle = badgeColor;
        ctx.fillRect(20, 225, 120, 28);

        ctx.fillStyle = textColor;
        ctx.font = 'bold 14px sans-serif';
        ctx.textBaseline = 'middle';
        ctx.fillText(status.toUpperCase(), 25, 225 + 14);

        // Metode Pembayaran
        ctx.font = '16px sans-serif';
        ctx.fillStyle = '#374151';
        ctx.fillText(`Metode Pembayaran: ${data.metode_pembayaran || 'Tidak diketahui'}`, 20, 270);

        // Download
        const link = document.createElement('a');
        link.download = `tiket-${data.id}.png`;
        link.href = canvas.toDataURL('image/png');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>