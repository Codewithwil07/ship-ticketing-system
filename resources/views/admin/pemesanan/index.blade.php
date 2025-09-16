@extends('layouts.admin')

@section('title', 'Data Pemesanan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Pemesanan</h1>
        <p class="text-sm text-gray-500">Kelola seluruh data pemesanan pengguna.</p>
    </div>
</div>

<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
        <label for="filter-status" class="text-sm font-medium text-gray-700">Filter Status:</label>
        <div class="relative">
            <select id="filter-status" class="block w-full appearance-none bg-white border border-gray-300 text-sm pl-3 pr-10 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                <option value="pending">Pending</option>
                <option value="lunas">Lunas</option>
                <option value="batal">Batal</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>
</div>

<div id="pemesanan-loading" class="hidden text-center py-10 text-gray-400">Loading data pemesanan...</div>

<div id="pemesanan-container" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Jadwal</th>
                <th class="px-4 py-3">Jumlah Tiket</th>
                <th class="px-4 py-3">Total Harga</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-end">Aksi</th>
            </tr>
        </thead>
        <tbody id="pemesanan-body" class="text-gray-700"></tbody>
    </table>
</div>
<div id="pagination" class="flex items-center gap-2 text-sm mt-7"></div>

<div id="toast" class="fixed bottom-5 right-5 z-50 hidden bg-green-600 text-white px-4 py-2 rounded shadow mt-10"></div>

<!-- Modal Lihat KTP -->
<div id="ktp-modal" class="fixed inset-0 z-50 hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-xl p-4 max-w-lg w-full relative">
        <button onclick="closeKTPModal()"
            class="absolute top-3 right-3 text-gray-400 hover:text-red-500">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-lg font-bold mb-4">Bukti KTP</h2>
        <img id="ktp-image" src="" alt="Bukti KTP" class="w-full rounded shadow">
    </div>
</div>


<!-- Modal (Update Status) -->
<div id="pemesanan-modal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-md p-6 relative">
        <button onclick="closePemesananModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ubah Status Pemesanan</h2>

        <form id="pemesanan-form" class="space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required class="block w-full border-gray-300 rounded-lg px-3 py-2 text-sm shadow-sm">
                    <option value="pending">Pending</option>
                    <option value="lunas">Lunas</option>
                    <option value="batal">Batal</option>
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
    const modal = document.getElementById('pemesanan-modal');
    const form = document.getElementById('pemesanan-form');
    const tableBody = document.getElementById('pemesanan-body');
    const token = localStorage.getItem('token')
    const loading = document.getElementById('pemesanan-loading');
    const container = document.getElementById('pemesanan-container');
    const toast = document.getElementById('toast');

    function openPemesananModal(data = null) {
        modal.classList.remove('hidden');
        form.reset();
        form.id.value = data?.id || '';
        form.status.value = data?.status || 'pending';
    }

    function closePemesananModal() {
        modal.classList.add('hidden');
    }

    function showToast(message) {
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    async function fetchPemesanan(page = 1) {
        const status = document.getElementById('filter-status').value;
        loading.classList.remove('hidden');
        container.classList.add('hidden');

        const url = new URL('/api/pemesanans', window.location.origin);
        url.searchParams.append('page', page);
        if (status) url.searchParams.append('status', status);

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const response = await res.json();
        const data = response.data;

        console.log(data);


        tableBody.innerHTML = data.map(p => `
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3">${p.nama_penumpang}</td>
                <td class="px-4 py-3">${p.jadwal?.tanggal_berangkat || '-'} (${p.jadwal?.tujuan || '-'})</td>
                <td class="px-4 py-3">${p.jumlah_tiket}</td>
                <td class="px-4 py-3">Rp${p.total_harga.toLocaleString()}</td>
                <td class="px-4 py-3">
    ${p.bukti_ktp 
        ? `<button onclick="viewKTP('${p.bukti_ktp}')" 
            class="text-blue-600 hover:underline cursor-pointer">
            Lihat KTP
          </button>` 
        : '-'}
</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${p.status === 'lunas' ? 'bg-green-100 text-green-600' : p.status === 'batal' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600'}">
                        ${p.status}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2 flex items-center justify-center">
                    <button onclick='openPemesananModal(${JSON.stringify(p)})' class="text-blue-600 hover:underline cursor-pointer">
                        <i data-lucide="edit-2" class="w-4 h-4 ml-10"></i>
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
        prev.onclick = () => fetchPemesanan(response.current_page - 1);
        pagination.appendChild(prev);

        const pageInfo = document.createElement('span');
        pageInfo.textContent = `Page ${response.current_page} of ${response.last_page}`;
        pagination.appendChild(pageInfo);

        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.disabled = !response.next_page_url;
        next.className = 'px-2 py-1 rounded border text-gray-600 hover:bg-gray-100 disabled:opacity-40 cursor-pointer';
        next.onclick = () => fetchPemesanan(response.current_page + 1);
        pagination.appendChild(next);
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(form));
        const url = `/api/pemesanans/${formData.id}`;

        const res = await fetch(url, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify({
                status: formData.status
            })
        });

        if (res.ok) {
            closePemesananModal();
            fetchPemesanan();
            showToast('Status pemesanan diperbarui');
        } else {
            const error = await res.json()
            alert('Gagal memperbarui data');
            console.log(error);

        }
    });

    function viewKTP(path) {
        const modal = document.getElementById('ktp-modal');
        const img = document.getElementById('ktp-image');
        img.src = `/storage/${path}`; // sesuaikan path penyimpanan file KTP
        modal.classList.remove('hidden');
    }

    function closeKTPModal() {
        document.getElementById('ktp-modal').classList.add('hidden');
    }


    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('filter-status').addEventListener('change', () => fetchPemesanan());
        fetchPemesanan();
    });
</script>
@endsection