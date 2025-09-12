@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>{{ $site->name }} - Site Detayları</h3>
            <a href="{{ route('sites.edit', $site) }}" class="btn btn-warning">Bu Siteyi Düzenle</a>
        </div>

        <div class="row">
            <div class="col-md-5">
                {{-- Adres ve Yönetici Bilgileri --}}
                <div class="card mb-4">
                    <div class="card-header">Temel Bilgiler</div>
                    <div class="card-body">
                        <strong>Adres:</strong>
                        {{-- Site modelinden gelen verileri gösteriyoruz --}}
                        <p class="ms-2">
                            {{ $site->address_line }}<br>
                            {{ $site->district }} / {{ $site->city }}<br>
                            {{ $site->postal_code }} {{ $site->country }}
                        </p>
                        <hr>
                        <strong>Yöneticiler:</strong>
                        <ul class="list-unstyled ms-2">
                            @forelse($site->managers as $manager)
                                <li>{{ $manager->name }}</li>
                            @empty
                                <li>Atanmış yönetici yok.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-7">
                {{-- İstatistikler ve Blok Listesi --}}
                <div class="card">
                    <div class="card-header">Bu Siteye Bağlı Bloklar</div>
                    <ul class="list-group list-group-flush">
                        @forelse($site->blocks as $block)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $block->name }}
                                <span>{{ $block->units->count() }} Birim</span>
                                <a href="{{ route('blocks.show', $block) }}" class="btn btn-sm btn-outline-secondary">Detayları Gör</a>
                            </li>
                        @empty
                            <li class="list-group-item">Bu siteye henüz blok eklenmemiş.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
