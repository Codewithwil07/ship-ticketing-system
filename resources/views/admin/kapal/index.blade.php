@extends('layouts.admin')

@section('title', 'Data Kapal')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Kapal</h1>
        <p class="text-sm text-gray-500">Kelola daftar kapal aktif dan tidak aktif.</p>
    </div>
    <button onclick="openKapalModal()" class="inline-flex cursor-pointer items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm shadow-sm transition">
        <i data-lucide="plus"></i> Tambah Kapal
    </button>
</div>

<div class="flex items-center justify-between mb-4">
    <div class="flex items-center gap-3">
        <label for="filter-status" class="text-sm font-medium text-gray-700">Filter Status:</label>
        <div class="relative">
            <select id="filter-status"
                class="block w-full appearance-none bg-white border border-gray-300 text-sm pl-3 pr-10 py-2 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua</option>
                <option value="aktif">Aktif</option>
                <option value="tidak aktif">Tidak Aktif</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                <i data-lucide="chevron-down" class="w-4 h-4"></i>
            </div>
        </div>
    </div>
</div>

<div id="kapal-loading" class="hidden text-center py-10 text-gray-400">Loading data kapal...</div>

<div id="kapal-container" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-x-auto hidden">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Tipe</th>
                <th class="px-4 py-3">Kapasitas</th>
                <th class="px-4 py-3">harga</th>
                <th class="px-4 py-3">Kode</th>
                <th class="px-4 py-3">Rute</th>
                <th class="px-4 py-3">Homebase</th>
                <th class="px-4 py-3">Operator</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="kapal-body" class="text-gray-700"></tbody>
    </table>
</div>
<div id="pagination" class="flex items-center gap-2 text-sm mt-7"></div>

<div id="toast" class="fixed bottom-5 right-5 z-50 hidden bg-green-600 text-white px-4 py-2 rounded shadow mt-10"></div>

<!-- Modal -->
<div id="kapal-modal" class="fixed inset-0 z-50 hidden bg-black/30 backdrop-blur-sm flex items-center justify-center">
    <div class="bg-white rounded-xl w-full max-w-xl p-6 relative">
        <button onclick="closeKapalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 cursor-pointer">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <h2 class="text-xl font-bold text-gray-800 mb-4">Form Kapal</h2>

        <form id="kapal-form" class="space-y-4">
            <input type="hidden" name="id">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Kapal</label>
                    <input type="text" name="nama_kapal" required class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipe</label>
                    <input type="text" name="tipe" required class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kapasitas</label>
                    <input type="number" name="kapasitas" required class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga</label>
                    <input type="number" name="harga" required class="w-full px-3 py-2 border rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kode Kapal</label>
                    <input type="text" name="kode_kapal" required class="w-full px-3 py-2 border rounded-md">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Rute</label>
                <input type="text" name="rute" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Home Base</label>
                <input type="text" name="home_base" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Operator</label>
                <input type="text" name="operator" required class="w-full px-3 py-2 border rounded-md">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <select name="status" required
                        class="block w-full appearance-none bg-white border border-gray-300 text-sm px-3 py-2 pr-10 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="aktif">Aktif</option>
                        <option value="tidak aktif">Tidak Aktif</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-400">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </div>
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
    const modal = document.getElementById('kapal-modal');
    const form = document.getElementById('kapal-form');
    const tableBody = document.getElementById('kapal-body');
    const token = localStorage.getItem('token')
    const loading = document.getElementById('kapal-loading');
    const container = document.getElementById('kapal-container');
    const toast = document.getElementById('toast');

    function openKapalModal(data = null) {
        modal.classList.remove('hidden');
        form.reset();
        form.id.value = data?.id || '';
        form.nama_kapal.value = data?.nama_kapal || '';
        form.tipe.value = data?.tipe || '';
        form.kapasitas.value = data?.kapasitas || '';
        form.harga.value = data?.harga || '';
        form.kode_kapal.value = data?.kode_kapal || '';
        form.rute.value = data?.rute || '';
        form.home_base.value = data?.home_base || '';
        form.operator.value = data?.operator || '';
        form.status.value = data?.status || 'aktif';
    }

    function closeKapalModal() {
        modal.classList.add('hidden');
    }

    function showToast(message) {
        toast.textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    async function fetchKapal(page = 1) {
        const status = document.getElementById('filter-status').value;
        // const status = document.getElementById('').value;
        loading.classList.remove('hidden');
        container.classList.add('hidden');

        const url = new URL('/api/kapals', window.location.origin);
        url.searchParams.append('page', page);
        if (status) url.searchParams.append('status', status);

        const res = await fetch(url, {
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        const response = await res.json();
        const data = response.data;


        tableBody.innerHTML = data.map(kapal => `
            <tr class="border-t hover:bg-gray-50 text-[10px]">
                <td class="px-4 py-3">${kapal.nama_kapal}</td>
                <td class="px-4 py-3">${kapal.tipe}</td>
                <td class="px-4 py-3">${kapal.kapasitas}</td>
                <td class="px-4 py-3">${kapal.harga}</td>
                <td class="px-4 py-3">${kapal.kode_kapal}</td>
                <td class="px-4 py-3">${kapal.rute}</td>
                <td class="px-4 py-3">${kapal.home_base}</td>
                <td class="px-4 py-3">${kapal.operator}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold ${kapal.status === 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'}">
                        ${kapal.status}
                    </span>
                </td>
                <td class="px-4 py-3 text-right space-x-2 flex items-center justify-center">
                    <button onclick='openKapalModal(${JSON.stringify(kapal)})' class="text-blue-600 hover:underline cursor-pointer">
                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                    </button>
                    <button onclick='deleteKapal(${kapal.id})' class="text-red-500 hover:text-red-600 cursor-pointer">
                        <i data-lucide="trash" class="w-4 h-4"></i>
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
        prev.onclick = () => fetchKapal(response.current_page - 1);
        pagination.appendChild(prev);

        const pageInfo = document.createElement('span');
        pageInfo.textContent = `Page ${response.current_page} of ${response.last_page}`;
        pagination.appendChild(pageInfo);

        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.disabled = !response.next_page_url;
        next.className = 'px-2 py-1 rounded border text-gray-600 hover:bg-gray-100 disabled:opacity-40 cursor-pointer';
        next.onclick = () => fetchKapal(response.current_page + 1);
        pagination.appendChild(next);
    }

    async function deleteKapal(id) {

        const res = await fetch(`/api/kapals/${id}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        });

        if (res.ok) {
            showToast('Kapal berhasil dihapus');
            fetchKapal();
        } else {
            console.log('Gagal menghapus');

        }
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = Object.fromEntries(new FormData(form));
        const method = formData.id ? 'PUT' : 'POST';
        const url = formData.id ? `/api/kapals/${formData.id}` : '/api/kapals';

        const res = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(formData)
        });

        if (res.ok) {
            closeKapalModal();
            fetchKapal();
            showToast('Data kapal berhasil disimpan');
        } else {
            alert('Gagal menyimpan data');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('filter-status').addEventListener('change', () => fetchKapal());
        fetchKapal();
    });
</script>
@endsection