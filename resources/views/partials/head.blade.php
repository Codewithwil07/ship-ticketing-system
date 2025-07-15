{{-- resources/views/partials/head.blade.php --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>E-Kapal</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])
<!-- @vite('resources/css/app.css') -->

<script src="https://unpkg.com/lucide@latest"></script>
<!-- Lucide Icons (Traveloka style icon) -->