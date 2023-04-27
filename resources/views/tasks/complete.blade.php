<div x-data="{
    incomplete: true,

    complete() {
        this.incomplete = false;
        axios.put('{{ route('api.tasks.complete', $task) }}',
                    {},
                    { headers: { Authorization: `Bearer {{ Cookie::get('auth-token') }}` }}
        )
        .then(function(response) {
            $store.notifications?.notify(response.data.message);

            setInterval(() => { $store.notifications?.clear() }, 3000);

        }).catch(function(error) {
            console.log(error);
        });
    }
}">

    <x-button class="!bg-amber-500 !hover:bg-amber-400" @click="complete" x-show="incomplete">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
             stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
    </x-button>
</div>
