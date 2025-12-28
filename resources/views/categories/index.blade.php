<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriyalar - Alifshop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <a href="/" class="text-2xl font-bold flex items-center space-x-2">
                <i class="fas fa-shopping-cart"></i>
                <span>Alifshop</span>
            </a>

            <div class="flex items-center space-x-6">
                <a href="/" class="hover:text-gray-200">Bosh sahifa</a>
                <a href="/categories" class="hover:text-gray-200">Kategoriyalar</a>
                <a href="/products" class="hover:text-gray-200">Mahsulotlar</a>
                <a href="/login" class="bg-white text-purple-600 px-4 py-2 rounded-lg">Kirish</a>
            </div>
        </div>
    </div>
</nav>

<!-- Content -->
<div class="container mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-gray-800 mb-8">Kategoriyalar</h1>

    <div id="categories-grid" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <!-- Loading -->
        <div class="col-span-full text-center py-12">
            <i class="fas fa-spinner fa-spin text-4xl text-gray-400"></i>
            <p class="mt-4 text-gray-500">Yuklanmoqda...</p>
        </div>
    </div>
</div>

<script>
    const API_URL = 'http://127.0.0.1:8000/api';

    async function loadCategories() {
        try {
            const response = await fetch(`${API_URL}/categories`, {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();

            if (data.success) {
                displayCategories(data.data);
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('categories-grid').innerHTML = `
                    <div class="col-span-full text-center py-12 text-red-500">
                        <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                        <p>Xatolik yuz berdi</p>
                    </div>
                `;
        }
    }

    function displayCategories(categories) {
        const grid = document.getElementById('categories-grid');

        if (categories.length === 0) {
            grid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Kategoriyalar mavjud emas</p>
                    </div>
                `;
            return;
        }

        grid.innerHTML = categories.filter(cat => !cat.parent_id).map(cat => `
                <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition p-6 cursor-pointer">
                    <div class="text-center">
                        <div class="h-20 w-20 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-th-large text-purple-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">${cat.name}</h3>
                        <p class="text-gray-500 text-sm">${cat.description || ''}</p>
                        <a href="/categories/${cat.slug}" class="inline-block mt-4 text-purple-600 hover:text-purple-700 font-medium">
                            Ko'proq <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            `).join('');
    }

    document.addEventListener('DOMContentLoaded', loadCategories);
</script>

</body>
</html>
