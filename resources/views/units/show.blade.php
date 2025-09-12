@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Birim Detayları: {{ $unit->name_or_number }}</h3>
                <p class="text-muted">
                    Konum: {{ $unit->block->site->name ?? '' }} / {{ $unit->block->name ?? '' }}
                </p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h4>Birim Bilgileri</h4>
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Türü:</strong> {{ ucfirst($unit->type) }}</li>
                            @if($unit->properties)
                                @foreach($unit->properties as $key => $value)
                                    <li class="list-group-item">
                                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>Bu Birimde Yaşayan Sakinler</h4>
                        @if($unit->residents->isNotEmpty())
                            <ul class="list-group">
                                @foreach($unit->residents as $resident)
                                    <li class="list-group-item">{{ $resident->name }}</li>
                                @endforeach
                            </ul>
                        @else
                            <p>Bu birime atanmış sakin bulunmuyor.</p>
                            {{-- Buraya "Sakin Ata" butonu veya formu eklenebilir --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
