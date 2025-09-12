@csrf
<div class="mb-3">
    <label for="name" class="form-label">Paket Adı</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $package->name ?? '') }}" required>
</div>
<div class="mb-3">
    <label for="price" class="form-label">Fiyat (TL)</label>
    <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $package->price ?? '0') }}" required>
</div>
<div class="mb-3">
    <label for="description" class="form-label">Açıklama</label>
    <textarea class="form-control" id="description" name="description">{{ old('description', $package->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <h5>Özellikler (Modüller)</h5>
    @foreach($features as $feature)
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="{{ $feature->id }}" id="feature{{ $feature->id }}" name="features[]"
                   @if(isset($packageFeatures) && in_array($feature->id, $packageFeatures)) checked @endif>
            <label class="form-check-label" for="feature{{ $feature->id }}">
                {{ $feature->name }} <small class="text-muted">({{ $feature->description }})</small>
            </label>
        </div>
    @endforeach
</div>
<button type="submit" class="btn btn-success">{{ isset($package) ? 'Güncelle' : 'Oluştur' }}</button>
