@extends('layouts.admin')

@section('title', 'Jadwal Keberangkatan')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Keberangkatan</h1>
        <p class="text-sm text-gray-500">Kelola jadwal kapal yang tersedia.</p>
    </div>
    <button onclick="openModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white cursor-pointer rounded-lg text-sm shadow-sm transition">
        <i data-lucide="plus"></i> Tambah Jadwal
    </button>
</div>

<div class="flex items-center gap-4 mb-4">
    <input type="text" id="search-input" placeholder="Cari asal / tujuan / kapal..." class="border rounded px-3 py-2 text-sm w-full max-w-xs">
    <select id="filter-status" class="rounded-md outline-none px-3 py-2 text-sm cursor-pointer bg-white shadow-sm">
        <option value="">Semua Status</option>
        <option value="tersedia">Tersedia</option>
        <option value="selesai">Selesai</option>
        <option value="dibatalkan">Dibatalkan</option>
    </select>
</div>

<div id="loading" class="text-center py-10 text-gray-400 hidden">Loading...</div>

<div id="data-container" class="overflow-x-auto hidden rounded-md shadow-sm">
    <table class="min-w-full text-sm text-left bg-white">
        <thead class="bg-gray-100 text-gray-600 uppercase">
            <tr>
                <th class="px-4 py-3">Kapal</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Rute</th>
                <th class="px-4 py-3">Harga</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="data-body" class="text-gray-800"></tbody>
    </table>
</div>

<div id="pagination" class="flex gap-2 text-sm mt-6"></div>

