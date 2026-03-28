import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ── Hakesa: Carrusel manual ──
document.addEventListener('alpine:init', () => {
    Alpine.data('carousel', () => ({
        current: 0,
        total: 0,
        init() {
            this.total = this.$el.querySelectorAll('.carousel-slide').length;
        },
        next() {
            this.current = (this.current + 1) % this.total;
        },
        prev() {
            this.current = (this.current - 1 + this.total) % this.total;
        },
        goTo(index) {
            this.current = index;
        },
    }));
});

Alpine.start();
