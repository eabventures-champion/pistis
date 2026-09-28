<div class="product-card">
    <a href="{{ route('shop.show', $product->slug) }}" style="display:block;text-decoration:none;">
        <div class="product-image">
            @if($product->primary_image_url)
                <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" loading="lazy">
            @else
                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f5f5f5;color:#a3a3a3;font-size:0.75rem;letter-spacing:0.1em;">NO IMAGE</div>
            @endif
            
            <div class="badge-overlay">
                @if($product->featured)
                    <span class="badge badge-primary">EDITORIAL</span>
                @endif
                @if($product->compare_price && $product->compare_price > $product->price)
                    @php $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100); @endphp
                    <span class="badge badge-secondary">-{{ $discount }}%</span>
                @endif
                @if(!$product->is_in_stock)
                    <span class="badge badge-warning">SOLD OUT</span>
                @endif
            </div>

            <div class="product-card-quick-btn">
                <span>View Piece →</span>
            </div>
        </div>
    </a>
    
    <div class="product-info">
        @if($product->category)
            <div class="product-category">{{ $product->category->name }}</div>
        @endif
        <a href="{{ route('shop.show', $product->slug) }}">
            <h3 class="product-name">{{ $product->name }}</h3>
        </a>
        <div class="product-price">
            <span class="price-current">{{ $product->formatted_price }}</span>
            @if($product->compare_price && $product->compare_price > $product->price)
                <span class="price-compare">{{ $currency_symbol }}{{ number_format($product->compare_price, 2) }}</span>
            @endif
        </div>
    </div>
</div>



