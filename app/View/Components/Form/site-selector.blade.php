@props(['name' => 'site_id', 'label' => 'İlgili Site'])

<div {{ $attributes }}>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
        @if(count($sites) > 1)
            <option value="">Lütfen bir site seçin...</option>
        @endif
        @foreach($sites as $site)
            <option value="{{ $site->id }}" {{ $selected == $site->id ? 'selected' : '' }}>
                {{ $site->name }}
            </option>
        @endforeach
    </select>
</div>
