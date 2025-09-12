@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tüm Daireler</span>
                <a href="{{ route('apartments.create') }}" class="btn btn-primary btn-sm">Yeni Daire Ekle</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Daire No</th>
                        <th>Blok</th>
                        <th>Site</th>
                        <th style="width: 200px;">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($apartments as $apartment)
                        <tr>
                            <td>{{ $apartment->door_number ?? 'N/A' }}</td>
                            <td>{{ $apartment->block->name ?? 'N/A' }}</td>
                            <td>{{ $apartment->block->site->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('apartments.show', $apartment) }}" class="btn btn-info btn-sm">İncele</a>
                                <a href="{{ route('apartments.edit', $apartment) }}" class="btn btn-warning btn-sm">Düzenle</a>
                                <form action="{{ route('apartments.destroy', $apartment) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu daireyi silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Sistemde kayıtlı daire bulunmuyor.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{-- Sayfalama linkleri için --}}
                {{ $apartments->links() }}
            </div>
        </div>
    </div>
@endsection
