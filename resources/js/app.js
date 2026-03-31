import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);

// ── Global Stores ──
document.addEventListener('alpine:init', () => {

    // Cart counter store
    Alpine.store('cart', {
        count: window.__cartCount || 0,
        update(count) {
            this.count = count;
        },
        increment() {
            this.count++;
        }
    });

    // Toast notification store
    Alpine.store('toasts', {
        items: [],
        show(message, type = 'success', duration = 3000) {
            const id = Date.now() + Math.random();
            this.items.push({ id, message, type });
            setTimeout(() => {
                this.items = this.items.filter(t => t.id !== id);
            }, duration);
        },
        success(msg) { this.show(msg, 'success'); },
        error(msg) { this.show(msg, 'error'); },
    });

    // ── Hakesa: Carrusel manual ──
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

    // ── Catalog filters (AJAX) ──
    Alpine.data('catalogFilters', () => ({
        loading: false,
        _debounceTimer: null,
        async submit() {
            if (this.loading) return; // Guard against double-fire
            this.loading = true;
            clearTimeout(this._debounceTimer);
            const form = this.$refs.filtrosForm;
            const params = new URLSearchParams(new FormData(form)).toString();
            try {
                const res = await fetch(`/productos?${params}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                document.getElementById('products-grid').innerHTML = data.html;
                document.getElementById('products-pagination').innerHTML = data.pagination;
                document.getElementById('results-info').innerHTML = data.results_info;
                history.pushState(null, '', `/productos?${params}`);
            } catch (e) {
                Alpine.store('toasts').error('Error al filtrar productos');
            }
            this.loading = false;
        },
        debounceSubmit() {
            clearTimeout(this._debounceTimer);
            this._debounceTimer = setTimeout(() => this.submit(), 500);
        },
        clearFilters() {
            clearTimeout(this._debounceTimer);
            this.$refs.filtrosForm.reset();
            this.submit();
        }
    }));

    // ── Add to cart (AJAX) ──
    Alpine.data('addToCart', (url) => ({
        loading: false,
        async submit(e) {
            e.preventDefault();
            this.loading = true;
            const form = this.$el;
            const formData = new FormData(form);
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });
                const data = await res.json();
                if (res.ok) {
                    Alpine.store('cart').update(data.cart_count);
                    Alpine.store('toasts').success(data.message);
                    // Bounce cart icon
                    window.dispatchEvent(new CustomEvent('cart-bounce'));
                    form.reset();
                    const qty = form.querySelector('[name="quantity"]');
                    if (qty) qty.value = 1;
                } else {
                    Alpine.store('toasts').error(data.message || 'Error al agregar');
                }
            } catch (e) {
                Alpine.store('toasts').error('Error de conexión');
            }
            this.loading = false;
        }
    }));

    // ── Comment form (AJAX) ──
    Alpine.data('commentForm', (url) => ({
        loading: false,
        content: '',
        error: '',
        async submit() {
            this.loading = true;
            this.error = '';
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ content: this.content }),
                });
                const data = await res.json();
                if (res.ok) {
                    Alpine.store('toasts').success(data.message);
                    this.content = '';
                    // Add pending comment to the list
                    const list = document.getElementById('comments-list');
                    const empty = document.getElementById('comments-empty');
                    if (empty) empty.remove();
                    const userName = data.comment.user_name;
                    const initials = userName.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
                    const card = document.createElement('div');
                    card.className = 'card-hakesa p-8 animate-fade-in-up';

                    const pContent = document.createElement('p');
                    pContent.className = 'text-gray-600 mb-6 italic';
                    pContent.textContent = `"${data.comment.content}"`;

                    const avatar = document.createElement('div');
                    avatar.className = 'w-12 h-12 bg-hakesa-pink/20 rounded-full flex items-center justify-center text-hakesa-pink font-bold';
                    avatar.textContent = initials;

                    const pName = document.createElement('p');
                    pName.className = 'font-semibold';
                    pName.textContent = userName;

                    const pStatus = document.createElement('p');
                    pStatus.className = 'text-teal-700 text-xs font-medium flex items-center gap-1';
                    pStatus.innerHTML = '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>Pendiente de aprobación';

                    const infoDiv = document.createElement('div');
                    infoDiv.append(pName, pStatus);

                    const flexDiv = document.createElement('div');
                    flexDiv.className = 'flex items-center gap-3';
                    flexDiv.append(avatar, infoDiv);

                    card.append(pContent, flexDiv);
                    list.prepend(card);
                } else {
                    this.error = data.errors?.content?.[0] || data.message || 'Error al enviar';
                    Alpine.store('toasts').error(this.error);
                }
            } catch (e) {
                this.error = 'Error de conexión';
                Alpine.store('toasts').error(this.error);
            }
            this.loading = false;
        }
    }));

    // ── Cart page actions (AJAX) ──
    Alpine.data('cartItem', (updateUrl, removeUrl) => ({
        updating: false,
        removing: false,
        _debounceTimer: null,
        autoUpdate(e) {
            clearTimeout(this._debounceTimer);
            this._debounceTimer = setTimeout(() => {
                this.doUpdate();
            }, 600);
        },
        async doUpdate() {
            if (this.updating) return;
            this.updating = true;
            const form = this.$el.closest('form') || this.$el.querySelector('form');
            const qtyInput = form.querySelector('[name="quantity"]');
            const qty = qtyInput.value;
            if (qty < 1) { qtyInput.value = 1; this.updating = false; return; }
            try {
                const res = await fetch(updateUrl, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ quantity: qty }),
                });
                const data = await res.json();
                if (res.ok) {
                    Alpine.store('cart').update(data.cart_count);
                    // Update subtotal inline
                    const subtotalEl = this.$el.closest('[data-cart-item]').querySelector('[data-subtotal]');
                    if (subtotalEl) subtotalEl.textContent = data.item_subtotal;
                    // Update summary
                    const totalEl = document.getElementById('cart-total');
                    if (totalEl) totalEl.textContent = data.cart_total;
                    const countEl = document.getElementById('cart-item-count');
                    if (countEl) countEl.textContent = `${data.cart_count} producto${data.cart_count !== 1 ? 's' : ''}`;
                } else {
                    Alpine.store('toasts').error(data.message || 'Error');
                }
            } catch (e) {
                Alpine.store('toasts').error('Error de conexión');
            }
            this.updating = false;
        },
        async remove() {
            this.removing = true;
            try {
                const res = await fetch(removeUrl, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (res.ok) {
                    Alpine.store('cart').update(data.cart_count);
                    Alpine.store('toasts').success(data.message);
                    const item = this.$el.closest('[data-cart-item]');
                    item.style.transition = 'opacity 0.3s, max-height 0.3s, padding 0.3s';
                    item.style.opacity = '0';
                    item.style.maxHeight = '0';
                    item.style.overflow = 'hidden';
                    item.style.paddingTop = '0';
                    item.style.paddingBottom = '0';
                    setTimeout(() => {
                        item.remove();
                        if (data.cart_count === 0) window.location.reload();
                    }, 300);
                    // Update summary
                    const totalEl = document.getElementById('cart-total');
                    if (totalEl) totalEl.textContent = data.cart_total;
                    const countEl = document.getElementById('cart-item-count');
                    if (countEl) countEl.textContent = `${data.cart_count} producto${data.cart_count !== 1 ? 's' : ''}`;
                } else {
                    Alpine.store('toasts').error(data.message || 'Error');
                }
            } catch (e) {
                Alpine.store('toasts').error('Error de conexión');
            }
            this.removing = false;
        }
    }));

    Alpine.data('cartClear', (url) => ({
        loading: false,
        async clear() {
            this.loading = true;
            try {
                const res = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();
                if (res.ok) {
                    Alpine.store('cart').update(0);
                    Alpine.store('toasts').success(data.message);
                    window.location.reload();
                } else {
                    Alpine.store('toasts').error(data.message || 'Error');
                }
            } catch (e) {
                Alpine.store('toasts').error('Error de conexión');
            }
            this.loading = false;
        }
    }));
});

Alpine.start();
