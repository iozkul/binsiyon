@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Gelen Kutusu</h3>
                <a href="{{ route('messages.create') }}" class="btn btn-primary">Yeni Mesaj Oluştur</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse($conversations as $conversation)
                    {{-- TODO: Okunmamış mesajları belirlemek için bir mantık eklenmeli --}}
                    <a href="{{ route('messages.show', $conversation) }}" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ $conversation->subject }}</h5>
                            <small>{{ $conversation->messages->last()->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-1">
                            <strong>Katılımcılar:</strong>
                            {{ $conversation->users->pluck('name')->implode(', ') }}
                        </p>
                        <small class="text-muted">
                            Son Mesaj: {{ Str::limit($conversation->messages->last()->body, 100) }}
                        </small>
                    </a>
                @empty
                    <div class="list-group-item">
                        <p class="text-center my-3">Gelen kutunuzda hiç mesaj bulunmuyor.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
