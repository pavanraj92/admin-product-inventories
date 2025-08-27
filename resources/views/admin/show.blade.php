<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold">Stock Quantity:</label>
            <p>{{ $product?->inventory?->stock_quantity ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold">Low Stock Threshold:</label>
            <p>{{ $product?->inventory?->low_stock_threshold ?? 'N/A' }}</p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="font-weight-bold">Stock Status:</label>
            <p>{!! config('inventory.constants.productStockStatus.' . ($product?->inventory?->stock_status ?? ''), 'N/A') !!}</p>
        </div>
    </div>
</div>