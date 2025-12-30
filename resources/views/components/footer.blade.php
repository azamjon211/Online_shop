{{-- resources/views/components/footer.blade.php --}}
<footer class="bg-gray-800 text-white mt-16">
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- About --}}
            <div>
                <h3 class="text-xl font-bold mb-4">AlifShop</h3>
                <p class="text-gray-400 text-sm mb-4">
                    O'zbekistondagi eng yirik onlayn do'konlardan biri. Biz sizga eng sifatli mahsulotlarni eng qulay narxlarda taqdim etamiz.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-600">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-400">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-pink-600">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-blue-500">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Tezkor havolalar</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white">Bosh sahifa</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white">Mahsulotlar</a></li>
                    <li><a href="{{ route('brands.index') }}" class="text-gray-400 hover:text-white">Brendlar</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Biz haqimizda</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Aloqa</a></li>
                </ul>
            </div>

            {{-- Customer Service --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Mijozlarga xizmat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="text-gray-400 hover:text-white">Yordam markazi</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Buyurtmani kuzatish</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Yetkazib berish</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Qaytarish siyosati</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">FAQ</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Bog'lanish</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-map-marker-alt mt-1 text-blue-500"></i>
                        <span class="text-gray-400">Toshkent, Yunusobod, 4-kv 7-dom</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-phone text-blue-500"></i>
                        <a href="tel:+998901234567" class="text-gray-400 hover:text-white">+998 94 608 81 09</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-envelope text-blue-500"></i>
                        <a href="mailto:info@alifshop.uz" class="text-gray-400 hover:text-white">info@alifshop.uz</a>
                    </li>
                </ul>

                {{-- Payment Methods --}}
                <div class="mt-6">
                    <p class="text-sm mb-2">To'lov usullari:</p>
                    <div class="flex gap-2">
                        <img src="/payment/uzcard.png" alt="Uzcard" class="h-8 bg-white rounded px-2">
                        <img src="/payment/humo.png" alt="Humo" class="h-8 bg-white rounded px-2">
                        <img src="/payment/payme.png" alt="Payme" class="h-8 bg-white rounded px-2">
                        <img src="/payment/click.png" alt="Click" class="h-8 bg-white rounded px-2">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-gray-700">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
                <p>&copy; 2024 AlifShop. Barcha huquqlar himoyalangan.</p>
                <div class="flex gap-4 mt-2 md:mt-0">
                    <a href="#" class="hover:text-white">Foydalanish shartlari</a>
                    <a href="#" class="hover:text-white">Maxfiylik siyosati</a>
                </div>
            </div>
        </div>
    </div>
</footer>
