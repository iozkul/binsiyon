<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="site_id" :value="__('Site')" />
        <select id="site_id" name="site_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            @foreach($sites as $site)
                <option value="{{ $site->id }}" @selected(old('site_id', $budget->site_id ?? '') == $site->id)>{{ $site->name }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('site_id')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="name" :value="__('Bütçe Adı')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $budget->name ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="year" :value="__('Yıl')" />
        <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year', $budget->year ?? date('Y'))" required />
        <x-input-error :messages="$errors->get('year')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="description" :value="__('Açıklama')" />
        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('description', $budget->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>
</div>

<div class="mt-8">
    <h3 class="text-lg font-medium text-gray-900">Bütçe Kalemleri</h3>
    <div id="items-container" class="mt-4 space-y-4">
        @if(isset($budget) && $budget->items)
            @foreach($budget->items as $index => $item)
                <div class="item grid grid-cols-12 gap-4 items-end p-4 border rounded-md">
                    <div class="col-span-2">
                        <label class="block font-medium text-sm text-gray-700">Tür</label>
                        <select name="items[{{ $index }}][type]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="income" @selected($item->type == 'income')>Gelir</option>
                            <option value="expense" @selected($item->type == 'expense')>Gider</option>
                        </select>
                    </div>
                    <div class="col-span-3">
                        <label class="block font-medium text-sm text-gray-700">Kategori</label>
                        <input type="text" name="items[{{ $index }}][category]" value="{{ $item->category }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="col-span-4">
                        <label class="block font-medium text-sm text-gray-700">Açıklama</label>
                        <input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="col-span-2">
                        <label class="block font-medium text-sm text-gray-700">Tutar</label>
                        <input type="number" step="0.01" name="items[{{ $index }}][amount]" value="{{ $item->amount }}" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>
                    <div class="col-span-1">
                        <button type="button" class="remove-item-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">-</button>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    <button type="button" id="add-item-btn" class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
        Yeni Kalem Ekle
    </button>
</div>

<div class="flex items-center justify-end mt-6">
    <x-primary-button>
        {{ __('Kaydet') }}
    </x-primary-button>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let itemIndex = {{ isset($budget) ? $budget->items->count() : 0 }};
            const container = document.getElementById('items-container');

            document.getElementById('add-item-btn').addEventListener('click', function () {
                const newItem = document.createElement('div');
                newItem.classList.add('item', 'grid', 'grid-cols-12', 'gap-4', 'items-end', 'p-4', 'border', 'rounded-md');
                newItem.innerHTML = `
                <div class="col-span-2">
                    <label class="block font-medium text-sm text-gray-700">Tür</label>
                    <select name="items[${itemIndex}][type]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                        <option value="income">Gelir</option>
                        <option value="expense">Gider</option>
                    </select>
                </div>
                <div class="col-span-3">
                    <label class="block font-medium text-sm text-gray-700">Kategori</label>
                    <input type="text" name="items[${itemIndex}][category]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Örn: Personel Giderleri">
                </div>
                <div class="col-span-4">
                    <label class="block font-medium text-sm text-gray-700">Açıklama</label>
                    <input type="text" name="items[${itemIndex}][description]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Örn: Güvenlik Personeli Maaşı">
                </div>
                <div class="col-span-2">
                    <label class="block font-medium text-sm text-gray-700">Tutar</label>
                    <input type="number" step="0.01" name="items[${itemIndex}][amount]" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="col-span-1">
                    <button type="button" class="remove-item-btn bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">-</button>
                </div>
            `;
                container.appendChild(newItem);
                itemIndex++;
            });

            container.addEventListener('click', function (e) {
                if (e.target && e.target.classList.contains('remove-item-btn')) {
                    e.target.closest('.item').remove();
                }
            });
        });
    </script>
@endpush
