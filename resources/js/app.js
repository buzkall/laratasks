import './bootstrap';

import Alpine from 'alpinejs';

Alpine.store('notifications', {
    items: [],
    notify(message) {
        this.items.push(message)
    },
    clear() {
        this.items = []
    }
})


window.Alpine = Alpine;

Alpine.start();
