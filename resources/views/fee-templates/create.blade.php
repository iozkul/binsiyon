@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Yeni Aidat Şablonu Ekle</div>
            <div class="card-body">
                <form action="{{ route('fee-templates.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Şablon Adı</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Tutar (TL)</label>
                        <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label for="applicable_level" class="form-label">Uygulanacağı Seviye</label>
                        <select id="applicable_level" name="applicable_level" class="form-select" required>
                            <option value="">Seçin...</option>
                            <option value="site">Site Geneli</option>
                            <option value="block">Tek Blok</option>
                            <option value="unit">Tek Birim (Daire/Dükkan)</option>
                        </select>
                    </div>

                    {{-- Dinamik olarak görünecek dropdown'lar --}}
                    <div id="site_select_div" class="mb-3" style="display: none;">
                        <label for="site_id" class="form-label">Site Seçin</label>
                        <select name="site_id" id="site_id" class="form-select">
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}">{{ $site->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="block_select_div" class="mb-3" style="display: none;">
                        <label for="block_id" class="form-label">Blok Seçin</label>
                        <select name="block_id" id="block_id" class="form-select">
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->site->name }} - {{ $block->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="unit_select_div" class="mb-3" style="display: none;">
                        <label for="unit_id" class="form-label">Birim Seçin</label>
                        <select name="unit_id" id="unit_id" class="form-select">
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->block->site->name }} - {{ $unit->block->name }} - {{ $unit->name_or_number }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Şablonu Kaydet</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('applicable_level').addEventListener('change', function() {
            // Tüm dropdown'ları gizle
            document.getElementById('site_select_div').style.display = 'none';
            document.getElementById('block_select_div').style.display = 'none';
            document.getElementById('unit_select_div').style.display = 'none';

            // Seçilen değere göre ilgili dropdown'ı göster
            const selectedValue = this.value;
            if (selectedValue) {
                document.getElementById(selectedValue + '_select_div').style.display = 'block';
            }
        });
    </script>
@endsection
