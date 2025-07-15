@extends('layouts.admin')

@section('title', 'Data Pembayaran')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pembayaran</h1>
        <p class="text-sm text-gray-500">Kelola bukti pembayaran pengguna dan status verifikasinya.</p>
    </div>
</div>

<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
        <label for="filter-status" class="text-sm font-medium text-gray-700">Filter Status:</label>
        <div class="relative">
            <select id="filter-status"
                class="block w-full appearance-none bg-white border border-gray-300 text-sm pl-3 pr-10 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                <option value="menunggu">Menunggu</option>
                <option value="diterima">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>
</div>

<div id="pembayaran-loading" class="hidden text-center py-10 text-gray-400">Loading data pembayaran...</div>

<div id="bukti-modal" class="hidden fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-lg p-6 relative">
        <button onclick="closeBuktiModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Bukti Pembayaran</h2>
        <div class="flex justify-center items-center">
            <img id="bukti-image" src="" class="max-w-full h-96 rounded-lg shadow-md" alt="Bukti Pembayaran">
        </div>
    </div>
</div>

<div id="pembayaran-container" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Metode</th>
                <th class="px-4 py-3">Bukti</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="pembayaran-body" class="text-gray-700"></tbody>
    </table>
</div>
<div id="pagination" class="flex items-center gap-2 text-sm mt-7"></div>

<div id="toast" class="fixed bottom-5 right-5 z-50 hidden bg-green-600 text-white px-4 py-2 rounded shadow mt-10"></div>


<div id="pembayaran-modal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button onclick="closePembayaranModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Verifikasi Pembayaran</h2>

        <form id="pembayaran-form" class="space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                <select name="status_verifikasi" required class="w-full px-3 py-2 border rounded-md">
                    <option value="menunggu">Menunggu</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            <div class="text-right">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 cursor-pointer text-white rounded-lg shadow-sm">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    const container = document.getElementById('pembayaran-container');
    const loading = document.getElementById('pembayaran-loading');
    const tableBody = document.getElementById('pembayaran-body');
    const form = document.getElementById('pembayaran-form');
    const modal = document.getElementById('pembayaran-modal');
    const toast = document.getElementById('toast');

    const buktiModal = document.getElementById('bukti-modal');
    const buktiImage = document.getElementById('bukti-image');

    function openPembayaranModal(data) {
        modal.classList.remove('hidden');
        form.id.value = data.id;
        form.status_verifikasi.value = data.status_verifikasi;
    }

    function closePembayaranModal() {
        modal.classList.add('hidden');
    }

    function showToast(msg) {
        toast.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // New functions for the bukti-modal
    function openBuktiModal(imageUrl) {
        buktiImage.src = imageUrl;
        buktiModal.classList.remove('hidden');
    }

    function closeBuktiModal() {
        buktiModal.classList.add('hidden');
        buktiImage.src = ''; // Clear image source when closing
    }

    async function fetchPembayaran(page = 1) {
        const status = document.getElementById('filter-status').value;
        loading.classList.remove('hidden');
        container.classList.add('hidden');

        const url = new URL('/api/pembayarans', window.location.origin);
        url.searchParams.append('page', page);
        if (status) url.searchParams.append('status', status);

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const response = await res.json();
        const data = response.data || [];

        tableBody.innerHTML = data.map(item => `
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3">${item.pemesanan?.user?.name || '-'}</td>
                <td class="px-4 py-3">${item.metode_pembayaran}</td>
                <td class="px-4 py-3">
                    <p onclick='openBuktiModal("/storage/${item.bukti}")' class="text-blue-600 underline cursor-pointer">Lihat</p>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${item.status_verifikasi === 'diterima' ? 'bg-green-100 text-green-600' : item.status_verifikasi === 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-yellow-100 text-yellow-600'}">
                        ${item.status_verifikasi}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <button onclick='openPembayaranModal(${JSON.stringify(item)})' class="text-blue-600 hover:underline">
                        <i data-lucide="edit" class="w-4 h-4 cursor-pointer"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        renderPagination(response);
        loading.classList.add('hidden');
        container.classList.remove('hidden');
        lucide.createIcons();
    }

    function renderPagination(response) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const prev = document.createElement('button');
        prev.textContent = '← Prev';
        prev.disabled = !response.prev_page_url;
        prev.className = 'px-2 py-1 rounded border text-gray-600 hover:bg-gray-100 disabled:opacity-40 cursor-pointer';
        prev.onclick = () => fetchPembayaran(response.current_page - 1);
        pagination.appendChild(prev);

        const pageInfo = document.createElement('span');
        pageInfo.textContent = `Page ${response.current_page} of ${response.last_page}`;
        pagination.appendChild(pageInfo);

        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.disabled = !response.next_page_url;
        next.className = 'px-2 py-1 rounded border text-gray-600 hover:bg-gray-100 disabled:opacity-40 cursor-pointer';
        next.onclick = () => fetchPembayaran(response.current_page + 1);
        pagination.appendChild(next);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(form));
        const res = await fetch(`/api/pembayarans/${formData.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                status_verifikasi: formData.status_verifikasi
            })
        });

        if (res.ok) {
            closePembayaranModal();
            fetchPembayaran();
            showToast('Status berhasil diperbarui');
        } else {
            alert('Gagal memperbarui status');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('filter-status').addEventListener('change', () => fetchPembayaran());
        fetchPembayaran();
    });
</script>
@endsection