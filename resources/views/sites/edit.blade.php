@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Site Düzenle: {{ $site->name }}</div>
            <div class="card-body">
                <form action="{{ route('sites.update', $site->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Site Adı --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Site Adı</label>
                        {{-- value özelliği, eski değeri veya mevcut site adını gösterir --}}
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $site->name) }}" required>
                    </div>

                    {{-- Adres Bilgileri --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Ülke</label>
                            <input type="text" name="country" id="country" class="form-control" value="{{ old('country', $site->country) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">İl</label>
                            <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $site->city) }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="district" class="form-label">İlçe</label>
                            <input type="text" name="district" id="district" class="form-control" value="{{ old('district', $site->district) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Posta Kodu (İsteğe Bağlı)</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control" value="{{ old('postal_code', $site->postal_code) }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Adres Detayı</label>
                        {{-- textarea içinde değeri bu şekilde gösteririz --}}
                        <textarea name="address_line" id="address_line" class="form-control" rows="3" required>{{ old('address_line', $site->address_line) }}</textarea>
                    </div>

                    <hr>

                    {{-- Yönetici Atama Alanı --}}
                    <div class="mb-3">
                        <label for="manager_ids" class="form-label">Site Yöneticileri</label>
                        <select name="manager_ids[]" id="manager_ids" multiple class="form-select" style="height: 150px;">
                            @foreach($potential_managers as $manager)
                                {{-- Mevcut yöneticileri 'selected' olarak işaretliyoruz --}}
                                <option value="{{ $manager->id }}" {{ in_array($manager->id, $siteManagers) ? 'selected' : '' }}>
                                    {{ $manager->name }} ({{ $manager->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Güncelle</button>
                        <a href="{{ route('sites.index') }}" class="btn btn-secondary">İptal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
