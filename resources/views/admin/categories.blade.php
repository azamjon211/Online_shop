<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriyalar - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .sidebar-link:hover { background-color: #f3f4f6; }
        .sidebar-link.active:hover { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    </style>
</head>
<body class="bg-gray-100">

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-lg flex-shrink-0">
        <div class="p-6 bg-gradient-to-r from-purple-600 to-purple-800">
            <h1 class="text-2xl font-bold text-white">
                <i class="fas fa-shopping-cart mr-2"></i>
                Alifshop Admin
            </h1>
        </div>

        <nav class="mt-6">
            <a href="/admin" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-home w-6"></i>
                <span>Dashboard</span>
            </a>

            <a href="/admin/categories" class="sidebar-link active flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-th-large w-6"></i>
                <span>Kategoriyalar</span>
            </a>

            <a href="/admin/brands" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-tags w-6"></i>
                <span>Brendlar</span>
            </a>

            <a href="/admin/products" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-box w-6"></i>
                <span>Mahsulotlar</span>
            </a>

            <a href="/admin/orders" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-shopping-bag w-6"></i>
                <span>Buyurtmalar</span>
            </a>

            <a href="/admin/users" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-users w-6"></i>
                <span>Foydalanuvchilar</span>
            </a>

            <hr class="my-4">

            <a href="/" class="sidebar-link flex items-center px-6 py-3 text-gray-700 transition">
                <i class="fas fa-globe w-6"></i>
                <span>Saytga o'tish</span>
            </a>

            <button onclick="logout()" class="sidebar-link flex items-center px-6 py-3 text-red-600 w-full text-left transition">
                <i class="fas fa-sign-out-alt w-6"></i>
                <span>Chiqish</span>
            </button>
        </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto">

        <!-- Top Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex items-center justify-between px-8 py-4">
                <h2 class="text-2xl font-bold text-gray-800">Kategoriyalar</h2>

                <button onclick="openAddModal()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i> Yangi kategoriya
                </button>
            </div>
        </header>

        <!-- Content -->
        <main class="p-8">

            <!-- Categories Table -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Holati</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amallar</th>
                        </tr>
                        </thead>
                        <tbody id="categories-table" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-spinner fa-spin text-2xl"></i>
                                <p class="mt-2">Yuklanmoqda...</p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>

    </div>

</div>

<!-- Add/Edit Modal -->
<div id="category-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <h3 class="text-2xl font-bold mb-6" id="modal-title">Yangi kategoriya</h3>

        <form id="category-form">
            <input type="hidden" id="category-id">

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Nomi *</label>
                <input type="text" id="category-name" class="w-full px-4 py-2 border rounded-lg" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Tavsif</label>
                <textarea id="category-description" class="w-full px-4 py-2 border rounded-lg" rows="3"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Parent kategoriya</label>
                <select id="category-parent" class="w-full px-4 py-2 border rounded-lg">
                    <option value="">Yo'q (Asosiy kategoriya)</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" id="category-active" checked class="mr-2">
                    <span class="text-gray-700">Aktiv</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal()" class="px-6 py-2 border rounded-lg hover:bg-gray-50">
                    Bekor qilish
                </button>
                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg">
                    Saqlash
                </button>
            </div>
        </form>
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

    function checkAdmin() {
        const user = getUser();
        if (!user || user.role !== 'admin') {
            window.location.href = '/login';
            return false;
        }
        return true;
    }

    async function logout() {
        const token = getToken();
        try {
            await fetch(`${API_URL}/auth/logout`, {
                method: 'POST',
                headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
            });
        } catch (error) {
            console.error('Logout error:', error);
        }
        localStorage.removeItem('token');
        localStorage.removeItem('user');
        window.location.href = '/login';
    }

    async function loadCategories() {
        try {
            const response = await fetch(`${API_URL}/categories`, {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();

            if (data.success) {
                displayCategories(data.data);
                populateParentSelect(data.data);
            }
        } catch (error) {
            console.error('Error:', error);
            document.getElementById('categories-table').innerHTML = `
                <tr><td colspan="6" class="px-6 py-8 text-center text-red-500">
                    Xatolik: ${error.message}
                </td></tr>
            `;
        }
    }

    function displayCategories(categories) {
        const tbody = document.getElementById('categories-table');

        if (categories.length === 0) {
            tbody.innerHTML = `
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                    <p>Kategoriyalar mavjud emas</p>
                </td></tr>
            `;
            return;
        }

        tbody.innerHTML = categories.map(cat => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 text-sm">${cat.id}</td>
                <td class="px-6 py-4 text-sm font-medium">${cat.name}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${cat.slug}</td>
                <td class="px-6 py-4 text-sm text-gray-500">${cat.parent_id || '-'}</td>
                <td class="px-6 py-4 text-sm">
                    ${cat.is_active
            ? '<span class="px-3 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">Aktiv</span>'
            : '<span class="px-3 py-1 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Nofaol</span>'
        }
                </td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="editCategory(${cat.id})" class="text-blue-600 hover:text-blue-800 mr-3">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteCategory(${cat.id})" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function populateParentSelect(categories) {
        const select = document.getElementById('category-parent');
        select.innerHTML = '<option value="">Yo\'q (Asosiy kategoriya)</option>';
        categories.forEach(cat => {
            if (!cat.parent_id) {
                select.innerHTML += `<option value="${cat.id}">${cat.name}</option>`;
            }
        });
    }

    function openAddModal() {
        document.getElementById('modal-title').textContent = 'Yangi kategoriya';
        document.getElementById('category-form').reset();
        document.getElementById('category-id').value = '';
        document.getElementById('category-active').checked = true;
        document.getElementById('category-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('category-modal').classList.add('hidden');
    }

    async function editCategory(id) {
        alert('Tahrirlash keyinroq qo\'shiladi. ID: ' + id);
    }

    async function deleteCategory(id) {
        if (!confirm('Kategoriyani o\'chirishni xohlaysizmi?')) return;

        const token = getToken();
        try {
            const response = await fetch(`${API_URL}/categories/${id}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                alert('Kategoriya o\'chirildi!');
                loadCategories();
            } else {
                alert('Xato: ' + data.message);
            }
        } catch (error) {
            alert('Xatolik yuz berdi!');
            console.error(error);
        }
    }

    document.getElementById('category-form').addEventListener('submit', async (e) => {
        e.preventDefault();

        const token = getToken();
        const id = document.getElementById('category-id').value;
        const name = document.getElementById('category-name').value;
        const description = document.getElementById('category-description').value;
        const parent_id = document.getElementById('category-parent').value || null;
        const is_active = document.getElementById('category-active').checked;

        try {
            const response = await fetch(`${API_URL}/categories`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name, description, parent_id, is_active })
            });

            const data = await response.json();

            if (data.success) {
                alert('Kategoriya saqlandi!');
                closeModal();
                loadCategories();
            } else {
                alert('Xato: ' + data.message);
            }
        } catch (error) {
            alert('Xatolik yuz berdi!');
            console.error(error);
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        if (checkAdmin()) {
            loadCategories();
        }
    });
</script>

</body>
</html>
