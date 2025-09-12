@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4>{{ $conversation->subject }}</h4>
                <p class="mb-0"><strong>Katılımcılar:</strong> {{ $conversation->users->pluck('name')->implode(', ') }}</p>
            </div>
            <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                {{-- Mesajları listeleyen döngü --}}
                @foreach($conversation->messages as $message)
                    <div class="d-flex mb-3 {{ $message->user->id == Auth::id() ? 'justify-content-end' : '' }}">
                        <div class="card w-75 {{ $message->user->id == Auth::id() ? 'bg-primary text-white' : 'bg-light' }}">
                            <div class="card-body">
                                <p class="card-text">{{ $message->body }}</p>
                                <small class="d-block text-end">
                                    {{ $message->user->name }} - {{ $message->created_at->format('d.m.Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="card-footer">
                {{-- Cevap Yazma Formu --}}
                <form action="{{ route('messages.reply', $conversation) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <textarea name="body" class="form-control" rows="3" placeholder="Cevabınızı yazın..." required></textarea>
                        <button class="btn btn-primary" type="submit">Gönder</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
