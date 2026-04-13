{{-- Shared filter fields — used by both desktop sidebar and mobile drawer --}}
<form x-ref="filtrosForm" method="GET" action="{{ route('catalog.index') }}" id="filterForm" class="space-y-4">
    <!-- Search (only in mobile drawer, desktop has inline search bar) -->
    <div class="lg:hidden mb-4">
        <label class="block text-xs font-medium text-gray-400 mb-1">Buscar</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..."
            @input="debounceSubmit()"
            class="w-full px-4 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white">
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

    <!-- Price Range -->
    <div>
        <label class="block text-xs font-medium text-gray-400 mb-1">Precio (₡)</label>
        <div class="flex gap-2">
            <input type="number" name="price_min" value="{{ request('price_min') }}" placeholder="Mín"
                @change="submit()"
                class="w-1/2 px-3 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white">
            <input type="number" name="price_max" value="{{ request('price_max') }}" placeholder="Máx"
                @change="submit()"
                class="w-1/2 px-3 py-2.5 border border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-gracia-primary text-sm bg-gray-700 text-white">
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
