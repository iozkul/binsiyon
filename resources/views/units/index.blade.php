@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tüm Birimler (Daireler, İş Yerleri vb.)</span>
                <a href="{{ route('units.create') }}" class="btn btn-primary btn-sm">Yeni Birim Ekle</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Birim Adı/No</th>
                        <th>Türü</th>
                        <th>Blok</th>
                        <th>Site</th>
                        <th style="width: 200px;">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($units as $unit)
                        <tr>
                            <td>{{ $unit->name_or_number }}</td>
                            <td>{{ ucfirst($unit->type) }}</td>
                            <td>{{ $unit->block->name ?? 'N/A' }}</td>
                            <td>{{ $unit->block->site->name ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('units.show', $unit) }}" class="btn btn-info btn-sm">İncele</a>
                                <a href="{{ route('units.edit', $unit) }}" class="btn btn-warning btn-sm">Düzenle</a>
                                <form action="{{ route('units.destroy', $unit) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu birimi silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Sistemde kayıtlı birim bulunmuyor.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $units->links() }}
            </div>
        </div>
    </div>
@endsection
