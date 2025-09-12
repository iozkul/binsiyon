@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1>Finans Yönetimi</h1>
            </div>
            <div class="card-body">
                <p>Bu sayfada aidat, borç, gelir ve gider durumu gibi finansal modüllerin bir özeti gösterilecektir.</p>

                {{-- Örnek Linkler --}}
                <div class="list-group">
                    <a href="{{ route('fees.index') }}" class="list-group-item list-group-item-action">Aidat Yönetimi</a>
                    <a href="{{ route('debts.index') }}" class="list-group-item list-group-item-action">Borçlular Listesi</a>
                    <a href="{{ route('incomes.index') }}" class="list-group-item list-group-item-action">Gelirler</a>
                    <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action">Giderler</a>
                </div>
            </div>
        </div>
    </div>
@endsection
