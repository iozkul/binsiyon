<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Site Yönetimi- Birim Düzenle') }}
        </h2>
    </x-slot>
        <div class="card">
            <div class="card-header">
                Birim Düzenle: {{ $unit->name_or_number }}
            </div>
            <div class="card-body">
                <form action="{{ route('units.update', $unit->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name_or_number" class="form-label">Birim Adı / Numarası</label>
                        <input type="text" name="name_or_number" id="name_or_number" class="form-control" value="{{ old('name_or_number', $unit->name_or_number) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="block_id" class="form-label">Ait Olduğu Blok</label>
                        <select name="block_id" id="block_id" class="form-select" required>
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}" {{ $unit->block_id == $block->id ? 'selected' : '' }}>
                                    {{ $block->site->name ?? '' }} - {{ $block->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="floor" class="form-label">Bulunduğu Kat</label>
                        <input type="number" name="floor" id="floor" class="form-control" value="{{ old('floor', $unit->floor) }}">
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Birim Türü</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="apartment" {{ $unit->type == 'apartment' ? 'selected' : '' }}>Daire</option>
                            <option value="commercial" {{ $unit->type == 'commercial' ? 'selected' : '' }}>Ticari Alan</option>
                            <option value="social" {{ $unit->type == 'social' ? 'selected' : '' }}>Sosyal Alan</option>
                        </select>
                    </div>

                    <hr>
                    <h5>Özellikler</h5>
                    <div class="mb-3">
                        <label for="properties_square_meters" class="form-label">Metrekare (m²)</label>
                        <input type="number" name="properties[square_meters]" id="properties_square_meters" class="form-control" value="{{ old('properties.square_meters', $unit->properties['square_meters'] ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="properties_room_count" class="form-label">Oda Sayısı</label>
                        <input type="number" name="properties[room_count]" id="properties_room_count" class="form-control" value="{{ old('properties.room_count', $unit->properties['room_count'] ?? '') }}">
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="properties[uses_elevator]" value="1"
                            {{ old('properties.uses_elevator', $unit->properties['uses_elevator'] ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label">Asansör Kullanıyor</label>
                    </div>
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" name="properties[gets_dues_share]" value="1"
                            {{ old('properties.gets_dues_share', $unit->properties['gets_dues_share'] ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label">Aidattan Pay Alır (İşaretli değilse aidattan muaftır)</label>
                    </div>
                    {{-- DAİREYE ÖZEL ALANLAR --}}
                    <div id="apartment_fields" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Oda Sayısı</label>
                            <input type="number" name="properties[room_count]" class="form-control" value="{{ $unit->properties['room_count'] ?? '' }}">
                        </div>
                    </div>

                    {{-- SOSYAL ALANA ÖZEL ALANLAR --}}
                    <div id="social_fields" style="display: none;">
                        <label class="form-label">Bu Alanda Neler Var?</label>
                        <div class="form-check">
                            <input type="checkbox" name="properties[amenities][pool]" value="1" class="form-check-input" {{ isset($unit->properties['amenities']['pool']) ? 'checked' : '' }}>
                            <label class="form-check-label">Yüzme Havuzu</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="properties[amenities][gym]" value="1" class="form-check-input" {{ isset($unit->properties['amenities']['gym']) ? 'checked' : '' }}>
                            <label class="form-check-label">Fitness Salonu</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="properties[amenities][park]" value="1" class="form-check-input" {{ isset($unit->properties['amenities']['park']) ? 'checked' : '' }}>
                            <label class="form-check-label">Çocuk Parkı</label>
                        </div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between">
                            <span>Otopark Alanları</span>
                            {{-- Yeni otopark alanı eklemek için bir modal (popup) açılabilir --}}
                            <button class="btn btn-success btn-sm">Yeni Otopark Alanı Ekle</button>
                        </div>
                        <div class="card-body">
                            <ul class="list-group">
                                @forelse($unit->parkingSpaces as $space)
                                    <li class="list-group-item">
                                        <strong>Yer No:</strong> {{ $space->space_number }}
                                        ({{ $space->location ?? 'Konum Belirtilmemiş' }})
                                    </li>
                                @empty
                                    <li class="list-group-item">Bu birime atanmış otopark alanı bulunmuyor.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    <hr>
                    <h5>Finansal Kurallar</h5>
                    <div class="mb-3">
                        <label class="form-label">Varsayılan Aidat Tutarı (TL)</label>
                        <input type="number" name="properties[base_due_amount]" class="form-control" value="{{ $unit->properties['base_due_amount'] ?? '' }}">
                        <small>Bu birime özel bir Aidat Şablonu yoksa, toplu aidat oluşturulurken bu tutar kullanılır. Muaf ise 0 girin.</small>
                    </div>

                    <div class="mb-3">
                        <label for="deed_status" class="form-label">Tapu Durumu</label>
                        <input type="text" name="deed_status" id="deed_status" class="form-control" value="{{ old('deed_status', $unit->deed_status) }}">
                    </div>

                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </form>
            </div>
        </div>
</x-admin-layout>>
