@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Mülklerim Paneli</h1>
        <p>Bu ekranda sahibi olduğunuz mülkleri ve o mülklerdeki kiracıların durumunu görebilirsiniz.</p>
        <hr>
        @foreach($ownedUnits as $unit)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>{{ $unit->block->site->name }} - {{ $unit->block->name }} - {{ $unit->name_or_number }}</h5>
                </div>
                <div class="card-body">
                    <h6>Bu Birimdeki Kiracı(lar)</h6>
                    @if($unit->residents->isNotEmpty())
                        <ul class="list-group">
                            @foreach($unit->residents as $resident)
                                <li class="list-group-item">
                                    {{ $resident->name }} - İletişim: {{ $resident->phone ?? 'Belirtilmemiş' }}
                                    ({{ $resident->pivot->relation ?? 'Kiracı' }}) {{-- Bu daha ileri bir konsept --}}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Bu birimde şu anda kiracı bulunmuyor.</p>
                    @endif

                    <h6 class="mt-3">Birimle İlgili Finansal Durum</h6>
                    {{-- Buraya o birimin aidat durumu ve ödemeler listelenecek --}}
                    <a href="#" class="btn btn-sm btn-outline-primary mt-2">Finansal Detayları Gör</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
