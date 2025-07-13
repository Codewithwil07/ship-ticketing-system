<head>
    @include('partials.head')
</head>
<!-- 
<script>
    const token = localStorage.getItem('token');
    const role = localStorage.getItem('role');

    if (!token || role !== 'user') {
        window.location.href = '/login';
    }
</script> -->


<script src="https://unpkg.com/lucide@latest"></script>

<body class="bg-gray-50">
    @yield('content')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
</body>

</html>

</html>