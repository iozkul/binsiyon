@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            {{-- Yeni Rezervasyon Formu --}}
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Yeni Rezervasyon Yap</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('reservations.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="unit_id" class="form-label">Hangi Sosyal Alan?</label>
                                <select name="unit_id" id="unit_id" class="form-select" required>
                                    @foreach($reservableUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name_or_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="start_time" class="form-label">Başlangıç Zamanı</label>
                                <input type="datetime-local" name="start_time" id="start_time" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="end_time" class="form-label">Bitiş Zamanı</label>
                                <input type="datetime-local" name="end_time" id="end_time" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Rezervasyon Talebi Gönder</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Takvim / Mevcut Rezervasyonlar --}}
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3>Rezervasyon Takvimi</h3>
                    </div>
                    <div class="card-body">
                        {{-- Buraya FullCalendar.js gibi bir kütüphane ile dinamik bir takvim entegre edilebilir --}}
                        <p>Mevcut Rezervasyonlar:</p>
                        <ul class="list-group">
                            @forelse($reservations as $reservation)
                                <li class="list-group-item">
                                    <strong>{{ $reservation->unit->name_or_number }}:</strong>
                                    {{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y H:i') }} - {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                                    <span class="badge float-end bg-{{ $reservation->status == 'approved' ? 'success' : 'warning' }}">{{ ucfirst($reservation->status) }}</span>
                                </li>
                            @empty
                                <li class="list-group-item">Henüz rezervasyon bulunmuyor.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
