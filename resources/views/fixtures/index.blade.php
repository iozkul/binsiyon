@extends('layouts.app') {{-- Ana layout dosyanızın yolunu belirtin --}}

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Demirbaş Yönetimi ve Bakım Takibi</h1>
            <a href="{{ route('fixtures.create') }}" class="btn btn-primary">Yeni Demirbaş Ekle</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Demirbaş Adı</th>
                            <th>Site</th>
                            <th>Marka / Model</th>
                            <th>Son Bakım</th>
                            <th>Sıradaki Bakım</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($fixtures as $fixture)
                            @php
                                $rowClass = '';
                                if ($fixture->next_maintenance_date) {
                                    if ($fixture->next_maintenance_date->isPast()) {
                                        $rowClass = 'table-danger'; // Bakım geçmiş
                                    } elseif ($fixture->next_maintenance_date->diffInDays(now()) <= 7) {
                                        $rowClass = 'table-warning'; // Bakıma 7 gün veya daha az kalmış
                                    }
                                }
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>{{ $fixture->id }}</td>
                                <td>{{ $fixture->name }}</td>
                                <td>{{ $fixture->site->name ?? 'N/A' }}</td>
                                <td>{{ $fixture->brand }} / {{ $fixture->model }}</td>
                                <td>{{ $fixture->last_maintenance_date ? $fixture->last_maintenance_date->format('d.m.Y') : '-' }}</td>
                                <td>
                                    @if($fixture->next_maintenance_date)
                                        <strong>{{ $fixture->next_maintenance_date->format('d.m.Y') }}</strong>
                                        <small>({{ $fixture->next_maintenance_date->diffForHumans() }})</small>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ strtoupper($fixture->status) }}</span>
                                </td>
                                <td>
                                    @can('update', $fixture)
                                    <a href="{{ route('fixtures.edit', $fixture->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                    @endcan
                                    @can('delete', $fixture)
                                    <form action="{{ route('fixtures.destroy', $fixture->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu demirbaşı silmek istediğinizden emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Kayıtlı demirbaş bulunamadı.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
