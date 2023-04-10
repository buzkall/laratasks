<x-layout>
    <x-slot name="title">{{ __('Tasks') }}</x-slot>


    <div class="container mx-auto p-8">
        <div class="flex items-center">
            <div class="flex-auto">
                <div class="flex justify-between">
                    <h1 class="text-xl text-gray-500">
                        {{ __('Edit task :id', ['id' => $task->id]) }}
                    </h1>

                    @include('tasks.delete')
                </div>

                <form method="POST" action="{{ route('tasks.update', $task) }}" class="mt-4">
                    @csrf
                    @method('PUT')

                    <label for="title" class="text-gray-800">{{ __('Title') }}</label>
                    <input type="text" name="title" id="title" placeholder="{{ __('Title') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                    focus:border-indigo-300 focus: ring focus:ring-indigo-200"
                           value="{{ old('title', $task->title) }}">

                    @error('title') {{ $message }} @enderror

                    <div class="flex justify-between mt-6">
                        <x-link :target="route('tasks.index')" class="bg-gray-500 hover:bg-gray-400">{{ __('Cancel') }}</x-link>
                        <x-button type="submit">{{ __('Update') }}</x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>
