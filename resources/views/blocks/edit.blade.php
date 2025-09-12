@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Blok Düzenle: {{ $block->name }}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('blocks.update', $block->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="alert alert-info">
                        <h5 class="alert-heading">Birim Özeti</h5>
                        <p>
                            Bu blokta mevcut durumda
                            <strong>{{ $block->apartment_count }}</strong> adet daire,
                            <strong>{{ $block->commercial_count }}</strong> adet ticari alan ve
                            <strong>{{ $block->social_count }}</strong> adet sosyal alan bulunmaktadır.
                        </p>
                        <hr>
                        <p class="mb-0">
                            Birimlerin özelliklerini (m², oda sayısı vb.) düzenlemek veya yeni birim eklemek için
                            <a href="{{ route('blocks.show', $block) }}" class="alert-link">Blok Detay Sayfasına</a> gidin.
                        </p>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Blok Adı</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $block->name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="site_id" class="form-label">Ait Olduğu Site</label>
                        <select name="site_id" id="site_id" class="form-select" required>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" {{ $block->site_id == $site->id ? 'selected' : '' }}>
                                    {{ $site->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <a href="{{ route('blocks.index') }}" class="btn btn-secondary">İptal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
