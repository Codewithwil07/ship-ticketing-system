@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md relative">
    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Register</h2>

    <form id="register-form" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
            class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md transition cursor-pointer">Daftar</button>
    </form>

    <p class="text-sm text-gray-600 mt-4 text-center">
        Sudah punya akun? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>

    <!-- Toast -->
    <div id="toast-container" class="absolute bottom-4 right-4 space-y-2 z-50"></div>
</div>

<script>
    const form = document.getElementById('register-form');

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `max-w-xs px-4 py-3 rounded shadow text-sm text-white ${
            type === 'error' ? 'bg-red-500' : 'bg-green-600'
        }`;
        toast.innerText = message;
        document.getElementById('toast-container').appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const name = document.getElementById("name").value.trim();
        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        const emailRegex = /^[^\s@]+@[^\s@]+\.(com|net|org|edu|gov|mil|int|io|id)$/i;

        if (!name || !email || !password) {
            return showToast('Semua field wajib diisi', 'error');
        }

        if (!emailRegex.test(email)) {
            return showToast('Gunakan email dengan domain internasional (.com, .net, dll)', 'error');
        }

        if (password.length < 8) {
            return showToast('Password minimal 8 karakter', 'error');
        }

        try {
            const res = await fetch("/api/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    name,
                    email,
                    password
                }),
            });

            const data = await res.json();

            if (res.ok) {
                showToast('Pendaftaran berhasil. Mengarahkan ke login...');
                setTimeout(() => window.location.href = "/login", 1500);
            } else {
                showToast(data.message || 'Registrasi gagal', 'error');
                console.error('Validation:', data.errors);
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan sistem', 'error');
        }
    });
</script>
@endsection