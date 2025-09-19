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
                        <label for="due_late_fee_rate" class="form-label">Aylık Gecikme Faizi Oranı (%)</label>
                        <input type="number" step="0.01" class="form-control" id="due_late_fee_rate" name="due_late_fee_rate"
                               value="{{ $settings['due_late_fee_rate'] ?? '' }}">
                        <div class="form-text">Örn: 5. Kat Mülkiyeti Kanunu'na göre yasal sınırı aşmamaya dikkat ediniz.</div>
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
