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
                    focus:border-indigo-300 focus: ring focus:ring-indigo-200">

                    @error('title') {{ $message }} @enderror

                    <div class="flex justify-end mt-6">
                        <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm text-white shadow-sm
                        hover:bg-indigo-500
                        focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            {{ __('Create') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
