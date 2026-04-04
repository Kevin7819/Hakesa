<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">
            Eliminar Cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Una vez que elimines tu cuenta, todos tus datos y recursos se borrarán de forma permanente. Antes de eliminarla, descarga cualquier información que desees conservar.
        </p>
    </header>

    <div x-data="deleteAccountModal()">
        <x-danger-button x-on:click="open = true">Eliminar Cuenta</x-danger-button>

        <!-- Modal Overlay -->
        <template x-teleport="body">
            <div x-show="open" x-cloak class="relative z-50" @keydown.escape.window="open = false" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <!-- Backdrop -->
                <div
                    x-show="open"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-900/75 transition-opacity"
                ></div>

                <!-- Modal Panel -->
                <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div
                            x-show="open"
                            @click.outside="open = false"
                            x-transition:enter="ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave="ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <!-- Header -->
                            <div class="bg-white px-6 pt-6 pb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-900" id="modal-title">¿Eliminar tu cuenta?</h3>
                                    </div>
                                    <button
                                        type="button"
                                        x-on:click="open = false"
                                        class="text-gray-400 hover:text-gray-500 transition-colors rounded-lg p-1 hover:bg-gray-100"
                                        aria-label="Cerrar"
                                    >
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <form method="post" action="{{ route('profile.destroy') }}" class="px-6 pb-6">
                                @csrf
                                @method('delete')

                                <p class="text-sm text-gray-600 mb-5">
                                    Esta acción es <strong class="text-red-600">permanente e irreversible</strong>. Todos tus datos, pedidos y configuración se eliminarán para siempre.
                                </p>

                                <div>
                                    <label for="delete-password" class="block text-sm font-medium text-gray-700 mb-1">Confirmá tu contraseña</label>
                                    <input
                                        id="delete-password"
                                        name="password"
                                        type="password"
                                        required
                                        autocomplete="current-password"
                                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-shadow"
                                        placeholder="Tu contraseña actual"
                                    />
                                    @error('password', 'userDeletion')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Footer -->
                                <div class="mt-6 flex flex-row-reverse gap-3">
                                    <button
                                        type="submit"
                                        class="px-5 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-red-500 active:bg-red-700 transition-colors"
                                    >
                                        Sí, eliminar mi cuenta
                                    </button>
                                    <button
                                        type="button"
                                        x-on:click="open = false"
                                        class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                                    >
                                        Cancelar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</section>

<script>
function deleteAccountModal() {
    return {
        open: false,
        toggleOverflow(open) {
            document.body.style.overflow = open ? 'hidden' : '';
        },
    };
}
</script>
