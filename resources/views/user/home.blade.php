@extends('layouts.user')
<!-- Navigation -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center space-x-4">
                <i data-lucide="ship" class="w-8 h-8 text-blue-600"></i>
                <span class="text-xl font-bold text-gray-800">KM Bung Tomo</span>
            </div>
            <div class="hidden md:flex items-center space-x-8">
                <a href="#beranda" class="text-gray-700 hover:text-blue-600 transition-colors">Beranda</a>
                <a href="#jadwal" class="text-gray-700 hover:text-blue-600 transition-colors">Jadwal</a>
                <a href="#pemesanan" class="text-gray-700 hover:text-blue-600 transition-colors">Pemesanan</a>
                <a href="#tentang" class="text-gray-700 hover:text-blue-600 transition-colors">Tentang</a>
            </div>
            <div class="flex items-center space-x-4">
                <button class="text-gray-700 hover:text-blue-600 transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Masuk
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-bg text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">
            Jelajahi Nusantara dengan KM Bung Tomo
        </h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">
            Nikmati perjalanan laut yang aman dan nyaman ke seluruh Indonesia
        </p>
        <button class="bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-lg text-lg font-semibold transition-colors">
            Pesan Tiket Sekarang
        </button>
    </div>
</section>

<!-- Booking Form -->
<section class="py-16 bg-white" id="pemesanan">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl p-8 -mt-20 relative z-10">
                <h2 class="text-2xl font-bold text-gray-800 mb-8 text-center">Cari & Pesan Tiket Kapal</h2>

                <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- Dari -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari</label>
                        <div class="relative">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Surabaya</option>
                                <option>Jakarta</option>
                                <option>Makassar</option>
                                <option>Balikpapan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ke -->
                    <div class="lg:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ke</label>
                        <div class="relative">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Makassar</option>
                                <option>Jakarta</option>
                                <option>Surabaya</option>
                                <option>Balikpapan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <div class="relative">
                            <i data-lucide="calendar" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                            <input type="date" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4 mb-6">
                    <!-- Penumpang -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penumpang</label>
                        <div class="relative">
                            <i data-lucide="users" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>1 Penumpang</option>
                                <option>2 Penumpang</option>
                                <option>3 Penumpang</option>
                                <option>4 Penumpang</option>
                            </select>
                        </div>
                    </div>

                    <!-- Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <div class="relative">
                            <i data-lucide="star" class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>
                            <select class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option>Ekonomi</option>
                                <option>Bisnis</option>
                                <option>VIP</option>
                            </select>
                        </div>
                    </div>

                    <!-- Button -->
                    <div class="flex items-end">
                        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-colors">
                            <i data-lucide="search" class="w-5 h-5 inline mr-2"></i>
                            Cari Tiket
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Jadwal Section -->
<section class="py-16 bg-gray-50" id="jadwal">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Jadwal Keberangkatan</h2>

        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                    <h3 class="text-xl font-semibold">Surabaya - Makassar</h3>
                    <p class="opacity-90">Jumat, 12 Juli 2025</p>
                </div>

                <div class="p-6">
                    <div class="space-y-4">
                        <!-- Jadwal Item -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">08:00</div>
                                        <div class="text-sm text-gray-500">Surabaya</div>
                                    </div>
                                    <div class="flex-1 relative">
                                        <div class="border-t-2 border-dashed border-gray-300 mx-4"></div>
                                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white rounded-full p-1">
                                            <i data-lucide="ship" class="w-3 h-3"></i>
                                        </div>
                                        <div class="text-center text-xs text-gray-500 mt-1">18j 30m</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">02:30</div>
                                        <div class="text-sm text-gray-500">Makassar</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">Rp 450.000</div>
                                    <div class="text-sm text-gray-500">Ekonomi</div>
                                    <button class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Pilih
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal Item 2 -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">14:00</div>
                                        <div class="text-sm text-gray-500">Surabaya</div>
                                    </div>
                                    <div class="flex-1 relative">
                                        <div class="border-t-2 border-dashed border-gray-300 mx-4"></div>
                                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white rounded-full p-1">
                                            <i data-lucide="ship" class="w-3 h-3"></i>
                                        </div>
                                        <div class="text-center text-xs text-gray-500 mt-1">18j 30m</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">08:30</div>
                                        <div class="text-sm text-gray-500">Makassar</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">Rp 650.000</div>
                                    <div class="text-sm text-gray-500">Bisnis</div>
                                    <button class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Pilih
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Jadwal Item 3 -->
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center space-x-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">20:00</div>
                                        <div class="text-sm text-gray-500">Surabaya</div>
                                    </div>
                                    <div class="flex-1 relative">
                                        <div class="border-t-2 border-dashed border-gray-300 mx-4"></div>
                                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-blue-600 text-white rounded-full p-1">
                                            <i data-lucide="ship" class="w-3 h-3"></i>
                                        </div>
                                        <div class="text-center text-xs text-gray-500 mt-1">18j 30m</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-800">14:30</div>
                                        <div class="text-sm text-gray-500">Makassar</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">Rp 850.000</div>
                                    <div class="text-sm text-gray-500">VIP</div>
                                    <button class="mt-2 bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors">
                                        Pilih
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Memilih KM Bung Tomo?</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8 text-blue-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Keamanan Terjamin</h3>
                <p class="text-gray-600">Kapal modern dengan standar keamanan internasional dan awak kapal berpengalaman.</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="clock" class="w-8 h-8 text-green-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Tepat Waktu</h3>
                <p class="text-gray-600">Jadwal keberangkatan dan kedatangan yang akurat dengan sistem navigasi canggih.</p>
            </div>

            <div class="text-center p-6">
                <div class="bg-orange-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="wifi" class="w-8 h-8 text-orange-600"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Fasilitas Lengkap</h3>
                <p class="text-gray-600">WiFi gratis, AC, mushola, kantin, dan fasilitas hiburan untuk perjalanan nyaman.</p>
            </div>
        </div>
    </div>
