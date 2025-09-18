<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Birim Yönetimi (Daire/İş Yeri)') }}
        </h2>
    </x-slot>

        <div class="card">
            <div class="card-header">Yeni Site Oluştur</div>
            <div class="card-body">
                <form action="{{ route('sites.store') }}" method="POST">
                    @csrf

                    {{-- Site Adı --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Site Adı</label>
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Ülke</label>
                            <input type="text" name="country" id="country" class="form-control" value="Türkiye" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">İl</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="Örn: İstanbul" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="district" class="form-label">İlçe</label>
                            <input type="text" name="district" id="district" class="form-control" placeholder="Örn: Kadıköy" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="postal_code" class="form-label">Posta Kodu (İsteğe Bağlı)</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address_line" class="form-label">Adres Detayı (Mahalle, Cadde, Sokak, No)</label>
                        <textarea name="address_line" id="address_line" class="form-control" rows="3" required></textarea>
                    </div>

                    {{-- Yönetici Atama (Daha önceki kodumuz) --}}
                    {{--@can('assign-manager')--}}
                    @if(auth()->user()->hasRole('super-admin'))
                        <div class="mb-3">
                            <label for="manager_ids" class="form-label">Site Yöneticileri</label>
                            <select name="manager_ids[]" id="manager_ids" multiple class="form-control">
                                @foreach($potential_managers as $manager)
                                    <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                            <small>Birden fazla seçmek için CTRL (veya Mac'te Command) tuşuna basılı tutun.</small>
                        </div>
                    @endif
                    {{-- @endcan--}}

                    <hr>

                    {{-- DİNAMİK ALANLARIN BAŞLANGICI --}}
                    <div class="mb-3">
                        <label for="block_count_input" class="form-label"><strong>Oluşturulacak Blok Sayısı</strong></label>
                        <input type="number" id="block_count_input" class="form-control" min="1" value="1">
                    </div>

                    {{-- JavaScript ile oluşturulacak blok bilgileri bu div'in içine eklenecek --}}
                    <div id="blocks_container"></div>
                    {{-- DİNAMİK ALANLARIN BİTİŞİ --}}


                    <button type="submit" class="btn btn-primary mt-3">Siteyi ve Blokları Oluştur</button>
                </form>
            </div>
        </div>


    <script>
        // Sayfa yüklendiğinde ve blok sayısı input'u değiştiğinde çalışacak fonksiyon
        const blockCountInput = document.getElementById('block_count_input');
        const blocksContainer = document.getElementById('blocks_container');

        // Başlangıçta 1 blok için alanları oluştur
        generateBlockFields();

        // Input değeri her değiştiğinde alanları yeniden oluştur
        blockCountInput.addEventListener('input', generateBlockFields);

        function generateBlockFields() {
            // Girilen blok sayısını al
            const count = parseInt(blockCountInput.value) || 0;

            // Önceki alanları temizle
            blocksContainer.innerHTML = '';

            // Girilen sayı kadar yeni alan oluştur
            for (let i = 0; i < count; i++) {
                // Her blok için bir div oluşturuyoruz
                const blockDiv = document.createElement('div');
                blockDiv.className = 'card mb-3';

                // Dinamik HTML içeriği
                blockDiv.innerHTML = `
                <div class="card-header">
                    <h5>Blok ${i + 1} Bilgileri</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="block_name_${i}" class="form-label">Blok Adı (Örn: A Blok)</label>
                            <input type="text" name="blocks[${i}][name]" id="block_name_${i}" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="apartments_count_${i}" class="form-label">Daire Sayısı</label>
                            <input type="number" name="blocks[${i}][apartment_count]" id="apartments_count_${i}" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="commercials_count_${i}" class="form-label">Ticari Alan Sayısı</label>
                            <input type="number" name="blocks[${i}][commercial_count]" id="commercials_count_${i}" class="form-control" value="0" min="0">
                        </div>
                        <div class="col-md-4">
                            <label for="socials_count_${i}" class="form-label">Sosyal Alan Sayısı</label>
                            <input type="number" name="blocks[${i}][social_count]" id="socials_count_${i}" class="form-control" value="0" min="0">
                        </div>
                    </div>
                </div>
            `;

                // Oluşturulan div'i ana container'a ekle
                blocksContainer.appendChild(blockDiv);
            }
        }
    </script>
</x-admin-layout>
