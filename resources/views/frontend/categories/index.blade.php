{{-- resources/views/frontend/categories/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kategoriyalar')

@section('content')

    {{-- Breadcrumb --}}
    <div class="container mt-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Bosh sahifa</a></li>
                <li class="breadcrumb-item active">Kategoriyalar</li>
            </ol>
        </nav>
    </div>

    {{-- Page Header --}}
    <div class="container my-5">
        <h1 class="display-4 fw-bold mb-4">Barcha kategoriyalar</h1>
        <p class="lead text-muted">50,000+ mahsulot, 100+ kategoriya</p>
    </div>

    {{-- Categories Grid --}}
    <div class="container mb-5">
        @if($categories->count() > 0)
            <div class="row g-4">
                @foreach($categories as $category)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm hover-card">
                            <div class="card-body">
                                {{-- Category Header --}}
                                <div class="d-flex align-items-center mb-3">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}"
                                             alt="{{ $category->name }}"
                                             class="rounded"
                                             style="width: 64px; height: 64px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                             style="width: 64px; height: 64px;">
                                            <i class="fas fa-tag text-primary fs-2"></i>
                                        </div>
                                    @endif

                                    <div class="ms-3 flex-grow-1">
                                        <a href="{{ route('categories.show', $category->slug) }}"
                                           class="text-decoration-none">
                                            <h5 class="card-title mb-1 text-dark">{{ $category->name }}</h5>
                                        </a>
                                        <small class="text-muted">
                                            <i class="fas fa-box me-1"></i>
                                            {{ $category->products_count }} mahsulot
                                        </small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                @if($category->description)
                                    <p class="text-muted small mb-3">{{ Str::limit($category->description, 100) }}</p>
                                @endif

                                {{-- Subcategories --}}
                                @if($category->children && $category->children->count() > 0)
                                    <div class="border-top pt-3">
                                        <h6 class="text-muted small fw-semibold mb-2">Subkategoriyalar:</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($category->children->take(5) as $child)
                                                <a href="{{ route('categories.show', $child->slug) }}"
                                                   class="badge bg-light text-dark text-decoration-none border">
                                                    {{ $child->name }}
                                                </a>
                                            @endforeach

                                            @if($category->children->count() > 5)
                                                <span class="badge bg-light text-muted border">
                                                +{{ $category->children->count() - 5 }} yana
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- View Button --}}
                                <a href="{{ route('categories.show', $category->slug) }}"
                                   class="btn btn-outline-primary w-100 mt-3">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    Mahsulotlarni ko'rish
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-inbox text-muted" style="font-size: 5rem;"></i>
                <h3 class="mt-3">Kategoriyalar topilmadi</h3>
                <p class="text-muted">Hozircha kategoriyalar mavjud emas</p>
            </div>
        @endif
    </div>

@endsection

@push('styles')
    <style>
        .hover-card {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
            border-color: #0d6efd;
        }
    </style>
@endpush
