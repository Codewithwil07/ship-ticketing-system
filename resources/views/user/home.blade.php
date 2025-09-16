@extends('layouts.user')
<!-- Header -->
@section('content')
<header class="absolute top-0 left-0 w-full z-40">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fas fa-ship text-2xl text-blue-400"></i>
                <h1 class="text-2xl font-bold text-white">Sub<span class="text-blue-300">sea</span></h1>
            </div>
            <div class="flex items-center">
                <nav class="hidden md:flex space-x-6 items-center">
                    <a href="#" class="text-gray-200 hover:text-blue-300">Beranda</a>
                    <a href="{{route('user.tiket')}}" class="text-gray-200 hover:text-blue-300" id="riwayat-tiket">Tiket</a>
                    <a class="bg-red-500 px-3 py-1.5 text-white shadow-md font-bold rounded-lg cursor-pointer" id="logout" onclick="logout()">Logout</a>
                </nav>
                <a href="/login" id="login">
                    <button class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 cursor-pointer shadow-md font-bold cursor-pointer">
                        Masuk
                    </button>
                </a>
            </div>
        </div>
    </div>
</header>
<section class="relative bg-cover bg-center h-screen" style="background-image: url('https://images.unsplash.com/photo-1707378175207-f64220bd7cdc?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cGVsYWJ1aGFufGVufDB8fDB8fHww');">
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="relative z-10 h-full flex items-center justify-center pt-20">s
        <div class="max-w-7xl mx-auto px-4 text-white">
            <div class="text-center">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">
                    Jelajahi Nusantara dengan Kapal
                </h2>
                <p class="text-xl text-gray-200 max-w-2xl mx-auto">
                    Nikmati perjalanan laut lintas pulau yang nyaman dan aman.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- Filter Section -->
<section class="max-w-7xl mx-auto px-4 py-8">
    <div class="flex flex-wrap items-center justify-between bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center space-x-4">
            <h3 class="text-lg font-semibold text-gray-900">Jadwal Keberangkatan</h3>
            <div id="resultsCount" class="text-sm text-gray-500">Memuat...</div>
        </div>
        <div class="flex items-center space-x-4">
            <button class="flex items-center space-x-2 text-gray-600 hover:text-blue-600">
                <i class="fas fa-filter"></i>
                <span>Filter</span>
            </button>
            <select id="sortBy" class="border border-gray-300 rounded-md px-3 py-2 text-sm" onchange="sortSchedules()">
                <option value="price_asc">Harga Terendah</option>
                <option value="price_desc">Harga Tertinggi</option>
                <option value="departure_asc">Waktu Berangkat</option>
                <option value="date_asc">Tanggal Berangkat</option>
            </select>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
        <p class="text-gray-500">Memuat jadwal keberangkatan...</p>
    </div>

    <!-- Schedule Results -->
    <div id="scheduleResults" class="hidden space-y-4">
        <!-- Results will be populated here -->
    </div>

    <!-- No Results -->
    <div id="noResults" class="hidden text-center py-12">
        <i class="fas fa-ship text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada jadwal ditemukan</h3>
        <p class="text-gray-500">Coba ubah kriteria pencarian Anda</p>
    </div>
</section>



