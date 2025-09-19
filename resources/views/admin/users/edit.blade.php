<x-app-layout>
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
        </div>
        <div class="mt-4">
            <x-input-label for="site_id" :value="__('Atanacak Site')" />
            <select name="sites[]" id="sites" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Site Seçilmedi</option>
                @foreach($sites as $site)
                    <option value="{{ $site->id }}" @if($user->site_id == $site->id) selected @endif>{{ $site->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Güncelle') }}
            </x-primary-button>
        </div>
    </form>
</x-app-layout>
