@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Yeni Finansal İşlem Ekle (Gelir/Gider)</div>
            <div class="card-body">
                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="site_id" class="form-label">İşlemin Ait Olduğu Site</label>
                            <select name="site_id" id="site_id" class="form-select" required>
                                <option value="">Site Seçin...</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site->id }}">{{ $site->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">İşlem Türü</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="income">Gelir</option>
                                <option value="expense">Gider</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Kategori</label>
                            <input type="text" name="category" id="category" class="form-control" placeholder="Örn: Personel Maaşı, Elektrik Faturası, Kira Geliri" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Tutar (TL)</label>
                            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Açıklama</label>
                        <textarea name="description" id="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">İşlem Tarihi</label>
                        <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </form>
            </div>
        </div>
    </div>
@endsection
