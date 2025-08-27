<div class="row mb-3">
    <div class="col-md-4">
        <label for="stock_quantity" class="form-label">Stock Quantity<span class="text-danger">*</span></label>
        <input type="text" name="stock_quantity" id="stock_quantity" class="form-control numbers-only"
            value="{{ old('stock_quantity', $product?->inventory?->stock_quantity ?? '') }}" placeholder="0">
        @error('stock_quantity')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="low_stock_threshold" class="form-label">Low Stock
            Threshold</label>
        <input type="text" name="low_stock_threshold" id="low_stock_threshold" class="form-control numbers-only"
            value="{{ old('low_stock_threshold', $product?->inventory->low_stock_threshold ?? '') }}" placeholder="0">
        @error('low_stock_threshold')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="stock_status" class="form-label">Stock Status</label>
        <select name="stock_status" id="stock_status" class="form-class select2">
            @php $stockStatuses = config('inventory.constants.productStockStatus', []); @endphp
            @foreach ($stockStatuses as $value => $label)
                <option value="{{ $value }}"
                    {{ old('stock_status', $product?->inventory->stock_status ?? 'in_stock') == $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('stock_status')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
</div>
