<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Comments', function () {
    beforeEach(function () {
        $this->user = User::factory()->create();
    });

    it('can display the welcome page with comments section', function () {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('testimonios');
    });

    it('shows login prompt when guest visits welcome page', function () {
        $response = $this->get('/');
        $response->assertSee('Inicia sesión para comentar');
    });

    it('shows comment form when user is authenticated', function () {
        $response = $this->actingAs($this->user)->get('/');
        $response->assertSee('Deja tu comentario');
    });

    it('authenticated user can submit a comment via JSON', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/comentarios', ['content' => 'Excelente servicio, muy recomendado']);

        $response->assertStatus(200);
        $response->assertJsonFragment(['message' => 'Tu comentario está pendiente de aprobación.']);
        $response->assertJsonFragment(['content' => 'Excelente servicio, muy recomendado']);

        $this->assertDatabaseHas('comments', [
            'user_id' => $this->user->id,
            'content' => 'Excelente servicio, muy recomendado',
            'status' => 'pendiente',
        ]);
    });

    it('comment is created with pending status', function () {
        $this->actingAs($this->user)
            ->postJson('/comentarios', ['content' => 'Mi comentario de prueba']);

        $comment = Comment::where('user_id', $this->user->id)->first();
        expect($comment->status)->toBe('pendiente');
        expect($comment->isPending())->toBeTrue();
        expect($comment->isApproved())->toBeFalse();
    });

    it('guest cannot submit a comment', function () {
        $response = $this->post('/comentarios', ['content' => 'Intento sin login']);
        $response->assertRedirect('/login');
    });

    it('comment cannot be empty', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/comentarios', ['content' => '']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    });

    it('comment cannot exceed 1000 characters', function () {
        $longContent = str_repeat('a', 1001);

        $response = $this->actingAs($this->user)
            ->postJson('/comentarios', ['content' => $longContent]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    });

    it('comment must have at least 2 characters', function () {
        $response = $this->actingAs($this->user)
            ->postJson('/comentarios', ['content' => 'a']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['content']);
    });

    it('approved comments show on welcome page', function () {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'content' => 'Comentario aprobado de prueba',
            'status' => 'aprobado',
        ]);

        $response = $this->get('/');
        $response->assertSee('Comentario aprobado de prueba');
    });

    it('pending comments do not show on welcome page', function () {
        Comment::factory()->create([
            'user_id' => $this->user->id,
            'content' => 'Comentario pendiente secreto',
            'status' => 'pendiente',
        ]);

        $response = $this->get('/');
        $response->assertDontSee('Comentario pendiente secreto');
    });
});
