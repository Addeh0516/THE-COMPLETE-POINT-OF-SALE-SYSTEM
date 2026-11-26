@extends('layouts.app', ['title' => 'Floor view', 'section' => 'FLOOR VIEW'])

@section('content')
<div class="page-intro page-intro-dashboard">
    <div><span class="section-tag">WEDNESDAY / LATE SHIFT</span><h1>The floor is <em>still glowing.</em></h1><p>Everything you need for the next round is in reach. Make the sale, keep the count honest.</p></div>
    <a class="button button-primary" href="{{ route('sales.create') }}">Record a sale @include('components.icon', ['name' => 'arrow'])</a>
</div>

<div class="dashboard-grid">
    <section class="hero-panel">
        <div class="hero-panel-top"><span class="permit-label"><span class="status-dot"></span> FEU PILOT</span><span class="mono">LIVE / 01</span></div>
        <div class="hero-panel-main"><div><span class="hero-label">TONIGHT'S REVENUE</span><strong class="hero-total">₱{{ number_format($todayRevenue, 2) }}</strong><span class="hero-sub">{{ $todaySales }} {{ Str::plural('sale', $todaySales) }} closed since 00:00</span></div><div class="hero-signal"><span></span><span></span><span></span><span></span><span></span></div></div>
        <div class="scanline"></div>
        <div class="hero-foot"><span>@include('components.icon', ['name' => 'clock']) Shift opened {{ now()->format('H:i') }}</span><span class="mono">NO PAYMENT GATEWAY / LAB MODE</span></div>
    </section>
    <aside class="editorial-panel"><div class="editorial-photo" style="background-image: linear-gradient(180deg, transparent 30%, rgba(11,11,18,.88)), url('{{ asset('media/arcade-floor.jpg') }}');"></div><div class="editorial-copy"><span class="section-tag">NIGHT NOTE / 01</span><p>Find the calm inside the noise. The cleanest sale is the one nobody has to ask twice.</p><span class="mono">AFTERHOURS / FLOOR LOG</span></div></aside>
</div>

<div class="status-row">
    <div class="status-card"><span class="status-card-icon mint">@include('components.icon', ['name' => 'box'])</span><div><span class="status-title">Available</span><strong>{{ number_format($availableStock) }}</strong><small>units on the floor</small></div><span class="status-mark">A</span></div>
    <div class="status-card"><span class="status-card-icon lavender">@include('components.icon', ['name' => 'receipt'])</span><div><span class="status-title">Approved</span><strong>{{ $todaySales }}</strong><small>sales closed today</small></div><span class="status-mark">OK</span></div>
    <div class="status-card"><span class="status-card-icon signal">@include('components.icon', ['name' => 'spark'])</span><div><span class="status-title">Pilot area</span><strong>{{ $activeStaff }}</strong><small>staff accounts live</small></div><span class="status-mark">01</span></div>
</div>

<div class="section-heading"><div><span class="section-tag">RECENT ACTIVITY</span><h2>What just happened.</h2></div><a class="text-link" href="{{ route('sales.index') }}">Open full ledger @include('components.icon', ['name' => 'arrow'])</a></div>
<div class="activity-layout">
    <section class="panel activity-panel">
        @forelse($recentSales as $sale)
            <div class="activity-row"><div class="activity-icon">@include('components.icon', ['name' => 'receipt'])</div><div class="activity-main"><strong>{{ $sale->product->name }}</strong><span>{{ $sale->customer?->full_name ?? 'Walk-in player' }} · by {{ $sale->staff->full_name }}</span></div><div class="activity-qty mono">×{{ $sale->quantity }}</div><div class="activity-total">₱{{ number_format($sale->total_price, 2) }}</div><div class="activity-time mono">{{ $sale->created_at->format('H:i') }}</div></div>
        @empty
            <div class="empty-state"><span class="empty-mark">∅</span><strong>No sales yet.</strong><span>The first round is waiting at the counter.</span></div>
        @endforelse
    </section>
    <section class="panel timeline-panel"><div class="panel-heading"><div><span class="section-tag">SHIFT TRACE</span><h3>One night, in markers.</h3></div><span class="permit-label">LIMITED SPACES</span></div><div class="timeline"><div class="timeline-item"><span class="timeline-time">19:00</span><span class="timeline-dot active"></span><div><strong>Doors open</strong><p>Terminals checked. The first lights come on.</p></div></div><div class="timeline-item"><span class="timeline-time">21:14</span><span class="timeline-dot"></span><div><strong>Floor fills</strong><p>Stock is ready for the rush.</p></div></div><div class="timeline-item"><span class="timeline-time">00:42</span><span class="timeline-dot"></span><div><strong>Last token</strong><p>The ledger closes only when the floor does.</p></div></div></div></section>
</div>

<div class="section-heading compact"><div><span class="section-tag">INVENTORY WATCH</span><h2>Keep an eye on these.</h2></div><a class="text-link" href="{{ route('products.index') }}">Manage products @include('components.icon', ['name' => 'arrow'])</a></div>
<section class="panel stock-panel"><div class="stock-head"><span>PRODUCT</span><span>STOCK</span><span>READ</span></div>@forelse($lowStockProducts as $product)<div class="stock-row"><div class="product-mini"><div class="product-thumb small">@if($product->image)<img src="{{ str_starts_with($product->image, 'uploads/') ? asset($product->image) : (str_contains($product->image, '/') ? Storage::url($product->image) : asset('media/'.$product->image)) }}" alt="">@else<span>@include('components.icon', ['name' => 'box'])</span>@endif</div><div><strong>{{ $product->name }}</strong><span>₱{{ number_format($product->price, 2) }}</span></div></div><span class="mono stock-number {{ $product->stock_quantity === 0 ? 'stock-zero' : 'stock-low' }}">{{ $product->stock_quantity }}</span><span class="stock-read">{{ $product->stock_quantity === 0 ? 'OUT OF STOCK' : 'LOW STOCK' }}</span></div>@empty<div class="empty-inline">All clear — no low-stock products right now.</div>@endforelse</section>
@endsection
