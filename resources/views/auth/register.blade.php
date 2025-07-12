@extends('layouts.auth')

@section('content')
<div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-center text-gray-800">Register</h2>

    <form method="POST" action="{{ url('/api/register') }}" id="register-form" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required
                class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <button type="submit"
            class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-md transition cursor-pointer">Daftar</button>
    </form>

    <p class="text-sm text-gray-600 mt-4 text-center">
        Sudah punya akun? <a href="{{ url('/login') }}" class="text-blue-600 hover:underline">Login</a>
    </p>
</div>

<script>
    async function register(e) {
        e.preventDefault();
        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const role = document.getElementById("role").value;

        const res = await fetch("/api/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                name,
                email,
                password,
            }),
        });

        const data = await res.json();

        if (res.ok) {
            localStorage.setItem("token", data.token);
            localStorage.setItem("role", data.user.role);
            localStorage.setItem('user', data.user);
 
            if (data.user.role === "admin") {
                window.location.href = "/admin/dashboard";
            } else {
                window.location.href = "/user/dashboard";
            }
        } else {
            alert("Gagal register");
            console.error(data);
        }
    }
</script>
@endsection