<div id="modal" class="fixed inset-0 z-50 bg-black/30 backdrop-blur-sm flex items-center justify-center hidden">
    <div class="bg-white rounded-xl p-6 max-w-lg w-full relative">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 cursor-pointer"><i data-lucide="x"></i></button>
        <h2 class="text-lg font-bold text-gray-700 mb-4">Form Jadwal</h2>
        <form id="form" class="space-y-4">
            <input type="hidden" name="id">
            <div>
                <label class="block text-sm font-medium text-gray-600">Kapal</label>
                <select name="kapal_id" required class="w-full px-3 py-2 border rounded">
                    <option value="">Pilih Kapal</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tanggal Berangkat</label>
                    <input type="date" name="tanggal_berangkat" required class="w-full px-3 py-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Jam Berangkat</label>
                    <input type="time" name="jam_berangkat" required class="w-full px-3 py-2 border rounded">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Asal</label>
                    <input type="text" name="asal" required class="w-full px-3 py-2 border rounded" placeholder="Contoh: Jakarta">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600">Tujuan</label>
                    <input type="text" name="tujuan" required class="w-full px-3 py-2 border rounded" placeholder="Contoh: Surabaya">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600">Harga</label>
                    <input type="number" name="harga" required min="0" class="w-full px-3 py-2 border rounded" placeholder="Contoh: 150000">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 cursor-pointer bg-white">Status</label>
                    <select name="status" required class="w-full px-3 py-2 border rounded cursor-pointer">
                        <option value="tersedia">Tersedia</option>
                        <option value="selesai">Selesai</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
            </div>
            <div class="text-right">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded cursor-pointer">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="toast" class="fixed bottom-6 right-6 bg-green-600 text-white px-4 py-2 rounded shadow hidden"></div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');
    const container = document.getElementById('data-container');
    const body = document.getElementById('data-body');
    const form = document.getElementById('form');
    const modal = document.getElementById('modal');
    const toast = document.getElementById('toast');
    const loading = document.getElementById('loading');

    function showToast(msg) {
        toast.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }

    // == PERUBAHAN DI SINI: Menambahkan 'asal' dan 'harga' saat membuka modal ==
    function openModal(data = null) {
        form.reset();
        form.id.value = data?.id || '';
        form.kapal_id.value = data?.kapal_id || '';
        form.tanggal_berangkat.value = data?.tanggal_berangkat || '';
        form.jam_berangkat.value = data ? data.jam_berangkat.slice(0, 5) : '';
        form.asal.value = data?.asal || '';
        form.tujuan.value = data?.tujuan || '';
        form.harga.value = data?.harga || '';
        form.status.value = data?.status || 'tersedia';
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    async function fetchJadwal(page = 1) {
        const status = document.getElementById('filter-status').value;
        const search = document.getElementById('search-input').value;

        loading.classList.remove('hidden');
        container.classList.add('hidden');

        const url = new URL('/api/jadwal-keberangkatan', window.location.origin);
        url.searchParams.append('page', page);
        if (status) url.searchParams.append('status', status);
        if (search) url.searchParams.append('search', search);

        const res = await fetch(url, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        const result = await res.json();
        // == PERUBAHAN DI SINI: Menampilkan 'asal' dan 'harga' di tabel ==
        body.innerHTML = result.data.map(item => `
            <tr class="border-t hover:bg-gray-50 text-sm">
                <td class="px-4 py-3">${item.kapal?.nama_kapal}</td>
                <td class="px-4 py-3">
                    <div class="font-semibold">${new Date(item.tanggal_berangkat).toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' })}</div>
                    <div class="text-gray-500">${item.jam_berangkat.slice(0, 5)} WIB</div>
                </td>
                <td class="px-4 py-3">
                    <div class="font-semibold">${item.asal}</div>
                    <div class="text-gray-500">${item.tujuan}</div>
                </td>
                <td class="px-4 py-3 font-mono">Rp ${new Intl.NumberFormat('id-ID').format(item.harga)}</td>
                <td class="px-4 py-3 capitalize">${item.status}</td>
                <td class="px-4 py-2 text-right">
                    <button onclick='openModal(${JSON.stringify(item)})' class="text-blue-500 hover:underline mr-2 cursor-pointer"><i data-lucide="edit-2"></i></button>
                    <button onclick='hapus(${item.id})' class="text-red-600 hover:text-red-700 cursor-pointer"><i data-lucide="trash-2"></i></button>
                </td>
            </tr>
        `).join('');

        renderPagination(result);
        loading.classList.add('hidden');
        container.classList.remove('hidden');
        lucide.createIcons();
    }

    function renderPagination(result) {
        const pag = document.getElementById('pagination');
        pag.innerHTML = '';
        if (result.total === 0) return;

        result.links.forEach(link => {
            const btn = document.createElement('button');
            btn.innerHTML = link.label;
            btn.disabled = !link.url;
            btn.className = `px-3 py-1 rounded border ${link.active ? 'bg-blue-600 text-white' : 'bg-white'} ${!link.url ? 'text-gray-400' : ''}`;
            btn.onclick = () => fetchJadwal(new URL(link.url).searchParams.get('page'));
            pag.appendChild(btn);
        });
    }

    async function fetchKapals() {
        const res = await fetch('/api/kapals?per_page=1000', { // Ambil semua kapal
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        const result = await res.json();
        const select = form.kapal_id;
        select.innerHTML = '<option value="">Pilih Kapal</option>';
        result.data.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id;
            opt.textContent = k.nama_kapal;
            select.appendChild(opt);
        });
    }

    async function hapus(id) {
        if (!confirm('Apakah Anda yakin ingin menghapus jadwal ini?')) return;

        await fetch(`/api/jadwal-keberangkatan/${id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        showToast('Jadwal berhasil dihapus');
        fetchJadwal();
    }

    form.addEventListener('submit', async e => {
        e.preventDefault();
        const fd = Object.fromEntries(new FormData(form));
        const method = fd.id ? 'PUT' : 'POST';
        const url = fd.id ? `/api/jadwal-keberangkatan/${fd.id}` : '/api/jadwal-keberangkatan';
        const res = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                Authorization: `Bearer ${token}`
            },
            body: JSON.stringify(fd)
        });
        if (res.ok) {
            closeModal();
            fetchJadwal();
            showToast('Jadwal berhasil disimpan');
        } else {
            const error = await res.json();
            const errorMessages = Object.values(error.errors).flat().join('\n');
            alert(`Gagal menyimpan:\n${errorMessages}`);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchJadwal();
        fetchKapals();
        let searchTimeout;
        document.getElementById('filter-status').addEventListener('change', () => fetchJadwal());
        document.getElementById('search-input').addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchJadwal(), 500); // Debounce
        });
    });
</script>
@endsection