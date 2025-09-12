@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Gelir & Gider Kayıtları</h3>
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">Yeni İşlem Ekle</a>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Site</th>
                        <th>Tür</th>
                        <th>Kategori</th>
                        <th>Açıklama</th>
                        <th class="text-end">Tutar (TL)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d.m.Y') }}</td>
                            <td>{{ $transaction->site->name ?? 'N/A' }}</td>
                            <td>
                                @if($transaction->type == 'income')
                                    <span class="badge bg-success">Gelir</span>
                                @else
                                    <span class="badge bg-danger">Gider</span>
                                @endif
                            </td>
                            <td>{{ $transaction->category }}</td>
                            <td>{{ $transaction->description }}</td>
                            <td class="text-end fw-bold">{{ number_format($transaction->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Kayıtlı finansal işlem bulunmuyor.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
