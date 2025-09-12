@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Toplu Aidat / Borç Oluştur</h3>
            </div>
            <div class="card-body">
                <p>
                    Buradan seçeceğiniz sitedeki **tüm sakinlere** belirlediğiniz tutarda ve tarihte yeni bir aidat borcu oluşturulacaktır.
                </p>
                <form action="{{ route('fees.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_id" class="form-label">Hangi Siteye Uygulanacak?</label>
                            <select name="site_id" id="site_id" class="form-select" required>
                                <option value="">Lütfen bir site seçin...</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">Açıklama</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Örn: Ekim 2025 Aidatları" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Tutar (TL)</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">Son Ödeme Tarihi</label>
                            <input type="date" name="due_date" id="due_date" class="form-control" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Tüm Sakinlere Aidat Oluştur</button>
                </form>
            </div>
        </div>
    </div>
@endsection
