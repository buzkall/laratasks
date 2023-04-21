<x-mail::message>
# New task created

with title {{ $task->title }}

<x-mail::button :url="$url">
    View Task
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
