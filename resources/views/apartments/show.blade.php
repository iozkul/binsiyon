@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daire No: {{ $apartment->door_number }} - Detaylar</h1>
    <p class="lead">
        Konum: <a href="{{ route('sites.show', $apartment->block->site) }}">{{ $apartment->block->site->name }}</a> / 
        <a href="{{ route('blocks.show', $apartment->block) }}">{{ $apartment->block->name }}</a>
    </p>

    <div class="row">
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-header">Daire Kimliği</div>
                <div class="card-body">
                    <p><strong>Kat:</strong> {{ $apartment->floor }}</p>
                    <p><strong>Durum:</strong> {{ ucfirst($apartment->residents->first()?->status ?? 'Boş') }}</p> </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Yönetici Bilgileri</div>
                <div class="card-body">
                    <p><strong>Site Yöneticisi:</strong> {{ $apartment->block->site->manager?->name ?? 'N/A' }}</p>
                    <p><strong>Blok Yöneticisi:</strong> {{ $apartment->block->manager?->name ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Demografik Bilgiler</div>
                <div class="card-body">
                    <p>Toplam Sakin: {{ $demographics['total'] }} ({{ $demographics['male'] }} Erkek, {{ $demographics['female'] }} Kadın)</p>
                    <p>Çocuk Sayısı: {{ $demographics['children'] }}</p>
                    <p>Yaşlı / Engelli Birey: {{ $demographics['elderly'] + $demographics['disabled'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header">Sakin Listesi</div>
                <div class="card-body p-0">
                    <table class="table mb-0">
                        @forelse($apartment->residents as $resident)
                        <tr>
                            <td>{{ $resident->name }} ({{ ucfirst($resident->status) }})</td>
                            <td>TC: {{ $resident->tc_kimlik ?? 'N/A' }}</td>
                            <td>Tel: {{ $resident->phone ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">Bu dairede kayıtlı sakin bulunmuyor.</td></tr>
                        @endforelse
                    </table>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">Aidat ve Borç Durumu</div>
                <div class="card-body p-0">
                     <table class="table mb-0">
                        <thead><tr><th>Tarih</th><th>Açıklama</th><th>Tutar</th><th>Durum</th></tr></thead>
                        <tbody>
                            @foreach($dues as $due)
                            <tr>
                                <td>{{ $due['date'] }}</td>
                                <td>{{ $due['description'] }}</td>
                                <td>{{ $due['amount'] }} TL</td>
                                <td><span class="badge bg-{{ $due['status'] == 'Ödendi' ? 'success' : 'danger' }}">{{ $due['status'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection