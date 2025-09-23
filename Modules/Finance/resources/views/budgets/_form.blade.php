@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="name" :value="__('Bütçe Adı')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $budget->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="description" :value="__('Açıklama')" />
        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $budget->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="start_date" :value="__('Başlangıç Tarihi')" />
        <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', isset($budget) ? $budget->start_date->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="end_date" :value="__('Bitiş Tarihi')" />
        <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', isset($budget) ? $budget->end_date->format('Y-m-d') : '')" required />
        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="total_income" :value="__('Toplam Tahmini Gelir (TL)')" />
        <x-text-input id="total_income" class="block mt-1 w-full" type="number" name="total_income" step="0.01" :value="old('total_income', $budget->total_income ?? '0.00')" />
        <x-input-error :messages="$errors->get('total_income')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="total_expense" :value="__('Toplam Tahmini Gider (TL)')" />
        <x-text-input id="total_expense" class="block mt-1 w-full" type="number" name="total_expense" step="0.01" :value="old('total_expense', $budget->total_expense ?? '0.00')" />
        <x-input-error :messages="$errors->get('total_expense')" class="mt-2" />
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('finance.budgets.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">
        {{ __('İptal') }}
    </a>
    <x-primary-button>
        {{ isset($budget) ? __('Güncelle') : __('Kaydet') }}
    </x-primary-button>
</div>
