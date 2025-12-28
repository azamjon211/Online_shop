<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mening profilim - Alifshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .tab-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="gradient-bg text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <a href="/" class="text-2xl font-bold flex items-center space-x-2">
                <i class="fas fa-shopping-cart"></i>
                <span>Alifshop</span>
            </a>

            <div class="hidden md:flex items-center space-x-6">
                <a href="/" class="hover:text-gray-200 transition">
                    <i class="fas fa-home mr-1"></i> Bosh sahifa
                </a>
                <a href="/categories" class="hover:text-gray-200 transition">
                    <i class="fas fa-th-large mr-1"></i> Kategoriyalar
                </a>
                <a href="/products" class="hover:text-gray-200 transition">
                    <i class="fas fa-box mr-1"></i> Mahsulotlar
                </a>
            </div>

            <div class="flex items-center space-x-3">
                <a href="/profile" class="flex items-center space-x-2 bg-purple-800 px-4 py-2 rounded-lg hover:bg-purple-900 transition">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span id="user-name">Foydalanuvchi</span>
                </a>
                <a href="/cart" class="relative">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mx-auto px-4 py-8">

    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center space-x-6">
            <div class="h-24 w-24 rounded-full gradient-bg flex items-center justify-center text-white text-4xl font-bold">
                A
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800" id="profile-name">Foydalanuvchi</h1>
                <p class="text-gray-600" id="profile-email">email@example.com</p>
                <p class="text-purple-600 font-medium mt-1">
                    <i class="fas fa-star mr-1"></i> Oddiy mijoz
                </p>
            </div>
            <div class="ml-auto">
                <button onclick="logout()" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-sign-out-alt mr-2"></i> Chiqish
                </button>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-8">
        <div class="flex border-b">
            <button class="tab-button tab-active px-6 py-4 font-medium transition" data-tab="orders">
                <i class="fas fa-shopping-bag mr-2"></i> Buyurtmalarim
            </button>
            <button class="tab-button px-6 py-4 font-medium text-gray-600 hover:bg-gray-50 transition" data-tab="wishlist">
                <i class="fas fa-heart mr-2"></i> Sevimlilar
            </button>
            <button class="tab-button px-6 py-4 font-medium text-gray-600 hover:bg-gray-50 transition" data-tab="settings">
                <i class="fas fa-cog mr-2"></i> Sozlamalar
            </button>
        </div>

        <div class="p-6">

            <!-- Orders Tab -->
            <div id="orders-tab" class="tab-content">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Mening buyurtmalarim</h2>

                <div class="grid gap-4" id="orders-list">
                    <div class="text-center py-12">
                        <i class="fas fa-shopping-bag text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Buyurtmalar yo'q</h3>
                        <p class="text-gray-500 mb-6">Siz hali birorta buyurtma bermagansiz</p>
                        <a href="/products" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            <i class="fas fa-shopping-cart mr-2"></i> Xarid qilishni boshlash
                        </a>
                    </div>
                </div>
            </div>

            <!-- Wishlist Tab -->
            <div id="wishlist-tab" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Sevimli mahsulotlar</h2>

                <div class="grid gap-4" id="wishlist-list">
                    <div class="text-center py-12">
                        <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Sevimlilar bo'sh</h3>
                        <p class="text-gray-500 mb-6">Siz hali birorta mahsulotni sevimlilarga qo'shmagansiz</p>
                        <a href="/products" class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition">
                            <i class="fas fa-shopping-cart mr-2"></i> Mahsulotlarni ko'rish
                        </a>
                    </div>
                </div>
            </div>

            <!-- Settings Tab -->
            <div id="settings-tab" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Profilni tahrirlash</h2>

                <form id="update-profile-form" class="max-w-2xl">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Ism familiya
                            </label>
                            <input
                                type="text"
                                id="edit-name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Ism familiyangiz"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input
                                type="email"
                                id="edit-email"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="email@example.com"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Telefon
                            </label>
                            <input
                                type="tel"
                                id="edit-phone"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="+998 90 123 45 67"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Yangi parol (ixtiyoriy)
                            </label>
                            <input
                                type="password"
                                id="edit-password"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Yangi parol"
                            >
                        </div>

                    </div>

                    <div class="mt-6">
                        <button
                            type="submit"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-medium transition"
                        >
                            <i class="fas fa-save mr-2"></i> Saqlash
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    const API_URL = 'http://127.0.0.1:8000/api';

    function getToken() {
        return localStorage.getItem('token');
    }

    function getUser() {
        const user = localStorage.getItem('user');
        return user ? JSON.parse(user) : null;
    }

    function checkAuth() {
        const user = getUser();
        if (!user) {
            alert('Iltimos, tizimga kiring!');
            window.location.href = '/login';
            return false;
        }

        document.getElementById('user-name').textContent = user.name;
        document.getElementById('profile-name').textContent = user.name;
        document.getElementById('profile-email').textContent = user.email;

        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-phone').value = user.phone || '';

        return true;
    }

    async function logout() {
        const token = getToken();

        try {
            await fetch(`${API_URL}/auth/logout`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });
        } catch (error) {
            console.error('Logout error:', error);
        }

        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/';
    }

    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', () => {
            const tabName = button.dataset.tab;

            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('text-gray-600', 'hover:bg-gray-50');
            });

            button.classList.add('tab-active');
            button.classList.remove('text-gray-600', 'hover:bg-gray-50');

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`${tabName}-tab`).classList.remove('hidden');
        });
    });

    // Run on page load
    document.addEventListener('DOMContentLoaded', checkAuth);
</script>