</section>

<!-- Booking Process -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Cara Pemesanan</h2>

        <div class="max-w-4xl mx-auto">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">1</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Pilih Jadwal</h3>
                    <p class="text-gray-600">Tentukan rute dan tanggal keberangkatan yang diinginkan</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">2</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Isi Data</h3>
                    <p class="text-gray-600">Masukkan data penumpang dan pilih kelas yang diinginkan</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">3</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Bayar</h3>
                    <p class="text-gray-600">Lakukan pembayaran melalui berbagai metode yang tersedia</p>
                </div>

                <div class="text-center">
                    <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4 text-xl font-bold">4</div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Berangkat</h3>
                    <p class="text-gray-600">Tunjukkan tiket elektronik dan nikmati perjalanan Anda</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
    <div class="container mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center space-x-2 mb-4">
                    <i data-lucide="ship" class="w-8 h-8 text-blue-400"></i>
                    <span class="text-xl font-bold">KM Bung Tomo</span>
                </div>
                <p class="text-gray-400 mb-4">
                    Melayani perjalanan laut yang aman dan nyaman ke seluruh Indonesia dengan armada kapal modern.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Layanan</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Pemesanan Tiket</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Jadwal Kapal</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Cek Status</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Bantuan</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Perusahaan</h3>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="#" class="hover:text-white transition-colors">Tentang Kami</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Karir</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Berita</a></li>
                    <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                <div class="space-y-2 text-gray-400">
                    <div class="flex items-center space-x-2">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>+62 31 123-4567</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>info@kmgungtomo.com</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Pelabuhan Tanjung Perak, Surabaya</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
            <p>&copy; 2025 PT Subsea Lintas Globalindo. All rights reserved.</p>
        </div>
    </div>
</footer>

<script>
    // Initialize Lucide icons
    lucide.createIcons();

    // Smooth scrolling for navigation links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add animation to cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all cards
    document.querySelectorAll('.card-shadow, .bg-white').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.6s ease-out';
        observer.observe(card);
    });

    // Mobile menu toggle
    const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.querySelector('.mobile-menu');

    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>