<!-- Footer -->
<footer class="bg-gray-800 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <i class="fas fa-ship text-2xl text-blue-400"></i>
                    <h3 class="text-xl font-bold">SeaBooking</h3>
                </div>
                <p class="text-gray-400">Platform booking kapal terpercaya untuk perjalanan laut yang nyaman dan aman.</p>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Layanan</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">Booking Kapal</a></li>
                    <li><a href="#" class="hover:text-white">Reschedule</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Bantuan</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white">FAQ</a></li>
                    <li><a href="#" class="hover:text-white">Kontak</a></li>
                    <li><a href="#" class="hover:text-white">Syarat & Ketentuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">Kontak</h4>
                <div class="space-y-2 text-gray-400">
                    <p><i class="fas fa-phone mr-2"></i> +62 21 123-4567</p>
                    <p><i class="fas fa-envelope mr-2"></i> info@seabooking.com</p>
                    <p><i class="fas fa-map-marker-alt mr-2"></i> Jakarta, Indonesia</p>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 SeaBooking. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    let allSchedules = [];
    let filteredSchedules = [];

    // Set default date to today
    // document.getElementById('date').valueAsDate = new Date();

    // Fetch schedules on page load
    document.addEventListener('DOMContentLoaded', function() {
        fetchSchedules();
    });


    const riwayat = document.getElementById('riwayat-pemesanan')
    const loginBtn = document.getElementById('login')
    const logoutBtn = document.getElementById('logout')
    const token = localStorage.getItem('token');

    if (token) {
        loginBtn.classList.add('hidden')
        logoutBtn.classList.add('block')
    } else {
        loginBtn.classList.add('block')
        logoutBtn.classList.add('hidden')
    }


    async function fetchSchedules() {
        try {
            showLoading();

            const response = await fetch('http://127.0.0.1:8000/api/user/jadwal-keberangkatan', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch schedules');
            }

            const data = await response.json();
            allSchedules = data;
            filteredSchedules = data;

            displaySchedules(filteredSchedules);
            updateResultsCount(filteredSchedules.length);

        } catch (error) {
            console.error('Error fetching schedules:', error);
            showError('Gagal memuat data jadwal. Silakan coba lagi.');
        }
    }

    function displaySchedules(schedules) {
        const resultsContainer = document.getElementById('scheduleResults');
        const loadingState = document.getElementById('loadingState');
        const noResults = document.getElementById('noResults');

        loadingState.classList.add('hidden');

        if (schedules.length === 0) {
            resultsContainer.classList.add('hidden');
            noResults.classList.remove('hidden');
            return;
        }

        noResults.classList.add('hidden');
        resultsContainer.classList.remove('hidden');

        resultsContainer.innerHTML = schedules.map(schedule => `
                <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex-1 py-5">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg">
                                    <i class="fas fa-ship text-2xl text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg text-gray-900">${schedule.kapal.nama_kapal}</h3>
                                    <p class="text-sm text-gray-500">${schedule.kapal.kode_kapal} - ${schedule.kapal.tipe}</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    <div class="flex items-center gap-x-1">
                                        <p class="text-sm text-gray-500">Home Base:</p>
                                        <p class="font-medium">${schedule.kapal.home_base}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <div class="flex items-center gap-x-1">
                                        <p class="text-sm text-gray-500">Asal:</p>
                                        <p class="font-medium">${schedule.asal}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <div class="flex items-center gap-x-1">
                                        <p class="text-sm text-gray-500">Tujuan:</p>
                                        <p class="font-medium">${schedule.tujuan}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-route text-gray-400"></i>
                                    <div class="flex items-center gap-x-1">
                                        <p class="text-sm text-gray-500">Rute:</p>
                                        <p class="font-medium">${schedule.kapal.rute}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-6 text-sm text-gray-600">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-calendar"></i>
                                    <span>${formatDate(schedule.tanggal_berangkat)}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-clock"></i>
                                    <span>${formatTime(schedule.jam_berangkat)}</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-users"></i>
                                    <span>${schedule.kapal.kapasitas} kursi</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-building"></i>
                                    <span>${schedule.kapal.operator}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 lg:mt-0 lg:ml-6 flex flex-col items-end">
                            <div class="text-right mb-4 flex items-center">
                                <p class="text-2xl font-bold text-blue-600">${formatPrice(schedule.harga)}</p>
                                <p class="text-sm text-gray-500">/per penumpang</p>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button onclick="bookSchedule(${schedule.id})" 
                                        class="bg-blue-600 text-white px-6 py-2 cursor-pointer rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                                    <span>Pilih</span>
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
    }

    function showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('scheduleResults').classList.add('hidden');
        document.getElementById('noResults').classList.add('hidden');
    }

    function showError(message) {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('scheduleResults').classList.add('hidden');
        document.getElementById('noResults').classList.remove('hidden');
        document.getElementById('noResults').innerHTML = `
                <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Terjadi Kesalahan</h3>
                <p class="text-gray-500">${message}</p>
            `;
    }

    function searchSchedules() {
        const homeBase = document.getElementById('homeBase').value.toLowerCase();
        const destination = document.getElementById('destination').value.toLowerCase();
        const date = document.getElementById('date').value;

        filteredSchedules = allSchedules.filter(schedule => {
            const matchesHomeBase = !homeBase || schedule.kapal.home_base.toLowerCase().includes(homeBase);
            const matchesDestination = !destination || schedule.tujuan.toLowerCase().includes(destination);
            const matchesDate = !date || schedule.tanggal_berangkat === date;

            return matchesHomeBase && matchesDestination && matchesDate;
        });

        displaySchedules(filteredSchedules);
        updateResultsCount(filteredSchedules.length);
    }

    function sortSchedules() {
        const sortBy = document.getElementById('sortBy').value;

        const sorted = [...filteredSchedules].sort((a, b) => {
            switch (sortBy) {
                case 'price_asc':
                    return a.kapal.harga - b.kapal.harga;
                case 'price_desc':
                    return b.kapal.harga - a.kapal.harga;
                case 'departure_asc':
                    return a.jam_berangkat.localeCompare(b.jam_berangkat);
                case 'date_asc':
                    return a.tanggal_berangkat.localeCompare(b.tanggal_berangkat);
                default:
                    return 0;
            }
        });

        displaySchedules(sorted);
    }

    function updateResultsCount(count) {
        document.getElementById('resultsCount').textContent = `${count} jadwal ditemukan`;
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(price);
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    }

    function formatTime(timeString) {
        const time = new Date(`1970-01-01T${timeString}`);
        return time.toLocaleTimeString('id-ID', {
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function bookSchedule(id) {
        const setId = localStorage.setItem('id', id)
        window.location.href = `/booking`;
    }

    function logout() {
        const token = localStorage.getItem('token');
        fetch('/api/logout', {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        }).then(() => {
            localStorage.removeItem('token');
            localStorage.removeItem('role');
            localStorage.removeItem('id');
            localStorage.removeItem('user');
            location.href = '/login';
        }).catch((e) => {
            console.log(e.message);
        });
    }
</script>