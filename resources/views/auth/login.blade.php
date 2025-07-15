@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md relative">
    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Login</h2>

    <form id="login-form" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
                class="mt-1 w-full px-3 py-2 border rounded-md text-black focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
                class="mt-1 w-full px-3 py-2 border rounded-md text-black focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
            class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition cursor-pointer">Login</button>
    </form>

    <p class="text-sm text-gray-600 mt-4 text-center">
        Belum punya akun? <a href="{{ url('/register') }}" class="text-blue-600 hover:underline">Daftar</a>
    </p>

    <!-- Toast -->
    <div id="toast-container" class="absolute bottom-4 right-4 space-y-2 z-50"></div>
</div>

<script>
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `max-w-xs px-4 py-3 rounded shadow text-sm text-white ${
            type === 'error' ? 'bg-red-500' : 'bg-green-600'
        }`;
        toast.innerText = message;
        document.getElementById('toast-container').appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    document.querySelector('#login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = {
            email: form.email.value.trim(),
            password: form.password.value.trim()
        };

        try {
            const res = await fetch('/api/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (res.ok) {
                localStorage.setItem('token', result.access_token);
                localStorage.setItem('role', result.user.role);
                localStorage.setItem('user', JSON.stringify(result.user));

                showToast('Login berhasil. Mengarahkan...');

                const role = result.user?.role || 'user';
                setTimeout(() => {
                    location.href = role === 'admin' ? '/admin/dashboard' : '/';
                }, 1500);
            } else {
                const msg = result.message || 'Email atau password salah';
                showToast(msg, 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Terjadi kesalahan saat login', 'error');
        }
    });
</script>
@endsection