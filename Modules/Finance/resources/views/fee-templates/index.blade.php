@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3>Aidat Şablonları</h3>
                <a href="{{ route('fee-templates.create') }}" class="btn btn-primary">Yeni Şablon Ekle</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Şablon Adı</th>
                        <th>Tutar</th>
                        <th>Uygulandığı Yer</th>
                        <th>İşlemler</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($templates as $template)
                        <tr>
                            <td>{{ $template->name }}</td>
                            <td>{{ number_format($template->amount, 2) }} TL</td>
                            <td>
                                <strong>{{ class_basename($template->applicable_type) }}:</strong>
                                {{ $template->applicable->name ?? $template->applicable->name_or_number }}
                            </td>
                            <td>
                                <a href="{{ route('fee-templates.edit', $template) }}" class="btn btn-sm btn-warning">Düzenle</a>
                                {{-- Silme butonu formu --}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
