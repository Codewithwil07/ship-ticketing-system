@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-sm text-gray-500 mt-1">Ringkasan sistem hari ini.</p>
</div>

{{-- CARD GRID --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
    ['label' => 'Total Kapal', 'value' => 12, 'icon' => 'ship', 'color' => 'blue'],
    ['label' => 'Jadwal Aktif', 'value' => 8, 'icon' => 'calendar-days', 'color' => 'green'],
    ['label' => 'Pemesanan', 'value' => 124, 'icon' => 'ticket', 'color' => 'purple'],
    ['label' => 'Pembayaran', 'value' => 97, 'icon' => 'credit-card', 'color' => 'orange'],
    ] as $item)
    <div class="bg-white border border-gray-100 p-4 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 group cursor-pointer">
        <div class="flex items-center space-x-4">
            <div class="p-3 rounded-full bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-600 transition-all group-hover:scale-110">
                <i data-lucide="{{ $item['icon'] }}" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500">{{ $item['label'] }}</p>
                <h3 class="text-xl font-semibold text-gray-800">{{ $item['value'] }}</h3>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- TABLE --}}
<div class="bg-white border border-gray-100 rounded-xl shadow-sm overflow-x-auto">
    <div class="flex justify-between items-center px-4 py-3 border-b">
        <h2 class="text-lg font-semibold text-gray-700">Pemesanan Terbaru</h2>
    </div>
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-600 uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">Kode</th>
                <th class="px-6 py-3">Penumpang</th>
                <th class="px-6 py-3">Kapal</th>
                <th class="px-6 py-3">Jadwal</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @foreach(range(1,5) as $i)
            <tr class="border-t hover:bg-gray-50 transition">
                <td class="px-6 py-3">ORD00{{ $i }}</td>
                <td class="px-6 py-3">Budi Santoso</td>
                <td class="px-6 py-3">KM Laut Biru</td>
                <td class="px-6 py-3">10 Juli 2025</td>
                <td class="px-6 py-3">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-600 font-medium">Lunas</span>
                </td>
                <td class="px-6 py-3 text-right">
                    <a href="#" class="inline-flex items-center text-sm text-blue-500 hover:underline hover:text-blue-700 transition gap-1">
                        <i data-lucide="eye" class="w-4 h-4"></i> Lihat
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        lucide.createIcons();
    });
</script>
@endsection