@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Aidat Yönetimi</h3>
                <div>
                    {{-- Yeni aidat oluşturma butonu --}}
                    <a href="{{ route('fees.create') }}" class="btn btn-primary">Toplu Aidat Oluştur</a>
                </div>
            </div>
            <div class="card-body">
                <p>Bu bölümde sakinlerin aidat ödeme durumları listelenmektedir.</p>

                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>Sakin Adı</th>
                        <th>Daire No</th>
                        <th>Tutar</th>
                        <th>Son Ödeme Tarihi</th>
                        <th>Durum</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($fees as $fee)
                        @php
                            // Durumu dinamik olarak belirliyoruz
                            $status = '';
                            $rowClass = '';
                            if ($fee->paid_at) {
                                $status = 'Ödendi';
                                $badgeClass = 'bg-success';
                            } elseif ($fee->due_date < now()->toDateString()) {
                                $status = 'Gecikti';
                                $badgeClass = 'bg-danger';
                                $rowClass = 'table-danger';
                            } else {
                                $status = 'Bekleniyor';
                                $badgeClass = 'bg-warning text-dark';
                            }
                        @endphp
                        <tr class="{{ $rowClass }}">
                            <td>{{ $fee->user->name ?? 'N/A' }}</td>
                            <td>{{ $fee->unit->door_number ?? 'N/A' }}</td>
                            <td>{{ number_format($fee->amount, 2) }} TL</td>
                            <td>{{ \Carbon\Carbon::parse($fee->due_date)->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Detay</a>
                                @if(!$fee->paid_at)
                                    <a href="#" class="btn btn-sm btn-success">Ödendi İşaretle</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Gösterilecek aidat kaydı bulunamadı.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
