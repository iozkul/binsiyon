@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3>Cari Hesap Özeti: {{ $user->name }}</h3>
                <p class="mb-0">Bakiye: <strong class="fs-5 {{ $balance >= 0 ? 'text-success' : 'text-danger' }}">{{ number_format($balance, 2) }} TL</strong></p>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Tarih</th>
                        <th>Açıklama</th>
                        <th class="text-end">Borç (-)</th>
                        <th class="text-end">Ödeme (+)</th>
                        <th class="text-end">Bakiye</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ledger as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->date)->format('d.m.Y') }}</td>
                            <td>{{ $item->description }}</td>
                            @if($item->type === 'borc')
                                <td class="text-end text-danger">-{{ number_format($item->amount, 2) }}</td>
                                <td class="text-end"></td>
                            @else
                                <td class="text-end"></td>
                                <td class="text-end text-success">+{{ number_format($item->amount, 2) }}</td>
                            @endif
                            <td class="text-end fw-bold">{{ number_format($item->balance, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
