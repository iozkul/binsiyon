<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Site Yönetimi') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tüm Siteler</h5>
            @can('manage sites')
                <a href="{{ route('sites.create') }}" class="btn btn-primary">Yeni Site Ekle</a>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Site Adı</th>
                    <th>Yönetici</th>
                    <th>Blok Sayısı</th>
                    <th>Birim Sayısı</th>
                    <th style="width: 200px;">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($sites as $site)
                    <tr>
                        <td>{{ $site->name }}</td>
                        <td>{{ $site->manager->name ?? 'N/A' }}</td>
                        <td>{{ $site->blocks_count }}</td>
                        <td>{{ $site->units_count }}</td>
                        <td>
                            <a href="{{ route('sites.show', $site->id) }}" class="btn btn-sm btn-info">Görüntüle</a>
                            @can('manage sites')
                                <a href="{{ route('sites.edit', $site->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                <form action="{{ route('sites.destroy', $site->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu siteyi silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Henüz bir site eklenmemiş.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($sites->hasPages())
            <div class="card-footer">
                {{ $sites->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
