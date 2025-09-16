@extends('layouts.user')

@section('content')
<main class="max-w-4xl mx-auto px-4 py-8">
    <div id="loadingState" class="text-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-500">Memuat detail pesanan...</p>
    </div>

    <div id="mainContent" class="hidden">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Konfirmasi Pemesanan</h1>
        <p class="text-gray-600 mb-6">Selesaikan pesanan Anda dalam beberapa langkah mudah.</p>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4 border-b pb-3">1. Detail Pemesan</h2>
                    <form id="bookingForm" novalidate>
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input type="text" id="nama_lengkap" class="mt-1 block w-full bg-gray-100 px-2 border-gray-300 rounded-md shadow-sm" readonly>
                            </div>
                            <div>
                                <label for="jumlah_tiket" class="block text-sm font-medium text-gray-700">Jumlah Tiket</label>
                                <input type="number" id="jumlah_tiket" min="1" max="10" value="1" required class="mt-1 block w-full px-2 border-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <h2 class="text-xl font-semibold mb-4 mt-8 border-b pb-3">2. Metode Pembayaran</h2>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4" id="rekeningInfo">
                            <p class="font-semibold text-gray-800">Transfer Manual</p>
                            <p class="text-sm text-gray-600 mb-3">Silakan lakukan transfer ke rekening berikut:</p>
                            <div class="text-center bg-white rounded p-3">
                                <p class="font-mono text-lg font-bold text-gray-900" id="rekening_nama">WAHYUNI PRATIWI</p>
                                <p class="font-mono text-2xl font-bold text-blue-600" id="rekening_nomor">654801012133532</p>
                                <p class="font-semibold text-gray-700" id="rekening_bank">BANK BRI</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="metode_pembayaran" class="block text-sm font-medium text-gray-700">Metode Transfer yang Anda Gunakan</label>
                            <select id="metode_pembayaran" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="BRI">Bank BRI</option>
                                <option value="BCA">Bank BCA</option>
                                <option value="DANA">DANA</option>
                            </select>
                        </div>


                        <div class="mt-4">
                            <label for="bukti_pembayaran" class="block text-sm font-medium text-gray-700">Unggah Bukti Pembayaran</label>
                            <input type="file" id="bukti_pembayaran" accept="image/png, image/jpeg" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>


                        <div class="mt-4">
                            <label for="bukti_ktp" class="block text-sm font-medium text-gray-700">Unggah Bukti KTP</label>
                            <input type="file" id="bukti_ktp" accept="image/png, image/jpeg" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>

                        <div class="mt-8 text-right">
                            <button type="submit" id="submitButton" class="bg-blue-600 w-full cursor-pointer text-white font-bold px-8 py-3 rounded-lg hover:bg-blue-700 shadow-md text-center">
                                Kirim & Buat Tiket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h3 class="text-lg font-semibold border-b pb-3 mb-4">Ringkasan Pesanan</h3>
                    <div id="summary">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Kapal:</span>
                            <span id="summary_kapal" class="font-semibold text-gray-900">-</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Rute:</span>
                            <span id="summary_rute" class="font-semibold text-gray-900 text-right">-</span>
                        </div>
                        <div class="border-t pt-4 mt-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Harga per Tiket:</span>
                                <span id="hargaPerTiket" class="font-semibold text-gray-900" data-harga="0">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-xl">
                                <span class="font-semibold text-gray-900">Total:</span>
                                <span id="totalPembayaran" class="font-bold text-blue-600">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Toast Container -->
<div id="toast-container" class="fixed bottom-6 right-6 z-50 space-y-3"></div>

