{{-- resources/views/admin/users/assign-package.blade.php --}}

@extends('layouts.app') {{-- VEYA SİZİN ANA ŞABLONUNUZUN ADI, ÖRN: layouts.admin --}}

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Kullanıcıya Paket Ata: <strong>{{ $user->name }}</strong>
                    </div>

                    <div class="card-body">
                        {{-- Form, POST metodu ile atama işlemini yapacak olan rotaya yönlendiriliyor --}}
                        <form action="{{ route('admin.users.assign-package', $user) }}" method="POST">
                            @csrf {{-- CSRF Güvenlik Koruması --}}

                            <div class="mb-3">
                                <label for="package_id" class="form-label">Kullanım Paketi Seçin</label>
                                <select class="form-select @error('package_id') is-invalid @enderror" id="package_id" name="package_id" required>
                                    <option value="">Lütfen bir paket seçin...</option>

                                    {{-- Controller'dan gelen tüm aktif paketleri listeliyoruz --}}
                                    @foreach($packages as $package)
                                        <option value="{{ $package->id }}" {{-- Eğer kullanıcının mevcut paketi bu ise, seçili gelsin --}}
                                            {{ $user->package_id == $package->id ? 'selected' : '' }}>
                                            {{ $package->name }} ({{ $package->price }} TL)
                                        </option>
                                    @endforeach
                                </select>

                                {{-- Form doğrulama hatası varsa gösterilir --}}
                                @error('package_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">İptal</a>
                                <button type="submit" class="btn btn-primary">Paketi Ata</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
