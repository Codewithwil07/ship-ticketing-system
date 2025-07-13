@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan sistem keseluruhan.</p>
</div>

{{-- CARD GRID --}}
<div id="dashboard-cards" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8"></div>

{{-- TABLE --}}
<div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-x-auto">
    <div class="flex justify-between items-center px-4 py-3 border-b">
        <h2 class="text-lg font-semibold text-gray-700">Pemesanan Terbaru</h2>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">Penumpang</th>
                <th class="px-6 py-3">Kapal</th>
                <th class="px-6 py-3">Tanggal</th>
                <th class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody id="pemesanan-body" class="text-gray-700"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    const token = localStorage.getItem('token');

    async function fetchDashboardData() {
        const headers = {
            'Authorization': `Bearer ${token}`
        };

        const [kapal, jadwal, pemesanan, pembayaran] = await Promise.all([
            fetch('/api/kapals', {
                headers
            }).then(r => r.json()),
            fetch('/api/jadwal-keberangkatan', {
                headers
            }).then(r => r.json()),
            fetch('/api/pemesanans', {
                headers
            }).then(r => r.json()),
            fetch('/api/pembayarans?status=diterima', {
                headers
            }).then(r => r.json())
        ]);

        const cards = [{
                label: 'Total Kapal',
                value: kapal.total ?? kapal.length,
                icon: 'ship',
                color: 'blue'
            },
            {
                label: 'Jadwal Aktif',
                value: jadwal.data?.filter(j => j.status === 'tersedia').length ?? 0,
                icon: 'calendar-days',
                color: 'green'
            },
            {
                label: 'Pemesanan',
                value: pemesanan.total ?? pemesanan.length,
                icon: 'ticket',
                color: 'purple'
            },
            {
                label: 'Pembayaran',
                value: pembayaran.total ?? pembayaran.length,
                icon: 'credit-card',
                color: 'orange'
            },
        ];

        renderCards(cards);
        renderPemesanan(pemesanan.data?.slice(0, 5) || pemesanan.slice(0, 5));
        lucide.createIcons();
    }

    function renderCards(items) {
        const wrap = document.getElementById('dashboard-cards');
        wrap.innerHTML = '';
        items.forEach(item => {
            wrap.innerHTML += `
                <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 group cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 rounded-full bg-${item.color}-100 text-${item.color}-600 transition-all group-hover:scale-110">
                            <i data-lucide="${item.icon}" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">${item.label}</p>
                            <h3 class="text-xl font-semibold text-gray-800">${item.value}</h3>
                        </div>
                    </div>
                </div>
            `;
        });
    }

    function renderPemesanan(data) {
        const tbody = document.getElementById('pemesanan-body');
        tbody.innerHTML = '';

        data.forEach(p => {
            console.log(p);

            const kapal = p.jadwal?.kapal?.nama_kapal ?? '-';
            const tanggal = p.jadwal?.tanggal_berangkat ?? '-';
            const status = p.status;
            const color = status === 'lunas' ? 'green' : (status === 'pending' ? 'yellow' : 'gray');

            tbody.innerHTML += `
                <tr class="border-t hover:bg-gray-50 transition">
                    <td class="px-6 py-3">${p.nama_penumpang}</td>
                    <td class="px-6 py-3">${kapal}</td>
                    <td class="px-6 py-3">${tanggal}</td>
                    <td class="px-6 py-3">
                        <span class="px-2 py-1 text-xs rounded-full bg-${color}-100 text-${color}-600 font-medium">${status}</span>
                    </td>
                </tr>
            `;
        });
    }

    document.addEventListener('DOMContentLoaded', fetchDashboardData);
</script>
@endsection