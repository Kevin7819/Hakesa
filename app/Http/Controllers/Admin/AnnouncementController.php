<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Controlador para anuncios y eventos
 *
 * Métodos: index, create, store, edit, update, destroy, toggle
 */
class AnnouncementController extends Controller
{
    /**
     * Listar todos los anuncios (paginado)
     */
    public function index(): View
    {
        $announcements = Announcement::latest()->paginate(10);

        return view('admin.announcements.index', compact('announcements'));
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): View
    {
        return view('admin.announcements.create');
    }

    /**
     * Guardar nuevo anuncio
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:60'],
            'description' => ['required', 'string', 'max:120'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'expires_at' => ['nullable', 'date', 'after:event_date'],
        ]);

        // Guardar imagen si existe
        if ($request->hasFile('image')) {
            $path = $this->storeAnnouncementImage($request->file('image'));
            $validated['image'] = $path;
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Anuncio creado exitosamente.');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    /**
     * Actualizar anuncio existente
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:60'],
            'description' => ['required', 'string', 'max:120'],
            'event_date' => ['required', 'date'],
            'location' => ['nullable', 'string', 'max:100'],
            'link' => ['nullable', 'url'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'is_active' => ['boolean'],
            'expires_at' => ['nullable', 'date'],
        ]);

        // Eliminar imagen si está marcado el checkbox
        if ($request->boolean('remove_image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            $validated['image'] = null;
        }
        // Nueva imagen
        elseif ($request->hasFile('image')) {
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }

            $path = $this->storeAnnouncementImage($request->file('image'));
            $validated['image'] = $path;
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Anuncio actualizado exitosamente.');
    }

    /**
     * Eliminar anuncio
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        // Eliminar imagen asociada
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }

        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Anuncio eliminado exitosamente.');
    }

    /**
     * Activar/desactivar anuncio (AJAX)
     */
    public function toggle(Announcement $announcement): RedirectResponse
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        $status = $announcement->is_active ? 'activado' : 'desactivado';

        return back()->with('success', "Anuncio {$status} exitosamente.");
    }

    /**
     * Guardar imagen de anuncio en storage
     * Convierte a WebP y redimensiona si es necesario
     */
    private function storeAnnouncementImage($file): string
    {
        // Validación de seguridad
        $allowedMimes = ['jpeg', 'png', 'jpg', 'gif', 'webp'];
        $extension = strtolower($file->extension());

        if (! in_array($extension, $allowedMimes)) {
            abort(422, 'Tipo de archivo no permitido.');
        }

        $realMime = $file->getMimeType();
        $allowedMimetypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

        if (! in_array($realMime, $allowedMimetypes)) {
            abort(422, 'El archivo no es una imagen válida.');
        }

        // Validar tamaño máximo (5MB)
        if ($file->getSize() > 5 * 1024 * 1024) {
            abort(422, 'La imagen no puede exceder 5MB.');
        }

        // Generar nombre seguro
        $uniqueId = Str::uuid();
        $filename = "announcement-{$uniqueId}.webp";

        // Procesar imagen
        $manager = new ImageManager(new Driver);
        $image = $manager->decode($file);

        // Redimensionar si es más grande que 1200px
        $image->scaleDown(width: 1200);

        // Guardar como WebP con 85% calidad
        $storagePath = Storage::disk('public')->path('announcements');
        if (! is_dir($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $image->save(Storage::disk('public')->path("announcements/{$filename}"), quality: 85);

        return "announcements/{$filename}";
    }
}
