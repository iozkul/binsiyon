@csrf
<div class="space-y-4">
    <div>
        <label for="site_id" class="block text-sm font-medium text-gray-700">Site</label>
        <select id="site_id" name="site_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option>Site Seçin</option>
            @foreach($sites as $site)
                <option value="{{ $site->id }}" {{ (old('site_id', $monthlyDue->site_id ?? '') == $site->id) ? 'selected' : '' }}>
                    {{ $site->name }}
                </option>
            @endforeach
        </select>
        @error('site_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="apartment_id" class="block text-sm font-medium text-gray-700">Daire</label>
        {{-- TODO: Site seçimine göre daireleri dinamik olarak doldur (JavaScript ile) --}}
        <input type="text" name="apartment_id" id="apartment_id" value="{{ old('apartment_id', $monthlyDue->apartment_id ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Daire ID'si">
        @error('apartment_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="resident_user_id" class="block text-sm font-medium text-gray-700">Sakin (Kullanıcı)</label>
        <input type="text" name="resident_user_id" id="resident_user_id" value="{{ old('resident_user_id', $monthlyDue->resident_user_id ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Sakin Kullanıcı ID'si">
        @error('resident_user_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="period" class="block text-sm font-medium text-gray-700">Dönem</label>
        <input type="date" name="period" id="period" value="{{ old('period', isset($monthlyDue) ? $monthlyDue->period->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('period') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">Tutar (TL)</label>
        <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $monthlyDue->amount ?? '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Örn: 550.50">
        @error('amount') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="due_date" class="block text-sm font-medium text-gray-700">Son Ödeme Tarihi</label>
        <input type="date" name="due_date" id="due_date" value="{{ old('due_date', isset($monthlyDue) ? $monthlyDue->due_date->format('Y-m-d') : '') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
        @error('due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Durum</label>
        <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            @foreach(['pending' => 'Beklemede', 'paid' => 'Ödendi', 'overdue' => 'Gecikmiş', 'partially_paid' => 'Kısmi Ödendi'] as $key => $value)
                <option value="{{ $key }}" {{ (old('status', $monthlyDue->status ?? 'pending') == $key) ? 'selected' : '' }}>
                    {{ $value }}
                </option>
            @endforeach
        </select>
        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>
</div>

<div class="flex justify-end mt-6">
    <a href="{{ route('finance.monthly-dues.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
        İptal
    </a>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r">
        Kaydet
    </button>
</div>
