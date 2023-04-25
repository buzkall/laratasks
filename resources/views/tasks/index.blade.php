<x-layout>
    <x-slot name="title">{{ __('Tasks') }}</x-slot>

    @if (Route::has('login'))
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
            @auth
                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-gray-200 dark:text-gray-400 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-200 dark:text-gray-400 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-200 dark:text-gray-400 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                @endif
            @endauth
        </div>
    @endif

    <div class="container mx-auto p-8">
        <div class="flex items-center">
            <div class="flex-auto">
                <h1 class="text-xl text-gray-500">{{ __('Tasks') }}</h1>

                <div class="flex-none mt-4">
                    @if(session('message'))
                        <div class="inline-block min-w-full py-2 px-8 border-indigo-300
                        bg-indigo-200 rounded-xl">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div x-data>
                        <template x-for="item in $store.notifications?.items">
                            <div class="inline-block min-w-full py-2 px-8 border-indigo-300
                        bg-indigo-200 rounded-xl">
                                <p x-text="item"></p>
                            </div>
                        </template>
                    </div>

                    @include('tasks.create')

                    <div class="inline-block min-w-full py-2 px-8 align-middle">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th class="py-2 px-6 text-left">{{ __('Id') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Title') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('User') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Tags') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Completed at') }}</th>
                                <th class="py-2 px-6 text-left">{{ __('Actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tasks as $task)
                                <tr>
                                    <td class="py-2">
                                        {{ $task->id }}
                                    </td>
                                    <td class="py-2">{{ $task->title }}</td>
                                    <td class="py-2">{{ $task->user->name }}</td>
                                    <td class="py-2">{{ $task->tags->pluck('name')->implode(', ') }}</td>
                                    <td class="py-2">
                                        {{ $task->completed_at?->diffForHumans() }}
                                    </td>
                                    <td class="flex justify-end space-x-1">
                                        @includeWhen(is_null($task->completed_at), 'tasks.complete')

                                        <x-link :target="route('tasks.edit', $task)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                 stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                            </svg>
                                        </x-link>

                                        @include('tasks.delete')
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {{ $tasks->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
