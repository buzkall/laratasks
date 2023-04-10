<x-layout>
    <x-slot name="title">{{ __('Tasks') }}</x-slot>


    <div class="container mx-auto p-8">
        <div class="flex items-center">
            <div class="flex-auto">
                <h1 class="text-xl text-gray-500">
                    {{ __('Create task') }}
                </h1>

                <form method="POST" action="{{ route('tasks.store') }}" class="mt-4">
                    @csrf

                    <label for="title" class="text-gray-800">{{ __('Title') }}</label>
                    <input type="text" name="title" id="title" placeholder="{{ __('Title') }}"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                    focus:border-indigo-300 focus: ring focus:ring-indigo-200"
                    value="{{ old('title') }}">

                    @error('title') {{ $message }} @enderror

                    <div class="flex justify-between mt-6">
                        <x-link :target="route('tasks.index')" class="bg-gray-500 hover:bg-gray-400">{{ __('Cancel') }}</x-link>
                        <x-button type="submit">{{ __('Create') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
