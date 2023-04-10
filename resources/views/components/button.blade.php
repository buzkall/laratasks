@props(['type' => 'button'])

<button type="{{ $type }}"
        {{ $attributes->class(['rounded-md bg-indigo-600 px-3 py-2 text-sm text-white shadow-sm
        hover:bg-indigo-500
        focus-visible:outline-offset-2 focus-visible:outline-indigo-600']) }}>
    {{ $slot }}
</button>
