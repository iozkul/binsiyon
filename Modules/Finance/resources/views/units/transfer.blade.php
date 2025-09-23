<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $unit->block->name }} - Daire {{ $unit->unit_number }} - Ünite Devir İşlemi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 p-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700">
                    <p class="font-bold">Önemli Bilgilendirme</p>
                    <p>Kat Mülkiyeti Kanunu uyarınca, yeni mülk sahibi ünitenin geçmiş tüm borçlarından müteselsilen sorumludur. Bu işlem, aşağıda listelenen tüm ödenmemiş borçları yeni mülk sahibine devreder.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-2">Mevcut Durum</h3>
                        <p><strong>Mevcut Malik:</strong> {{ $unit->owner->name ?? 'TANIMSIZ' }}</p>
                        <p><strong>Toplam Ödenmemiş Borç:</strong> <span class="font-bold text-red-600">{{ $outstandingDebts->sum('amount') - $outstandingDebts->sum('paid_amount') }} TL</span></p>

                        <h4 class="font-bold mt-4">Bekleyen Borçlar:</h4>
                        <ul class="list-disc pl-5">
                            @forelse($outstandingDebts as $debt)
                                <li>{{ $debt->description }} - {{ $debt->due_date->format('d/m/Y') }}: {{ $debt->amount - $debt->paid_amount }} TL</li>
                            @empty
                                <li>Bekleyen borç bulunmamaktadır.</li>
                            @endforelse
                        </ul>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-2">Devir İşlemi</h3>
                        <form action="{{ route('units.transfer.process', $unit) }}" method="POST">
                            @csrf
                            <div>
                                <label for="new_owner_id" class="block font-medium text-sm text-gray-700">Yeni Mülk Sahibini Seçin</label>
                                <select name="new_owner_id" id="new_owner_id" class="block mt-1 w-full" required>
                                    <option value="">Seçiniz...</option>
                                    @foreach($potentialOwners as $owner)
                                        <option value="{{ $owner->id }}">{{ $owner->name }} ({{ $owner->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4">
                                <label for="transfer_date" class="block font-medium text-sm text-gray-700">Devir Tarihi (Tapu Tarihi)</label>
                                <input type="date" name="transfer_date" id="transfer_date" class="block mt-1 w-full" required value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="mt-4">
                                <label for="notes" class="block font-medium text-sm text-gray-700">Devir Notları (Opsiyonel)</label>
                                <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full"></textarea>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500" onclick="return confirm('Bu işlemi onaylıyor musunuz? Tüm borçlar yeni malike devredilecektir. Bu işlem geri alınamaz.')">
                                    Devir İşlemini Tamamla
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
