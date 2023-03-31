<x-layout>
    <x-slot name="title">{{ __('Tasks') }}</x-slot>

    <div class="container mx-auto p-8">
        <div class="flex items-center">
            <div class="flex-auto">
                <h1 class="text-xl text-gray-500">
                    {{ __('Task with id :id', ['id' => $task->id]) }}
                </h1>

                <div class="flex-none mt-4">
                    <div class="inline-block min-w-full py-2 px-8 align-middle">
                        {{ $task->title }}
                        {{ $task->completed_at?->diffForHumans() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
