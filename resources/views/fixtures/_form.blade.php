@csrf
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="name" class="form-label">Demirbaş Adı <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $fixture->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="site_id" class="form-label">Ait Olduğu Site <span class="text-danger">*</span></label>
        <select class="form-select" id="site_id" name="site_id" required>
            <option value="">Lütfen seçiniz...</option>
            @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ (old('site_id', $fixture->site_id ?? '') == $site->id) ? 'selected' : '' }}>
                    {{ $site->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="brand" class="form-label">Marka</label>
        <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $fixture->brand ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label for="model" class="form-label">Model</label>
        <input type="text" class="form-control" id="model" name="model" value="{{ old('model', $fixture->model ?? '') }}">
    </div>
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="purchase_date" class="form-label">Satın Alma Tarihi</label>
        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', isset($fixture->purchase_date) ? $fixture->purchase_date->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label for="warranty_end_date" class="form-label">Garanti Bitiş Tarihi</label>
        <input type="date" class="form-control" id="warranty_end_date" name="warranty_end_date" value="{{ old('warranty_end_date', isset($fixture->warranty_end_date) ? $fixture->warranty_end_date->format('Y-m-d') : '') }}">
    </div>
</div>
<hr>
<h5 class="mt-4">Bakım Bilgileri</h5>
<div class="row">
    <div class="col-md-4 mb-3">
        <label for="last_maintenance_date" class="form-label">Son Bakım Tarihi</label>
        <input type="date" class="form-control" id="last_maintenance_date" name="last_maintenance_date" value="{{ old('last_maintenance_date', isset($fixture->last_maintenance_date) ? $fixture->last_maintenance_date->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label for="maintenance_interval_days" class="form-label">Bakım Periyodu (Gün)</label>
        <input type="number" class="form-control" id="maintenance_interval_days" name="maintenance_interval_days" value="{{ old('maintenance_interval_days', $fixture->maintenance_interval_days ?? '') }}" placeholder="Örn: 90">
    </div>
    <div class="col-md-4 mb-3">
        <label for="status" class="form-label">Durum <span class="text-danger">*</span></label>
        <select class="form-select" id="status" name="status" required>
            <option value="aktif" {{ (old('status', $fixture->status ?? 'aktif') == 'aktif') ? 'selected' : '' }}>Aktif</option>
            <option value="bakımda" {{ (old('status', $fixture->status ?? '') == 'bakımda') ? 'selected' : '' }}>Bakımda</option>
            <option value="arızalı" {{ (old('status', $fixture->status ?? '') == 'arızalı') ? 'selected' : '' }}>Arızalı</option>
            <option value="pasif" {{ (old('status', $fixture->status ?? '') == 'pasif') ? 'selected' : '' }}>Pasif</option>
        </select>
    </div>
</div>
<div class="mb-3">
    <label for="notes" class="form-label">Notlar</label>
    <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $fixture->notes ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-primary">Kaydet</button>
<a href="{{ route('fixtures.index') }}" class="btn btn-secondary">İptal</a>
