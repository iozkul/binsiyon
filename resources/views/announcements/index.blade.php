@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Duyurular</h3>
                {{-- Sadece yetkisi olanlar yeni duyuru ekleyebilsin --}}
                @can('create', App\Models\Announcement::class)
                    <a href="{{ route('announcements.create') }}" class="btn btn-primary">Yeni Duyuru Ekle</a>
                @endcan
            </div>
            <div class="card-body">
                @if($announcements->isEmpty())
                    <div class="alert alert-info text-center">
                        Gösterilecek duyuru bulunmuyor.
                    </div>
                @else
                    {{-- Duyuruları listeleyen döngü --}}
                    @foreach($announcements as $announcement)
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">{{ $announcement->title }}</h5>
                                <p class="card-text">{!! nl2br(e($announcement->body)) !!}</p>
                            </div>
                            <div class="card-footer text-muted d-flex justify-content-between">
                            <span>
                                <strong>Yayınlayan:</strong> {{ $announcement->author->name ?? 'Bilinmiyor' }} |
                                <strong>Site:</strong> {{ $announcement->site->name ?? 'Genel' }}
                            </span>
                                <span>
                                <strong>Yayın Tarihi:</strong> {{ $announcement->published_at ? \Carbon\Carbon::parse($announcement->published_at)->format('d.m.Y H:i') : 'Henüz Yayınlanmadı' }}

                                    {{-- Sadece yetkisi olanlar düzenleme ve silme butonlarını görsün --}}
                                    @can('update', $announcement)
                                        <a href="{{ route('announcements.edit', $announcement) }}" class="btn btn-sm btn-warning ms-2">Düzenle</a>
                                    @endcan
                                    @can('delete', $announcement)
                                        <form action="{{ route('announcements.destroy', $announcement) }}" method="POST" class="d-inline">
                                        @csrf
                                            @method('DELETE')
                                        <button type-="submit" class="btn btn-sm btn-danger">Sil</button>
                                    </form>
                                    @endcan
                            </span>
                            </div>
                        </div>
                    @endforeach

                    {{-- Sayfalama linkleri --}}
                    {{ $announcements->links() }}
                @endif
            </div>
        </div>
    </div>
@endsection
