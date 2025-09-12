@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $block->name }} - Blok Detayları</h1>
    <p class="lead">
        Ait Olduğu Site: <a href="{{ route('sites.show', $block->site) }}">{{ $block->site->name }}</a>
    </p>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Yönetici Bilgileri</div>
                <div class="card-body">
                    <p><strong>Site Yöneticisi:</strong> {{ $block->site->manager?->name ?? 'Atanmamış' }}</p>
                    <p><strong>Blok Yöneticisi:</strong> {{ $block->manager?->name ?? 'Atanmamış' }}</p>
                    <p><strong>İletişim:</strong> {{ $block->manager?->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">İstatistikler</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Toplam Daire Sayısı
                        <span class="badge bg-primary rounded-pill">{{ $unitCount }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Toplam Sakin Sayısı
                        <span class="badge bg-primary rounded-pill">{{ $residentCount }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Mülk Sahibi Sayısı
                        <span class="badge bg-success rounded-pill">{{ $statusStats['owner'] ?? 0 }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Kiracı Sayısı
                        <span class="badge bg-warning rounded-pill">{{ $statusStats['tenant'] ?? 0 }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Gelir - Gider Özeti</div>
                <div class="card-body">
                    <p><strong>Toplam Gelir:</strong> {{ number_format($financialSummary['income'], 2) }} TL</p>
                    <p><strong>Toplam Gider:</strong> {{ number_format($financialSummary['expense'], 2) }} TL</p>
                    <hr>
                    <p><strong>Kasa Durumu:</strong> {{ number_format($financialSummary['income'] - $financialSummary['expense'], 2) }} TL</p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Görevli Personel</div>
                <div class="card-body">
                    <p><strong>Blok Görevlisi:</strong> Mehmet Öztürk</p>
                    </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between">
                <span>Bu Bloğa Ait Birimler</span>
                <a href="{{ route('units.create', ['block_id' => $block->id]) }}" class="btn btn-success btn-sm">
                    Bu Bloğa Toplu Birim Ekle
                </a>
            </div>
            <div class="card-body">
                {{-- Bloğun mevcut birimlerini listeleyebilirsiniz --}}
            </div>
        </div>
    </div>
</div>
@endsection
