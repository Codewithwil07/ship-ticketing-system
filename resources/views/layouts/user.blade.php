<head>
    @include('partials.head')
</head>

<script>
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'user') {
        window.location.href = '/login';
    }
</script>

<script src="https://unpkg.com/lucide@latest"></script>

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .hero-bg {
        background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 800"><rect fill="%23667eea" width="1200" height="800"/><path d="M0,400 Q300,200 600,400 T1200,400 V800 H0 Z" fill="%23764ba2" opacity="0.5"/></svg>');
        background-size: cover;
        background-position: center;
    }
</style>

<body class="bg-gray-100 text-gray-800">
    <div class="flex">
        <main class="p-6 w-full">
            @yield('content')
        </main>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>

</html>

</html>