<script>
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `max-w-xs w-full px-4 py-3 rounded-lg shadow-lg flex items-center gap-3 text-sm text-white transition-all duration-500 ${
            type === 'error' ? 'bg-red-500' : 'bg-emerald-600'
        }`;
        toast.innerHTML = `
            <div class="flex-1">${message}</div>
            <button onclick="this.parentElement.remove()" class="text-white hover:opacity-80">&times;</button>
        `;
        document.getElementById('toast-container').appendChild(toast);
        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const API_JADWAL_URL = '/api/user/jadwal-keberangkatan';
        const API_PEMESANAN_URL = '/api/user/pemesanans';
        const API_PEMBAYARAN_URL = '/api/user/pembayarans';
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user'));
        const id = localStorage.getItem('id');



        const loadingState = document.getElementById('loadingState');
        const mainContent = document.getElementById('mainContent');
        const jumlahTiketInput = document.getElementById('jumlah_tiket');
        const hargaPerTiketElem = document.getElementById('hargaPerTiket');
        const totalPembayaranElem = document.getElementById('totalPembayaran');

        document.getElementById('nama_lengkap').value = user?.name || '-';

        let currentSchedule = null;

        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }

        function updateTotal() {
            const qty = parseInt(jumlahTiketInput.value) || 1;
            const harga = parseFloat(hargaPerTiketElem.getAttribute('data-harga')) || 0;
            totalPembayaranElem.textContent = formatPrice(qty * harga);
        }

        function populateSummary(schedule) {
            document.getElementById('summary_kapal').textContent = schedule.kapal.nama_kapal;
            document.getElementById('summary_rute').textContent = `${schedule.kapal.home_base} - ${schedule.tujuan}`;
            hargaPerTiketElem.setAttribute('data-harga', schedule.harga);
            hargaPerTiketElem.textContent = formatPrice(schedule.harga);
            updateTotal();
        }

        async function initPage(id) {
            try {
                const res = await fetch(`${API_JADWAL_URL}/${id}`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                if (!res.ok) throw new Error('Gagal memuat jadwal.');
                const schedule = await res.json();
                currentSchedule = schedule;
                populateSummary(schedule);
                loadingState.classList.add('hidden');
                mainContent.classList.remove('hidden');
            } catch (e) {
                showToast(e.message, 'error');
                setTimeout(() => window.location.href = '/', 2000);
            }
        }

        jumlahTiketInput.addEventListener('input', () => {
            let val = parseInt(jumlahTiketInput.value);
            if (isNaN(val) || val < 1) {
                jumlahTiketInput.value = 1;
            }
            updateTotal();
        });


        document.getElementById('bookingForm').addEventListener('submit', async e => {
            e.preventDefault();
            const bukti = document.getElementById('bukti_pembayaran').files[0];
            const bukti_ktp = document.getElementById('bukti_ktp').files[0];
            const metode = document.getElementById('metode_pembayaran').value;
            const jumlah = parseInt(jumlahTiketInput.value) || 1;
            const harga = parseInt(hargaPerTiketElem.getAttribute('data-harga')) || 0;
            const total = jumlah * harga;

            if (!bukti) return showToast('Harap upload bukti pembayaran', 'error');
            if (!bukti_ktp) return showToast('Harap upload bukti ktp', 'error');

            try {
                // 1. Kirim pemesanan pakai FormData
                const formPemesanan = new FormData();
                formPemesanan.append('jadwal_id', Number(id));
                formPemesanan.append('jumlah_tiket', jumlah);
                formPemesanan.append('total_harga', total);
                formPemesanan.append('status', 'pending');
                formPemesanan.append('bukti_ktp', bukti_ktp); // ✅ file ikut terkirim

                const resPemesanan = await fetch(API_PEMESANAN_URL, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`
                        // ❌ jangan set Content-Type manual, biar browser otomatis isi "multipart/form-data"
                    },
                    body: formPemesanan
                });


                const contentType = resPemesanan.headers.get('content-type') || '';
                if (!resPemesanan.ok || !contentType.includes('application/json')) {
                    const text = await resPemesanan.text();
                    console.error('Server response bukan JSON:', text);
                    return showToast('Gagal membuat pemesanan', 'error');
                }

                const hasilPemesanan = await resPemesanan.json();
                if (!resPemesanan.ok) return showToast(hasilPemesanan.message || 'Gagal menyimpan pemesanan', 'error');

                // 2. Kirim pembayaran
                const formData = new FormData();
                formData.append('pemesanan_id', hasilPemesanan.id);
                formData.append('metode_pembayaran', metode);
                formData.append('bukti', bukti);

                const resBayar = await fetch(API_PEMBAYARAN_URL, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                    },
                    body: formData
                });

                if (!resBayar.ok) {
                    const err = await resBayar.json();
                    return showToast(err.message || 'Gagal upload pembayaran', 'error');
                }

                showToast('Pemesanan berhasil!');
                setTimeout(() => {
                    window.location.href = `/tiket`;
                }, 1500);
            } catch (err) {
                console.error(err);
                showToast('Terjadi kesalahan saat mengirim data', 'error');
            }
        });

        const metodeSelect = document.getElementById('metode_pembayaran');
        const namaElem = document.getElementById('rekening_nama');
        const nomorElem = document.getElementById('rekening_nomor');
        const bankElem = document.getElementById('rekening_bank');

        // Data rekening per metode
        const metodeRekening = {
            BRI: {
                nama: 'ADMIN SUBSEA',
                nomor: '654801012133532',
                bank: 'BANK BRI'
            },
            BCA: {
                nama: 'PT SUBSEA GLOBALINDO',
                nomor: '7810998899',
                bank: 'BANK BCA'
            },
            DANA: {
                nama: 'ADMIN SUBSEA',
                nomor: '0812-3456-7890',
                bank: 'DANA - Dompet Digital'
            }
        };

        // Ganti UI rekening ketika dropdown berubah
        metodeSelect.addEventListener('change', (e) => {
            const selected = e.target.value;
            const data = metodeRekening[selected];

            if (data) {
                namaElem.textContent = data.nama;
                nomorElem.textContent = data.nomor;
                bankElem.textContent = data.bank;
            }
        });

        // Set default tampilan saat load
        metodeSelect.dispatchEvent(new Event('change'));


        jumlahTiketInput.addEventListener('input', updateTotal);
        if (id) initPage(id);
    });
</script>
@endsection