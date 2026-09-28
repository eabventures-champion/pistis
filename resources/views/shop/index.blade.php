@extends('layouts.app')

@section('title', 'Shop — Pistis')

@section('content')
{{-- Shop Banner --}}
<div style="background:#000000;color:#ffffff;text-align:center;padding:56px 24px 48px;">
    <span style="font-family:'Inter',sans-serif;font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:#737373;display:block;margin-bottom:12px;">THE ARCHIVE</span>
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:300;margin:0;letter-spacing:0.02em;">
        @if(request('category') && $categories->firstWhere('id', request('category')))
            {{ $categories->firstWhere('id', request('category'))->name }}
        @else
            All Pieces
        @endif
    </h1>
    <div style="width:40px;height:1px;background:#525252;margin:20px auto 0;"></div>
</div>

<div class="container" style="padding-top:40px;padding-bottom:60px;">
    <div class="shop-layout">
        {{-- Filter Sidebar --}}
        <button type="button" class="filter-toggle-btn mobile-only" onclick="document.querySelector('.filter-sidebar').classList.toggle('open')">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/></svg>
            <span>FILTER & SORT</span>
        </button>
        <aside class="filter-sidebar">
            <div class="filter-group">
                <div class="filter-title">Categories</div>
                <ul class="filter-list">
                    <li>
                        <a href="{{ route('shop.index', request()->except('category')) }}"
                           class="{{ !request('category') ? 'active' : '' }}">
                            All Pieces
                        </a>
                    </li>
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $category->id])) }}"
                               class="{{ request('category') == $category->id ? 'active' : '' }}">
                                {{ $category->name }}
                                <span style="font-size:0.7rem;color:#a3a3a3;">{{ $category->total_products_count }}</span>
                            </a>
                            @if($category->children->count() > 0)
                                <ul class="filter-list" style="padding-left:16px;margin-top:4px;">
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('shop.index', array_merge(request()->query(), ['category' => $child->id])) }}"
                                               class="{{ request('category') == $child->id ? 'active' : '' }}"
                                               style="font-size:0.8rem;padding:4px 12px;">
                                                {{ $child->name }}
                                                <span style="font-size:0.7rem;color:#a3a3a3;">{{ $child->total_products_count }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-group">
                <div class="filter-title">Sort By</div>
                <ul class="filter-list">
                    @foreach(['newest' => 'Latest', 'price_low' => 'Price: Low → High', 'price_high' => 'Price: High → Low', 'name' => 'Alphabetical'] as $key => $label)
                        <li>
                            <a href="{{ route('shop.index', array_merge(request()->query(), ['sort' => $key])) }}"
                               class="{{ request('sort') == $key ? 'active' : '' }}">
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="filter-group">
                <div class="filter-title">Price Range</div>
                <form action="{{ route('shop.index') }}" method="GET">
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    <div class="form-row" style="margin-bottom:8px;">
                        <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                        <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">APPLY</button>
                </form>
            </div>
        </aside>

        {{-- Product Grid --}}
        <div>
            {{-- Search Bar --}}
            <form action="{{ route('shop.index') }}" method="GET" style="margin-bottom:32px;">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('sort')) <input type="hidden" name="sort" value="{{ request('sort') }}"> @endif
                <div class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Search the archive..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">SEARCH</button>
                </div>
            </form>

            @if(request('search'))
                <p style="font-size:0.8rem;color:#737373;margin-bottom:24px;letter-spacing:0.05em;text-transform:uppercase;">
                    Results for "<strong style="color:#000000;">{{ request('search') }}</strong>"
                    <a href="{{ route('shop.index') }}" style="margin-left:8px;color:#000000;">Clear ✕</a>
                </p>
            @endif

            @if($products->count() > 0)
                <div class="product-grid">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                <div class="pagination" style="margin-top:48px;">
                    {{ $products->links() }}
                </div>
            @else
                <div style="text-align:center;padding:80px 24px;">
                    <div style="font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:#a3a3a3;margin-bottom:16px;">NO RESULTS</div>
                    <h3 style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;margin:0 0 12px;">Nothing Found</h3>
                    <p style="color:#737373;font-size:0.85rem;margin-bottom:24px;">Try adjusting your filters or search terms</p>
                    <a href="{{ route('shop.index') }}" class="btn btn-primary">CLEAR FILTERS</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
