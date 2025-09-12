<!-- resources/views/residents/create.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Yeni Site Sakini Ekle</h2>
    <form action="{{ route('residents.store') }}" method="POST">
        @csrf

        <div class="form-group mb-3">
            <label for="block_id">Blok</label>
            <select name="block_id" id="block_id" class="form-control" required>
                @foreach($blocks as $block)
                    <option value="{{ $block->id }}">{{ $block->name }} ({{ $block->site->name }})</option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-3">
            <label for="name">Ad Soyad</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Ad Soyad" required>
        </div>

        <div class="form-group mb-3">
            <label for="email">E-posta</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="E-posta adresi">
        </div>

        <div class="form-group mb-3">
            <label for="phone">Telefon</label>
            <input type="text" name="phone" id="phone" class="form-control" placeholder="05xx xxx xx xx">
        </div>

        <div class="form-group mb-3">
            <label for="type">Kullanıcı Tipi</label>
            <select name="type" id="type" class="form-control">
                <option value="kiracı">Kiracı</option>
                <option value="mülk_sahibi">Mülk Sahibi</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="{{ route('residents.index') }}" class="btn btn-secondary">Geri</a>
    </form>
</div>
@endsection
