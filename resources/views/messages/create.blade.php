@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Yeni Mesaj Oluştur</div>
            <div class="card-body">
                <form action="{{ route('messages.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="recipients" class="form-label">Alıcı(lar)</label>
                        <select name="recipients[]" id="recipients" multiple class="form-select" required style="height: 150px;">
                            {{-- Controller'dan gelen kullanıcı listesi --}}
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->getRoleNames()->first() }})</option>
                            @endforeach
                        </select>
                        <small>Birden fazla alıcı seçmek için CTRL tuşuna basılı tutun.</small>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Konu</label>
                        <input type="text" name="subject" id="subject" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="body" class="form-label">Mesajınız</label>
                        <textarea name="body" id="body" class="form-control" rows="6" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gönder</button>
                </form>
            </div>
        </div>
    </div>
@endsection
