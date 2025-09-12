@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3>Bekleyen Rezervasyon Talepleri</h3>
                <a href="{{ route('reservations.create') }}" class="btn btn-primary">
                    Yeni Rezervasyon Oluştur
                </a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Sakin</th>
                        <th>Sosyal Alan</th>
                        <th>Tarih & Saat</th>
                        <th class="text-end">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($pendingReservations as $reservation)
                        <tr>
                            <td>{{ $reservation->user->name }}</td>
                            <td>{{ $reservation->unit->name_or_number }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($reservation->start_time)->format('d.m.Y H:i') }} -
                                {{ \Carbon\Carbon::parse($reservation->end_time)->format('H:i') }}
                            </td>
                            <td class="text-end">
                                <form action="{{ route('reservations.approve', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Onayla</button>
                                </form>
                                {{-- Reddetme işlemi için ayrı bir form ve rota oluşturulabilir --}}
                                <a href="#" class="btn btn-sm btn-danger">Reddet</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Onay bekleyen rezervasyon talebi bulunmuyor.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
