@extends('layouts.admin')

@section('title', 'Rekapitulasi Keuangan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Rekap Keuangan Bulanan</h1>
    <p class="text-sm text-gray-500">Rekap total pemasukan dari pembayaran yang diterima.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6" id="laporan-container">
</div>

<div class="bg-white p-6 rounded-lg shadow-sm border mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Detail Pemasukan</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Pemb.</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Tiket</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Harga</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                </tr>
            </thead>
            <tbody id="transactions-table-body" class="bg-white divide-y divide-gray-200">
            </tbody>
        </table>
    </div>
    <div id="pagination-controls" class="mt-4 flex justify-between items-center">
    </div>
    <div class="mt-4 text-right text-lg font-semibold text-gray-800">
        Total Keseluruhan: <span id="grand-total-amount" class="text-blue-600">Rp 0</span>
    </div>
</div>

<div id="loading" class="text-gray-400">Memuat data...</div>
@endsection

@section('scripts')
<script>
    const container = document.getElementById('laporan-container');
    const loading = document.getElementById('loading');
    const transactionsTableBody = document.getElementById('transactions-table-body');
    const paginationControls = document.getElementById('pagination-controls');
    const grandTotalAmount = document.getElementById('grand-total-amount');
    const token = localStorage.getItem('token');
    const itemsPerPage = 10;
    let currentPage = 1;
    let allTransactions = [];

    async function fetchLaporan() {
        try {
            const res = await fetch('/api/pembayarans?status=diterima', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });
            const result = await res.json();
            const data = result.data;
            allTransactions = data; // Store all transactions

            const monthlyTotal = {};
            let grandTotal = 0;

            data.forEach(item => {
                const month = new Date(item.created_at).toLocaleString('default', {
                    month: 'long',
                    year: 'numeric'
                });
                const harga = item.pemesanan?.total_harga ?? 0;
                monthlyTotal[month] = (monthlyTotal[month] || 0) + harga;
                grandTotal += harga;
            });

            container.innerHTML = Object.entries(monthlyTotal).map(([bulan, total]) => `
                <div class="bg-white p-4 rounded-lg shadow-sm border">
                    <div class="text-sm text-gray-500">${bulan}</div>
                    <div class="text-xl font-semibold text-blue-600">Rp ${total.toLocaleString('id-ID')}</div>
                </div>
            `).join('');

            grandTotalAmount.textContent = `Rp ${grandTotal.toLocaleString('id-ID')}`;

            renderTable();
            renderPagination();

        } catch (err) {
            container.innerHTML = '<p class="text-red-500">Gagal memuat laporan</p>';
            transactionsTableBody.innerHTML = '<tr><td colspan="9" class="px-6 py-4 text-center text-red-500">Gagal memuat data transaksi</td></tr>';
        } finally {
            loading.classList.add('hidden');
        }
    }

    function renderTable() {
        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const paginatedTransactions = allTransactions.slice(startIndex, endIndex);

        transactionsTableBody.innerHTML = paginatedTransactions.map(item => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">${item.id ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${item.pemesanan?.jumlah_tiket ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">Rp ${ (item.pemesanan?.total_harga ?? 0).toLocaleString('id-ID') }</td>
                <td class="px-6 py-4 whitespace-nowrap">${item.pemesanan?.status ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${item.metode_pembayaran ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${item.status_verifikasi ?? '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap">${new Date(item.created_at).toLocaleDateString('id-ID')}</td>
            </tr>
        `).join('');
    }

    function renderPagination() {
        const totalPages = Math.ceil(allTransactions.length / itemsPerPage);
        let paginationHtml = '';

        if (totalPages > 1) {
            paginationHtml += `
                <button onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? 'disabled' : ''} class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md disabled:opacity-50">Sebelumnya</button>
                <span>Halaman ${currentPage} dari ${totalPages}</span>
                <button onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? 'disabled' : ''} class="px-3 py-1 bg-gray-200 text-gray-700 rounded-md disabled:opacity-50">Selanjutnya</button>
            `;
        }
        paginationControls.innerHTML = paginationHtml;
    }

    function goToPage(page) {
        if (page < 1 || page > Math.ceil(allTransactions.length / itemsPerPage)) {
            return;
        }
        currentPage = page;
        renderTable();
        renderPagination();
    }

    document.addEventListener('DOMContentLoaded', fetchLaporan);
</script>
@endsection