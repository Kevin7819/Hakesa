{{-- Shared filter fields — used by both desktop sidebar and mobile drawer --}}
<form x-ref="filtrosForm" method="GET" action="{{ route('catalog.index') }}" id="filterForm" class="space-y-4">
    <!-- Search (now inside panel for both desktop and mobile) -->
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Buscar</label>
        <div class="relative">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..."
                @input="debounceSubmit()"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white placeholder-gray-400">
        </div>
    </div>

    <!-- Category -->
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Categoría</label>
        <select name="category" @change="submit()" class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white">
            <option value="">Todas</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Price Range with Dual Slider -->
    <div x-data="priceRangeSlider(
        {{ request('price_min', 0) }},
        {{ request('price_max', $maxPrice) }},
        0,
        {{ $maxPrice }}
    )">
        <label class="block text-xs font-medium text-gray-400 mb-1">Precio (₡)</label>

        <!-- Hidden inputs for form submission -->
        <input type="hidden" name="price_min" :value="minPrice">
        <input type="hidden" name="price_max" :value="maxPrice">

        <!-- Price display -->
        <div class="flex justify-between items-center mb-3">
            <span class="text-sm font-semibold text-gracia-primary">₡<span x-text="minPrice.toLocaleString()"></span></span>
            <span class="text-sm font-semibold text-gracia-primary">₡<span x-text="maxPrice.toLocaleString()"></span></span>
        </div>

        <!-- Dual range slider container -->
        <div class="relative h-8 w-full select-none">
            <!-- Track background -->
            <div class="absolute top-1/2 -translate-y-1/2 w-full h-2 bg-gray-600 rounded-full"></div>

            <!-- Highlighted track between handles -->
            <div class="absolute top-1/2 -translate-y-1/2 h-2 rounded-full bg-gradient-to-r from-gracia-primary to-gracia-secondary"
                 :style="`left: ${(minPrice / maxRange) * 100}%; width: ${((maxPrice - minPrice) / maxRange) * 100}%`">
            </div>

            <!-- Visual thumb for min -->
            <div class="absolute top-1/2 -translate-y-1/2 w-5 h-5 bg-white rounded-full border-2 border-gracia-primary shadow-lg pointer-events-none"
                 :style="`left: calc(${(minPrice / maxRange) * 100}% - 10px)`">
            </div>

            <!-- Visual thumb for max -->
            <div class="absolute top-1/2 -translate-y-1/2 w-5 h-5 bg-white rounded-full border-2 border-gracia-secondary shadow-lg pointer-events-none"
                 :style="`left: calc(${(maxPrice / maxRange) * 100}% - 10px)`">
            </div>

            <!-- Range input for min -->
            <input type="range" :min="minRange" :max="maxRange" step="500"
                   x-model.number="minPrice"
                   @input="onMinInput($event.target.value)"
                   class="absolute top-0 left-0 w-full h-8 opacity-0 cursor-pointer"
                   :class="minThumbActive ? 'z-30' : 'z-10'"
                   @mousedown="minThumbActive = true"
                   @mouseup="minThumbActive = false; submit()">

            <!-- Range input for max -->
            <input type="range" :min="minRange" :max="maxRange" step="500"
                   x-model.number="maxPrice"
                   @input="onMaxInput($event.target.value)"
                   class="absolute top-0 left-0 w-full h-8 opacity-0 cursor-pointer"
                   :class="maxThumbActive ? 'z-30' : 'z-20'"
                   @mousedown="maxThumbActive = true"
                   @mouseup="maxThumbActive = false; submit()">
        </div>
    </div>

    <!-- Sort -->
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Ordenar</label>
        <select name="sort" @change="submit()" class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white">
            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Más recientes</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nombre A-Z</option>
        </select>
    </div>

    <!-- Actions -->
    <div>
        <button type="button" @click="clearFilters()" class="w-full px-4 py-2.5 border border-gray-600 rounded-xl text-gray-400 hover:text-gray-300 hover:border-gray-500 text-sm font-medium transition-colors">
            Limpiar filtros
        </button>
    </div>

    <!-- Loading indicator -->
    <div x-show="loading" x-cloak class="flex items-center gap-2 text-sm text-gray-400">
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        Filtrando...
    </div>

    <!-- Error indicator -->
    <div x-show="error" x-text="error" x-cloak class="bg-red-500/10 text-red-400 text-sm p-3 rounded-xl flex items-center gap-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
    </div>
</form>
