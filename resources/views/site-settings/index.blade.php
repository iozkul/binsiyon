<x-admin-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Site Yönetim Ayarları') }}
        </h2>
    </x-slot>

        <h1>Site Yönetim Ayarları</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('site-settings.update') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">Finansal Ayarlar</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="due_late_fee_rate" class="form-label">Aylık Gecikme Tazminatı Oranı (%)</label>
                        <input type="number" step="0.01" class="form-control" id="due_late_fee_rate" name="due_late_fee_rate"
                               value="{{ $settings['due_late_fee_rate'] ?? '' }}">
                        <div class="form-text">Örn: 5. Kat Mülkiyeti Kanunu'na göre yasal sınırı aşmamaya dikkat ediniz.</div>
                    </div>

                    <div class="mb-3">
                        <label class="block font-medium text-sm text-gray-700">{{ __('Aidat Hesaplama Yöntemi') }}</label>
                        @php
                            $currentMethod = $settings['fee_calculation_method'] ?? 'fixed';
                        @endphp
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="fee_calculation_method" value="fixed" class="form-radio" {{ $currentMethod == 'fixed' ? 'checked' : '' }}>
                                <span class="ml-2">Sabit Tutar (Tüm daireler için eşit)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="fee_calculation_method" value="per_sqm" class="form-radio" {{ $currentMethod == 'per_sqm' ? 'checked' : '' }}>
                                <span class="ml-2">m² Başına (Daire büyüklüğüne göre)</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="fee_calculation_method" value="land_share" class="form-radio" {{ $currentMethod == 'land_share' ? 'checked' : '' }}>
                                <span class="ml-2">Arsa Payı Oranında</span>
                            </label>
                        </div>
                        <p class="text-sm text-gray-500 mt-1">m² ve Arsa Payı yöntemleri için tüm bağımsız birimlerin bilgilerinin eksiksiz girildiğinden emin olunuz.</p>
                    </div>

                    <div class="mt-4">
                        <label for="late_fee_rate" class="block font-medium text-sm text-gray-700">{{ __('Aylık Gecikme Zammı Oranı (%)') }}</label>
                        <input id="late_fee_rate" class="block mt-1 w-full md:w-1/4 border-gray-300 rounded-md shadow-sm" type="number" step="0.01" name="late_fee_rate"  value="{{ old('late_fee_rate', $settings['late_fee_rate'] ?? '5.00') }}" />
                        <p class="text-sm text-gray-500 mt-1">KMK uyarınca yasal olarak uygulanacak aylık faiz oranıdır.</p>
                        @error('late_fee_rate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

            </div>

            <div class="card mt-4">
                <div class="card-header">Gider Paylaşım Kuralları</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="elevator_fee_exempt_floors" class="form-label">Asansör Giderinden Muaf Katlar</label>
                        <input type="text" class="form-control" id="elevator_fee_exempt_floors" name="elevator_fee_exempt_floors"
                               value="{{ $settings['elevator_fee_exempt_floors'] ?? '' }}">
                        <div class="form-text">Zemin kat ve altındaki katlar genellikle muaftır. Kat numaralarını virgülle ayırarak girin. Örn: 0,-1</div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Ayarları Kaydet</button>
        </form>
</x-admin-layout>>
