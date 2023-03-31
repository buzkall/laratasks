<x-layout>
    <x-slot name="title">{{ __('Tasks') }}</x-slot>

    <div class="container mx-auto p-8">
        <div class="flex items-center">
            <div class="flex-auto">
                <h1 class="text-xl text-gray-500">{{ __('Tasks') }}</h1>

                <div class="flex-none mt-4">
                    <div class="inline-block min-w-full py-2 px-8 align-middle">
                        <table class="divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th class="py-2 px-6 text-left">{{ __('Id') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Title') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Completed at') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td class="py-2">
                                        <a href="{{ route('tasks.show', $task) }}">
                                            {{ $task->id }}
                                        </a>
                                    </td>
                                    <td class="py-2">{{ $task->title }}</td>
                                    <td class="py-2">
                                        {{ $task->completed_at?->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
