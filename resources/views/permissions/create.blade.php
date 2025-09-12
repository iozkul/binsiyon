@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Yeni Yetki Oluştur</div>
            <div class="card-body">
                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Yetki Adı</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Örn: manage-surveys" required>
                        <small class="form-text text-muted">Yetki adları genellikle küçük harf ve '-' ile yazılır.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
@endsection
