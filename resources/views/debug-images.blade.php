{{-- resources/views/debug-images.blade.php --}}
    <!DOCTYPE html>
<html>
<head>
    <title>Debug Images</title>
</head>
<body>
<h1>Image Debug</h1>

@php
    $product = \App\Models\Product::first();
@endphp

@if($product)
    <h2>Product: {{ $product->name }}</h2>

    <h3>Image Information:</h3>
    <ul>
        <li><strong>primary_image:</strong> {{ $product->primary_image }}</li>
        <li><strong>asset() result:</strong> {{ asset($product->primary_image) }}</li>
        <li><strong>Full URL:</strong> {{ url($product->primary_image) }}</li>
    </ul>

    <h3>Product Images from DB:</h3>
    @foreach($product->images as $img)
        <div style="border: 1px solid #ccc; padding: 10px; margin: 10px 0;">
            <p><strong>Image path:</strong> {{ $img->image }}</p>
            <p><strong>Is primary:</strong> {{ $img->is_primary ? 'Yes' : 'No' }}</p>
            <p><strong>Full path:</strong> {{ asset($img->image) }}</p>

            <h4>Test 1: Direct path</h4>
            <img src="{{ $img->image }}" alt="Test 1" style="max-width: 200px; border: 2px solid red;">

            <h4>Test 2: With asset()</h4>
            <img src="{{ asset($img->image) }}" alt="Test 2" style="max-width: 200px; border: 2px solid blue;">

            <h4>Test 3: With storage/</h4>
            <img src="{{ asset('storage/' . str_replace('images/', '', $img->image)) }}" alt="Test 3" style="max-width: 200px; border: 2px solid green;">

            <h4>Test 4: Direct storage path</h4>
            <img src="/storage/{{ str_replace('images/', '', $img->image) }}" alt="Test 4" style="max-width: 200px; border: 2px solid orange;">
        </div>
    @endforeach

    <h3>File System Check:</h3>
    <ul>
        <li><strong>public_path():</strong> {{ public_path() }}</li>
        <li><strong>storage_path():</strong> {{ storage_path() }}</li>
        @php
            $img = $product->images->first();
            if ($img) {
                $paths = [
                    'public/' . $img->image => public_path($img->image),
                    'storage/app/public/' . str_replace('images/', '', $img->image) => storage_path('app/public/' . str_replace('images/', '', $img->image)),
                    'public/storage/' . str_replace('images/', '', $img->image) => public_path('storage/' . str_replace('images/', '', $img->image)),
                ];

                foreach ($paths as $label => $path) {
                    echo "<li><strong>$label:</strong> " . (file_exists($path) ? '✅ EXISTS' : '❌ NOT FOUND') . " - $path</li>";
                }
            }
        @endphp
    </ul>
@else
    <p>No products found!</p>
@endif
</body>
</html>
