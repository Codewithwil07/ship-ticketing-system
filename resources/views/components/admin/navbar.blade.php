<nav class="bg-white border-b border-gray-200 px-6 py-4 shadow-sm">
    <div class="flex justify-between items-center">
        <!-- Kiri -->
        <div class="text-2xl font-bold text-blue-600 tracking-tight">Admin Panel</div>

        <!-- Kanan -->
        <div class="flex items-center space-x-6">
            <button onclick="logout()" class="text-red-500 text-sm font-medium hover:underline hover:text-red-600 transition cursor-pointer flex items-center gap-1">
                <i data-lucide="log-out" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</nav>

<script>
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
            localStorage.removeItem('user');
            location.href = '/login';
        }).catch((e) => {
            console.log(e.message);
        });
    }
</script>