<section>
    <header class="mb-6">
        <h2 class="text-lg font-bold text-gray-900">
            Eliminar Cuenta
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            Una vez que elimines tu cuenta, todos tus datos y recursos se borrarán de forma permanente. Antes de eliminarla, descarga cualquier información que desees conservar.
        </p>
    </header>

    <div x-data="{ modalOpen: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
        <x-danger-button
            x-on:click.prevent="modalOpen = true"
        >Eliminar Cuenta</x-danger-button>

        <!-- Modal: no se cierra al clickear fuera — acción peligrosa -->
        <div
            x-show="modalOpen"
            x-on:keydown.escape.window="modalOpen = false"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto"
            style="display: none;"
        >
            <!-- Backdrop oscuro -->
            <div
                x-show="modalOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/75"
            ></div>

            <!-- Panel del modal -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div
                    x-show="modalOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl overflow-hidden"
                    @click.outside="modalOpen = false"
                >
                    <!-- Header con X -->
                    <div class="flex items-center justify-between px-6 pt-6 pb-2">
                        <h2 class="text-lg font-bold text-gray-900">
                            ¿Eliminar tu cuenta?
                        </h2>
                        <button
                            type="button"
                            x-on:click="modalOpen = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                            aria-label="Cerrar modal"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <form method="post" action="{{ route('profile.destroy') }}" class="px-6 pb-6">
                        @csrf
                        @method('delete')

                        <p class="text-sm text-gray-500 mb-6">
                            Esta acción es <strong>permanente e irreversible</strong>. Todos tus datos, pedidos y configuración se eliminarán para siempre. Ingresa tu contraseña para confirmar.
                        </p>

                        <div>
                            <label for="delete-password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                            <input
                                id="delete-password"
                                name="password"
                                type="password"
                                required
                                autofocus
                                autocomplete="current-password"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-shadow"
                                placeholder="Tu contraseña actual"
                            />
                            @error('password', 'userDeletion')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Footer -->
                        <div class="mt-6 flex justify-end gap-3">
                            <button
                                type="button"
                                x-on:click="modalOpen = false"
                                class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl font-semibold text-sm text-gray-700 hover:bg-gray-50 transition-colors"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-5 py-2.5 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white hover:bg-red-500 active:bg-red-700 transition-colors"
                            >
                                Eliminar Cuenta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
