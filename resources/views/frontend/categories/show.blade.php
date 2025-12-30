{{-- resources/views/frontend/categories/show.blade.php --}}
@extends('layouts.app')

@section('title', $category->name)

@section('content')

    {{-- Breadcrumb --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Bosh sahifa</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Kategoriyalar</a></li>
                @if($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $category->parent->slug) }}">
                            {{ $category->parent->name }}
                        </a>
                    </li>
                @endif
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>

    {{-- Category Header --}}
    <div class="bg-light py-4 mb-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    @if($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}"
                             alt="{{ $category->name }}"
                             class="rounded me-3"
                             style="width: 80px; height: 80px; object-fit: cover;">
                    @endif

                    <div class="d-inline-block align-middle">
                        <h1 class="mb-2">{{ $category->name }}</h1>
                        @if($category->description)
                            <p class="text-muted mb-0">{{ $category->description }}</p>
                        @endif
                        <small class="text-muted">
                            <i class="fas fa-box me-1"></i>
                            {{ $category->products_count }} ta mahsulot
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Subcategories --}}
    @if($category->children && $category->children->count() > 0)
        <div class="container mb-4">
            <h5 class="fw-bold mb-3">
                <i class="fas fa-layer-group me-2"></i>
                Subkategoriyalar
            </h5>
            <div class="row g-3">
                @foreach($category->children as $child)
                    <div class="col-6 col-md-3 col-lg-2">
                        <a href="{{ route('categories.show', $child->slug) }}"
                           class="card text-center text-decoration-none shadow-sm hover-card h-100">
                            <div class="card-body">
                                @if($child->image)
                                    <img src="{{ asset('storage/' . $child->image) }}"
                                         alt="{{ $child->name }}"
                                         class="mb-2"
                                         style="width: 48px; height: 48px; object-fit: contain;">
                                @else
                                    <div class="bg-primary bg-opacity-10 rounded-circle mx-auto mb-2"
                                         style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fas fa-tag text-primary"></i>
                                    </div>
                                @endif
                                <h6 class="card-title mb-1 small">{{ $child->name }}</h6>
                                <small class="text-muted">{{ $child->products_count }}</small>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Products Section --}}
    <div class="container mb-5">
        @if($products->count() > 0)
            {{-- Filters & Sorting --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="mb-0">Mahsulotlar</h5>
                    <small class="text-muted">{{ $products->total() }} ta mahsulot topildi</small>
                </div>

                <div class="d-flex gap-2">
                    {{-- Sort Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle"
                                type="button"
                                data-bs-toggle="dropdown">
                            <i class="fas fa-sort me-1"></i> Tartiblash
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'price_asc']) }}">
                                    Arzon → Qimmat
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'price_desc']) }}">
                                    Qimmat → Arzon
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'newest']) }}">
                                    Yangi mahsulotlar
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('categories.show', ['slug' => $category->slug, 'sort' => 'popular']) }}">
                                    Ommabop
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- View Type --}}
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" onclick="setView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Products Grid --}}
            <div class="row g-3" id="products-grid">
                @foreach($products as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        <x-product-card :product="$product" />
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-box-open text-muted" style="font-size: 5rem;"></i>
                <h3 class="mt-3">Mahsulotlar topilmadi</h3>
                <p class="text-muted mb-4">Bu kategoriyada hozircha mahsulotlar yo'q</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Boshqa mahsulotlar
                </a>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        function setView(view) {
            const grid = document.getElementById('products-grid');

            if (view === 'list') {
                grid.classList.remove('row-cols-md-4', 'row-cols-lg-5');
                grid.classList.add('row-cols-1');

                // Update button states
                document.querySelectorAll('.btn-group button').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('button').classList.add('active');
            } else {
                grid.classList.remove('row-cols-1');
                grid.classList.add('row-cols-md-4', 'row-cols-lg-5');

                // Update button states
                document.querySelectorAll('.btn-group button').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.closest('button').classList.add('active');
            }
        }
    </script>
@endpush

@push('styles')
    <style>
        .hover-card {
            transition: all 0.3s ease;
        }
        .hover-card:hover {
            transform: scale(1.05);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
    </style>
@endpush
