@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tüm Bloklar</span>
                <a href="{{ route('blocks.create') }}" class="btn btn-primary btn-sm">Yeni Blok Ekle</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Blok Adı</th>
                        <th>Ait Olduğu Site</th>
                        <th>Daire Sayısı</th>
                        <th style="width: 200px;">İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($blocks as $block)
                        <tr>
                            <td>{{ $block->name }}</td>
                            <td>{{ $block->site->name ?? 'N/A' }}</td>
                            <td>{{ $block->units_count }}</td>
                            <td>
                                <a href="{{ route('blocks.show', $block) }}" class="btn btn-info btn-sm">İncele</a>
                                <a href="{{ route('blocks.edit', $block) }}" class="btn btn-warning btn-sm">Düzenle</a>
                                <form action="{{ route('blocks.destroy', $block) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu bloğu silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Sistemde kayıtlı blok bulunmuyor.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $blocks->links() }}
            </div>
        </div>
    </div>
@endsection
