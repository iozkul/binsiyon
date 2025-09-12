@extends('layouts.app') {{-- Kendi ana layout'unuzu kullanın --}}

@section('content')
    <div class="container">
        <h3>Hoş Geldiniz, {{ $user->name }}</h3>
        <hr>
        <div class="row">
            {{-- Sol Sütun: Konum ve Mülk Bilgileri --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">Konum Bilgilerim</div>
                    <div class="card-body">
                        @if($user->unit)
                            <p><strong>Site:</strong> {{ $user->unit->block->site->name }}</p>
                            <p><strong>Blok:</strong> {{ $user->unit->block->name }}</p>
                            <p><strong>Daire No:</strong> {{ $user->unit->name_or_number }}</p>
                            <p><strong>Adres:</strong> {{ $user->unit->block->site->address_line }}, {{ $user->unit->block->site->district }} / {{ $user->unit->block->site->city }}</p>
                        @else
                            <p>Henüz bir daireye atanmamışsınız.</p>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Kullanım Haklarım</div>
                    <div class="card-body">
                        <h6>Otopark</h6>
                        @forelse($user->unit->parkingSpaces ?? [] as $space)
                            <p>Yer No: {{ $space->space_number }} ({{ $space->location }})</p>
                        @empty
                            <p>Tanımlı otopark hakkınız bulunmuyor.</p>
                        @endforelse
                        <hr>
                        <h6>Sosyal Alanlar</h6>
                        <p>Havuz Kullanımı: <span class="badge bg-success">Aktif</span></p>
                        {{-- Bu bilgiler unit->properties JSON alanından çekilebilir --}}
                    </div>
                </div>
            </div>

            {{-- Sağ Sütun: Finans ve Bildirimler --}}
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header text-white bg-danger">
                        Ödenmemiş Borçlarım ({{ $unpaidFees->count() }} adet)
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($unpaidFees as $fee)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $fee->description }} (Son Ödeme: {{ \Carbon\Carbon::parse($fee->due_date)->format('d.m.Y') }})</span>
                                <strong>{{ number_format($fee->amount, 2) }} TL</strong>
                            </li>
                        @empty
                            <li class="list-group-item">Ödenmemiş borcunuz bulunmamaktadır.</li>
                        @endforelse
                    </ul>
                    <div class="card-footer text-end">
                        <a href="{{ route('finance.index') }}" class="btn btn-primary">Tüm Finansal Durumum & Ödeme Yap</a>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Duyurular ve Mesajlar</div>
                    <div class="card-body">
                        {{-- Buraya duyuru ve mesaj listesi gelecek --}}
                        <p>Hoş geldiniz duyurusu.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
