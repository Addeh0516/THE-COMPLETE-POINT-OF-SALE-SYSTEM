@extends('layouts.app', ['title' => 'Products', 'section' => 'PRODUCTS'])

@section('content')
<div class="page-intro"><div><span class="section-tag">CATALOG / INVENTORY</span><h1>Products on the floor.</h1><p>Manage the things players can take home, one clean record at a time.</p></div><a class="button button-primary" href="{{ route('products.create') }}">@include('components.icon', ['name' => 'plus']) Add product</a></div>
<div class="toolbar"><div class="result-count"><strong>{{ $products->total() }}</strong> products in catalog</div><span class="permit-label"><span class="status-dot"></span> DISPLAY-READY IMAGES</span></div>
<section class="product-grid">
    @forelse($products as $product)
        <article class="product-card"><div class="product-card-image">@if($product->image)<img src="{{ str_starts_with($product->image, 'uploads/') ? asset($product->image) : (str_contains($product->image, '/') ? Storage::url($product->image) : asset('media/'.$product->image)) }}" alt="{{ $product->name }}">@else<div class="image-placeholder">@include('components.icon', ['name' => 'box'])<span>NO IMAGE</span></div>@endif<span class="stock-badge {{ $product->stock_quantity === 0 ? 'empty' : ($product->stock_quantity <= 5 ? 'low' : '') }}">{{ $product->stock_quantity === 0 ? 'OUT' : $product->stock_quantity.' IN STOCK' }}</span></div><div class="product-card-body"><div><span class="mono product-code">SKU / {{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span><h2>{{ $product->name }}</h2></div><strong class="product-price">₱{{ number_format($product->price, 2) }}</strong></div><div class="product-card-foot"><span>Added {{ $product->created_at->format('M d, Y') }}</span><div class="row-actions"><a class="icon-button" href="{{ route('products.edit', $product) }}" aria-label="Edit {{ $product->name }}">@include('components.icon', ['name' => 'edit'])</a><form method="POST" action="{{ route('products.destroy', $product) }}">@csrf @method('DELETE')<button class="icon-button danger" type="submit" title="Remove {{ $product->name }}" aria-label="Remove {{ $product->name }}">@include('components.icon', ['name' => 'trash'])</button></form></div></div></article>
    @empty
        <div class="empty-state wide"><span class="empty-mark">∅</span><strong>Catalog is empty.</strong><span>Add the first product to give the counter something to sell.</span><a class="button button-primary" href="{{ route('products.create') }}">Add product</a></div>
    @endforelse
</section>
<div class="pagination-wrap">{{ $products->links() }}</div>
@endsection
