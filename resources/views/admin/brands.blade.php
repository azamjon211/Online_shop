{{-- resources/views/admin/brands.blade.php --}}
@extends('layouts.admin')

@section('title', 'Brendlar')
@section('page-title', 'Brendlar')

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Barcha brendlar</h3>
            <button onclick="openCreateModal()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                <i class="fas fa-plus mr-2"></i> Yangi brend
            </button>
        </div>

        <div class="p-6">
            <table class="w-full">
                <thead>
                <tr class="text-left text-gray-500 text-sm border-b">
                    <th class="pb-3">ID</th>
                    <th class="pb-3">Logo</th>
                    <th class="pb-3">Nom</th>
                    <th class="pb-3">Slug</th>
                    <th class="pb-3">Mahsulotlar</th>
                    <th class="pb-3">Holat</th>
                    <th class="pb-3">Amallar</th>
                </tr>
                </thead>
                <tbody>
                @foreach($brands as $brand)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-4">{{ $brand->id }}</td>
                        <td class="py-4">
                            @if($brand->logo)
                                <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-12 object-contain">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="py-4 font-medium">{{ $brand->name }}</td>
                        <td class="py-4 text-gray-500">{{ $brand->slug }}</td>
                        <td class="py-4">{{ $brand->products_count }}</td>
                        <td class="py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $brand->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $brand->is_active ? 'Faol' : 'Nofaol' }}
                        </span>
                        </td>
                        <td class="py-4">
                            <button onclick="editBrand({{ $brand->id }})" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.brands.destroy', $brand->id) }}" method="POST" class="inline" onsubmit="return confirm('Ishonchingiz komilmi?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $brands->links() }}
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    <div id="brandModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold" id="modalTitle">Yangi brend</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="brandForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="methodField"></div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Nom *</label>
                    <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Slug</label>
                    <input type="text" name="slug" class="w-full border rounded px-3 py-2">
                    <p class="text-xs text-gray-500 mt-1">Bo'sh qoldirilsa avtomatik yaratiladi</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Tavsif</label>
                    <textarea name="description" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                </div>

                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                        <span class="text-sm">Faol</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded hover:bg-gray-50">
                        Bekor qilish
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                        Saqlash
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function openCreateModal() {
            document.getElementById('modalTitle').textContent = 'Yangi brend';
            document.getElementById('brandForm').action = '{{ route("admin.brands.store") }}';
            document.getElementById('methodField').innerHTML = '';
            document.getElementById('brandForm').reset();
            document.getElementById('brandModal').classList.remove('hidden');
        }

        function editBrand(id) {
            // AJAX bilan brand ma'lumotlarini olish va formaga to'ldirish
            fetch(`/admin/brands/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('modalTitle').textContent = 'Brendni tahrirlash';
                    document.getElementById('brandForm').action = `/admin/brands/${id}`;
                    document.getElementById('methodField').innerHTML = '@method("PUT")';

                    // Formaga ma'lumotlarni to'ldirish
                    document.querySelector('[name="name"]').value = data.name;
                    document.querySelector('[name="slug"]').value = data.slug;
                    document.querySelector('[name="description"]').value = data.description || '';
                    document.querySelector('[name="is_active"]').checked = data.is_active;

                    document.getElementById('brandModal').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('brandModal').classList.add('hidden');
        }
    </script>
@endpush
