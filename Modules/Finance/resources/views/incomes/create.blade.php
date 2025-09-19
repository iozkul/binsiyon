<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Yeni Gelir Ekle</h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('finance.incomes.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <x-form.site-selector class="col-span-1" />

                            <div class="col-span-1">
                                <label for="income_date" class="block text-sm font-medium text-gray-700">Gelir Tarihi</label>
                                <input type="date" name="income_date" id="income_date" value="{{ now()->format('Y-m-d') }}" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div class="col-span-1">
                                <label for="site_id" class="block text-sm font-medium text-gray-700">İlgili Site</label>
                                <select id="site_id" name="site_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                                    @foreach($sites as $site)
                                        <option value="{{ $site->id }}" {{ count($sites) === 1 ? 'selected' : '' }}>{{ $site->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-1">
                                <label for="income_date" class="block text-sm font-medium text-gray-700">Gelir Tarihi</label>
                                <input type="date" name="income_date" id="income_date" value="{{ now()->format('Y-m-d') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                            <div class="col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Açıklama</label>
                                <input type="text" name="description" id="description" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Örn: Daire 5'ten Gelen Gecikme Faizi" required>
                            </div>
                            <div class="col-span-1">
                                <label for="amount" class="block text-sm font-medium text-gray-700">Tutar (TL)</label>
                                <input type="number" step="0.01" name="amount" id="amount" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                            </div>
                        </div>
                        <div class="flex justify-end mt-6 pt-4 border-t">
                            <a href="{{ route('finance.incomes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md mr-2">İptal</a>
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-md">Geliri Kaydet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
