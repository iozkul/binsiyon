<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Blok Yönetimi') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tüm Bloklar</h5>
            <a href="{{ route('blocks.create') }}" class="btn btn-primary">Yeni Blok Ekle</a>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Blok Adı</th>
                    <th>Site</th>
                    <th>Birim Sayısı</th>
                    <th style="width: 200px;">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($blocks as $block)
                    <tr>
                        <td>{{ $block->name }}</td>
                        <td>{{ $block->site->name }}</td>
                        <td>{{ $block->units_count }}</td>
                        <td>
                            <a href="{{ route('blocks.show', $block->id) }}" class="btn btn-sm btn-info">Görüntüle</a>
                            <a href="{{ route('blocks.edit', $block->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                            <form action="{{ route('blocks.destroy', $block->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu bloğu silmek istediğinizden emin misiniz?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Henüz bir blok eklenmemiş.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($blocks->hasPages())
            <div class="card-footer">
                {{ $blocks->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
