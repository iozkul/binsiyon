@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Toplu Birim Ekle</div>
            <div class="card-body">
                <form action="{{ route('units.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="block_id" class="form-label">Hangi Bloğa Eklenecek?</label>
                        <select name="block_id" id="block_id" class="form-select" required>
                            @foreach($blocks as $block)
                                {{-- Eğer bir önceki sayfadan geldiyse o bloğu otomatik seç --}}
                                <option value="{{ $block->id }}" {{ request('block_id') == $block->id ? 'selected' : '' }}>
                                    {{ $block->site->name }} - {{ $block->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Oluşturulacak Daire Sayısı</label>
                            <input type="number" name="apartment_count" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Oluşturulacak Ticari Alan Sayısı</label>
                            <input type="number" name="commercial_count" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Oluşturulacak Sosyal Alan Sayısı</label>
                            <input type="number" name="social_count" class="form-control" value="0" min="0">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Birimleri Oluştur</button>
                </form>
            </div>
        </div>
    </div>
@endsection
