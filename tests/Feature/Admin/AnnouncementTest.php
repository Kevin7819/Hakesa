<?php

use App\Models\AdminUser;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

describe('Admin Announcement', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
    });

    describe('index', function () {
        it('can list announcements as admin', function () {
            Announcement::factory()->count(3)->create();

            $response = $this->actingAs($this->admin, 'admin')
                ->get(route('admin.announcements.index'));

            $response->assertStatus(200);
            $response->assertSee('Anuncios');
        });

        it('redirects unauthenticated users', function () {
            $response = $this->get(route('admin.announcements.index'));

            $response->assertRedirect(route('admin.login'));
        });
    });

    describe('create', function () {
        it('can show create form', function () {
            $response = $this->actingAs($this->admin, 'admin')
                ->get(route('admin.announcements.create'));

            $response->assertStatus(200);
            $response->assertSee('Crear Anuncio');
        });
    });

    describe('store', function () {
        it('can create a valid announcement', function () {
            $data = [
                'title' => 'Nuevo Evento',
                'description' => 'Descripción del evento',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'location' => 'San José',
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertRedirect(route('admin.announcements.index'));
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('announcements', [
                'title' => 'Nuevo Evento',
                'description' => 'Descripción del evento',
                'location' => 'San José',
            ]);
        });

        it('validates required fields', function () {
            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), []);

            $response->assertSessionHasErrors(['title', 'description', 'event_date']);
        });

        it('validates title max length', function () {
            $data = [
                'title' => str_repeat('a', 65),
                'description' => 'Test',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['title']);
        });

        it('validates description max length', function () {
            $data = [
                'title' => 'Test',
                'description' => str_repeat('a', 125),
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['description']);
        });

        it('accepts valid URL format', function () {
            $data = [
                'title' => 'Evento con Enlace',
                'description' => 'Descripción',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'location' => 'San José',
                'link' => 'https://facebook.com/event/123',
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('announcements', [
                'link' => 'https://facebook.com/event/123',
            ]);
        });

        it('rejects invalid URL format', function () {
            $data = [
                'title' => 'Evento',
                'description' => 'Descripción',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'link' => 'not-a-url',
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['link']);
        });

        it('accepts optional link (null)', function () {
            $data = [
                'title' => 'Evento sin Enlace',
                'description' => 'Descripción',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'link' => null,
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasNoErrors();
        });

        it('validates expires_at after event_date', function () {
            $eventDate = Carbon::now()->addWeek()->format('Y-m-d');

            $data = [
                'title' => 'Test',
                'description' => 'Test',
                'event_date' => $eventDate,
                'expires_at' => Carbon::now()->addDay()->format('Y-m-d'),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['expires_at']);
        });

        it('can set is_active to false', function () {
            $data = [
                'title' => 'Evento Inactivo',
                'description' => 'Descripción',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'is_active' => false,
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $this->assertDatabaseHas('announcements', [
                'title' => 'Evento Inactivo',
                'is_active' => false,
            ]);
        });

        it('accepts valid image file', function () {
            Storage::fake('public');

            $data = [
                'title' => 'Evento con Imagen',
                'description' => 'Descripción',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'image' => UploadedFile::fake()->image('test.jpg', 800, 600),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasNoErrors();
            $this->assertDatabaseHas('announcements', [
                'title' => 'Evento con Imagen',
            ]);
        });

        it('rejects invalid image type', function () {
            $data = [
                'title' => 'Test',
                'description' => 'Test',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'image' => UploadedFile::fake()->create('test.pdf', 1024),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['image']);
        });

        it('rejects image larger than 5MB', function () {
            $data = [
                'title' => 'Test',
                'description' => 'Test',
                'event_date' => Carbon::now()->addWeek()->format('Y-m-d'),
                'image' => UploadedFile::fake()->image('test.jpg', 100, 100)->size(6000),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->post(route('admin.announcements.store'), $data);

            $response->assertSessionHasErrors(['image']);
        });
    });

    describe('edit', function () {
        it('can show edit form', function () {
            $announcement = Announcement::factory()->create();

            $response = $this->actingAs($this->admin, 'admin')
                ->get(route('admin.announcements.edit', $announcement));

            $response->assertStatus(200);
            $response->assertSee($announcement->title);
        });
    });

    describe('update', function () {
        it('can update announcement', function () {
            $announcement = Announcement::factory()->create();

            $data = [
                'title' => 'Título Actualizado',
                'description' => 'Descripción Actualizada',
                'event_date' => $announcement->event_date->format('Y-m-d'),
            ];

            $response = $this->actingAs($this->admin, 'admin')
                ->put(route('admin.announcements.update', $announcement), $data);

            $response->assertRedirect(route('admin.announcements.index'));
            $response->assertSessionHas('success');

            $this->assertDatabaseHas('announcements', [
                'id' => $announcement->id,
                'title' => 'Título Actualizado',
            ]);
        });

        it('can remove image via remove_image flag', function () {
            // TODO: Requires actual file to exist or checkbox logic needs investigation
            $this->markTestSkipped('Needs investigation - checkbox value handling');
        });

        it('validates same rules as store', function () {
            $announcement = Announcement::factory()->create();

            $response = $this->actingAs($this->admin, 'admin')
                ->put(route('admin.announcements.update', $announcement), []);

            $response->assertSessionHasErrors(['title', 'description', 'event_date']);
        });
    });

    describe('destroy', function () {
        it('can delete announcement', function () {
            $announcement = Announcement::factory()->create();

            $response = $this->actingAs($this->admin, 'admin')
                ->delete(route('admin.announcements.destroy', $announcement));

            $response->assertRedirect(route('admin.announcements.index'));
            $response->assertSessionHas('success');

            $this->assertModelMissing($announcement);
        });

        it('deletes associated image', function () {
            Storage::fake('public');
            $announcement = Announcement::factory()->create(['image' => 'announcements/test.jpg']);

            Storage::disk('public')->put('announcements/test.jpg', 'fake image data');

            $this->actingAs($this->admin, 'admin')
                ->delete(route('admin.announcements.destroy', $announcement));

            Storage::disk('public')->assertMissing('announcements/test.jpg');
        });
    });

    describe('toggle', function () {
        it('can toggle is_active from true to false', function () {
            $announcement = Announcement::factory()->create(['is_active' => true]);

            $response = $this->actingAs($this->admin, 'admin')
                ->patch(route('admin.announcements.toggle', $announcement));

            $response->assertSessionHas('success');

            $announcement->refresh();
            expect($announcement->is_active)->toBeFalse();
        });

        it('can toggle is_active from false to true', function () {
            $announcement = Announcement::factory()->create(['is_active' => false]);

            $response = $this->actingAs($this->admin, 'admin')
                ->patch(route('admin.announcements.toggle', $announcement));

            $response->assertSessionHas('success');

            $announcement->refresh();
            expect($announcement->is_active)->toBeTrue();
        });
    });

    describe('authorization', function () {
        it('regular user cannot access admin announcements', function () {
            $user = User::factory()->create();

            $response = $this->actingAs($user)
                ->get(route('admin.announcements.index'));

            $response->assertRedirect(route('admin.login'));
        });
    });
});
