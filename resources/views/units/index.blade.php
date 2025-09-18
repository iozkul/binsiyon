<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Birim Yönetimi (Daire/İş Yeri)') }}
        </h2>
    </x-slot>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tüm Birimler</h5>
            @can('manage units')
                <a href="{{ route('units.create') }}" class="btn btn-primary">Yeni Birim Ekle</a>
            @endcan
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Birim No</th>
                    <th>Blok</th>
                    <th>Site</th>
                    <th>Mülk Sahibi</th>
                    <th>Durum</th>
                    <th style="width: 200px;">İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($units as $unit)
                    <tr>
                        <td>{{ $unit->unit_no }}</td>
                        <td>{{ $unit->block->name }}</td>
                        <td>{{ $unit->block->site->name }}</td>
                        <td>{{ $unit->owner->name ?? 'Atanmamış' }}</td>
                        <td>{{ $unit->status }}</td>
                        <td>
                            <a href="{{ route('units.show', $unit->id) }}" class="btn btn-sm btn-info">Görüntüle</a>
                            @can('manage units')
                                <a href="{{ route('units.edit', $unit->id) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu birimi silmek istediğinizden emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Henüz bir birim eklenmemiş.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        @if($units->hasPages())
            <div class="card-footer">
                {{ $units->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
