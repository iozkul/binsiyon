<!-- resources/views/blocks/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Yeni Blok Ekle</h2>
    <form action="{{ route('blocks.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label for="site_id">Site</label>
            <select name="site_id" id="site_id" class="form-control" required>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="name">Blok Adı</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Blok adı girin" required>
        </div>

        <div class="form-group mb-3">
            <label for="floor_count">Kat Sayısı</label>
            <input type="number" name="floor_count" id="floor_count" class="form-control" min="1">
        </div>

        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="{{ route('blocks.index') }}" class="btn btn-secondary">Geri</a>
    </form>
</div>
@endsection
