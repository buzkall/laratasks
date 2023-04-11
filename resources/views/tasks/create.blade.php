<div class="container mx-auto px-8">
    <div class="flex items-center">
        <div class="flex-auto">
            <form method="POST" action="{{ route('tasks.store') }}" class="mt-4">
                @csrf

                <div class="flex justify-between space-x-2">
                    <div class="min-w-full">
                        <input type="text" name="title" id="title" placeholder="{{ __('Title') }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                    focus:border-indigo-300 focus: ring focus:ring-indigo-200"
                               value="{{ old('title') }}">

                        @error('title') {{ $message }} @enderror
                    </div>

                    <x-button type="submit">{{ __('Create') }}</x-button>
                </div>
            </form>
        </div>
    </div>
