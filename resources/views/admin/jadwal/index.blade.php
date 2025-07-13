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
    <input type="text" id="search-input" placeholder="Cari tujuan / kapal..." class="border rounded px-3 py-2 text-sm w-full max-w-xs">
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
                <th class="px-4 py-3">Jam</th>
                <th class="px-4 py-3">Tujuan</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody id="data-body" class="text-gray-800"></tbody>
    </table>
</div>

<div id="pagination" class="flex gap-2 text-sm mt-6"></div>

<!-- Modal -->
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
            <div>
                <label class="block text-sm font-medium text-gray-600">Tanggal Berangkat</label>
                <input type="date" name="tanggal_berangkat" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Jam Berangkat</label>
                <input type="time" name="jam_berangkat" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600">Tujuan</label>
                <input type="text" name="tujuan" required class="w-full px-3 py-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-600 cursor-pointer bg-white">Status</label>
                <select name="status" required class="w-full px-3 py-2 border rounded cursor-pointer">
                    <option value="tersedia">Tersedia</option>
                    <option value="selesai">Selesai</option>
                    <option value="dibatalkan">Dibatalkan</option>
                </select>
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

    
    function openModal(data = null) {
        form.reset();
        form.id.value = data?.id || '';
        form.kapal_id.value = data?.kapal_id || '';
        form.tanggal_berangkat.value = data?.tanggal_berangkat || '';
        form.jam_berangkat.value = data?.jam_berangkat + ':00'|| '';
        form.tujuan.value = data?.tujuan || '';
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
        body.innerHTML = result.data.map(item => `
            <tr class="border-t hover:bg-gray-50 text-sm">
                <td class="px-4 py-2">${item.kapal?.nama_kapal}</td>
                <td class="px-4 py-2">${item.tanggal_berangkat}</td>
                <td class="px-4 py-2">${item.jam_berangkat.slice(0, 5)}</td>
                <td class="px-4 py-2">${item.tujuan}</td>
                <td class="px-4 py-2 capitalize">${item.status}</td>
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
        const prev = document.createElement('button');
        prev.textContent = '← Prev';
        prev.disabled = !result.prev_page_url;
        prev.className = 'px-2 py-1 rounded border';
        prev.onclick = () => fetchJadwal(result.current_page - 1);
        pag.appendChild(prev);

        const info = document.createElement('span');
        info.textContent = `Page ${result.current_page} of ${result.last_page}`;
        pag.appendChild(info);

        const next = document.createElement('button');
        next.textContent = 'Next →';
        next.disabled = !result.next_page_url;
        next.className = 'px-2 py-1 rounded border';
        next.onclick = () => fetchJadwal(result.current_page + 1);
        pag.appendChild(next);
    }

    async function fetchKapals() {
        const res = await fetch('/api/kapals', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        const data = await res.json();
        const select = form.kapal_id;
        select.innerHTML = '<option value="">Pilih Kapal</option>';
        data.data.forEach(k => {
            const opt = document.createElement('option');
            opt.value = k.id;
            opt.textContent = k.nama_kapal;
            select.appendChild(opt);
        });
    }

    async function hapus(id) {
        await fetch(`/api/jadwal-keberangkatan/${id}`, {
            method: 'DELETE',
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        showToast('Berhasil dihapus');
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
                Authorization: `Bearer ${token}`
            },
            body: JSON.stringify(fd)
        });
        if (res.ok) {
            closeModal();
            fetchJadwal();
            showToast('Berhasil disimpan');
        } else {
            const error = await res.json();
            alert('Gagal menyimpan');
            console.error(error.errors); //
            alert(error.message || 'Gagal menyimpan');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchJadwal();
        fetchKapals();
        document.getElementById('filter-status').addEventListener('change', () => fetchJadwal());
        document.getElementById('search-input').addEventListener('input', () => fetchJadwal());
    });
</script>
@endsection