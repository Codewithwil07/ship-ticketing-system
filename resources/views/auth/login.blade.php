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


    <p class="text-red-600 left-0 right-0 text-center mt-10 absolute" id="error"></p>

    <p class="text-sm text-gray-600 mt-4 text-center">
        Belum punya akun? <a href="{{ url('/register') }}" class="text-blue-600 hover:underline">Daftar</a>
    </p>
</div>

<script>
    const error = document.querySelector('#error')
    document.querySelector('#login-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const form = e.target;
        const data = {
            email: form.email.value,
            password: form.password.value
        };

        const res = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();

        if (res.ok) {
            localStorage.setItem('token', result.access_token);
            localStorage.setItem('role', result.user.role);
            localStorage.setItem('user', JSON.stringify(result.user));
            const role = result.user?.role || 'user';
            location.href = role === 'admin' ? '/admin/dashboard' : '/';
        } else {
            const errorMessage = result.message || 'Username atau password salah';
            error.innerText = errorMessage;
        }
    });
</script>
@endsection