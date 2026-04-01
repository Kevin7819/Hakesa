<?php

use App\Models\AdminUser;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Admin Comment Moderation', function () {
    beforeEach(function () {
        $this->admin = AdminUser::factory()->create();
        $this->user = User::factory()->create();
    });

    it('can list comments', function () {
        Comment::factory()->count(2)->create(['user_id' => $this->user->id]);
        Comment::factory()->pending()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'admin')->get('/admin/comments');
        $response->assertStatus(200);
    });

    it('can approve a pending comment', function () {
        $comment = Comment::factory()->pending()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch("/admin/comments/{$comment->id}/approve");

        $response->assertRedirect('/admin/comments');
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => 'aprobado',
        ]);
    });

    it('can reject a pending comment', function () {
        $comment = Comment::factory()->pending()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->patch("/admin/comments/{$comment->id}/reject");

        $response->assertRedirect('/admin/comments');
        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => 'rechazado',
        ]);
    });

    it('can delete a comment', function () {
        $comment = Comment::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->admin, 'admin')
            ->delete("/admin/comments/{$comment->id}");

        $response->assertRedirect('/admin/comments');
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    });

    it('can approve a rejected comment', function () {
        $comment = Comment::factory()->rejected()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->admin, 'admin')
            ->patch("/admin/comments/{$comment->id}/approve");

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'status' => 'aprobado',
        ]);
    });

    it('guest cannot access comment moderation', function () {
        $response = $this->get('/admin/comments');
        $response->assertRedirect('/admin/login');
    });
});
