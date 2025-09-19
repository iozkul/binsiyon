<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            '{{ $site->name }}' Sitesi için Modül Yönetimi
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.sites.modules.update', $site) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            @foreach($modules as $module)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="module_{{ $module->id }}" name="modules[]" type="checkbox" value="{{ $module->id }}"
                                               class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                            {{ in_array($module->id, $assignedModules) ? 'checked' : '' }}>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="module_{{ $module->id }}" class="font-medium text-gray-700">{{ $module->name }}</label>
                                        <p class="text-gray-500">{{ $module->description }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Kaydet
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
