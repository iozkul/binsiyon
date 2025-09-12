@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $resource->name }} - Rezervasyonları</h1>
        @can('create', [App\Models\Reservation::class, $resource])
            <a href="{{ route('reservations.create', $resource) }}" class="btn btn-primary mb-3">Yeni Rezervasyon Yap</a>
        @endcan

        {{-- Burada fullcalendar.io gibi bir kütüphane ile görsel bir takvim oluşturulabilir --}}
        {{-- Şimdilik basit bir liste gösterelim --}}

        <ul class="list-group">
            @forelse($reservations as $reservation)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $reservation->user->name }}</strong><br>
                        <small>{{ $reservation->start_time->format('d M Y, H:i') }} - {{ $reservation->end_time->format('H:i') }}</small>
                    </div>
                    <span class="badge badge-pill
                    @if($reservation->status == 'approved') badge-success @endif
                    @if($reservation->status == 'pending') badge-warning @endif
                    @if($reservation->status == 'rejected') badge-danger @endif
                ">{{ $reservation->status }}</span>

                    <div>
                        @can('approve', $reservation)
                            @if($reservation->status == 'pending')
                                <form action="{{ route('reservations.approve', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Onayla</button>
                                </form>
                                <form action="{{ route('reservations.reject', $reservation) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-danger">Reddet</button>
                                </form>
                            @endif
                        @endcan
                        @can('cancel', $reservation)
                            <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-secondary">İptal Et</button>
                            </form>
                        @endcan
                    </div>
                </li>
            @empty
                <li class="list-group-item">Henüz rezervasyon yok.</li>
            @endforelse
        </ul>
    </div>
@endsection
