<?php

use App\Models\Announcement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Test setup
});

describe('Announcement Model', function () {
    describe('scopes', function () {
        it('returns only active announcements', function () {
            $active = Announcement::factory()->create(['is_active' => true]);
            $inactive = Announcement::factory()->create(['is_active' => false]);

            $visible = Announcement::visible()->get();

            expect($visible)->toHaveCount(1);
            expect($visible->first()->id)->toBe($active->id);
        });

        it('excludes expired announcements', function () {
            $valid = Announcement::factory()->create([
                'is_active' => true,
                'expires_at' => Carbon::now()->addWeek(),
            ]);
            $expired = Announcement::factory()->create([
                'is_active' => true,
                'expires_at' => Carbon::now()->subDay(),
            ]);

            $visible = Announcement::visible()->get();

            expect($visible)->toHaveCount(1);
            expect($visible->first()->id)->toBe($valid->id);
        });

        it('includes announcements without expiration', function () {
            $announcement = Announcement::factory()->create([
                'is_active' => true,
                'expires_at' => null,
            ]);

            $visible = Announcement::visible()->get();

            expect($visible)->toHaveCount(1);
            expect($visible->first()->id)->toBe($announcement->id);
        });

        it('orders by newest first', function () {
            $old = Announcement::factory()->create(['created_at' => Carbon::now()->subDay()]);
            $new = Announcement::factory()->create(['created_at' => Carbon::now()]);

            $all = Announcement::latest()->get();

            expect($all->first()->id)->toBe($new->id);
            expect($all->last()->id)->toBe($old->id);
        });
    });

    describe('isExpired', function () {
        it('returns false when no expires_at', function () {
            $announcement = Announcement::factory()->create(['expires_at' => null]);

            expect($announcement->isExpired())->toBeFalse();
        });

        it('returns false when expires_at is in the future', function () {
            $announcement = Announcement::factory()->create([
                'expires_at' => Carbon::now()->addWeek(),
            ]);

            expect($announcement->isExpired())->toBeFalse();
        });

        it('returns true when expires_at is in the past', function () {
            $announcement = Announcement::factory()->create([
                'expires_at' => Carbon::now()->subDay(),
            ]);

            expect($announcement->isExpired())->toBeTrue();
        });
    });

    describe('casts', function () {
        it('casts is_active as boolean', function () {
            $announcement = Announcement::factory()->create(['is_active' => true]);

            expect($announcement->is_active)->toBeTrue();
            expect(is_bool($announcement->is_active))->toBeTrue();
        });

        it('casts event_date as date', function () {
            $date = Carbon::now()->addWeek();
            $announcement = Announcement::factory()->create(['event_date' => $date]);

            expect($announcement->event_date->toDateString())->toBe($date->toDateString());
        });

        it('casts expires_at as datetime', function () {
            $date = Carbon::now()->addWeek();
            $announcement = Announcement::factory()->create(['expires_at' => $date]);

            expect($announcement->expires_at->toDateString())->toBe($date->toDateString());
        });
    });

    describe('getImageUrlAttribute', function () {
        it('returns null when no image', function () {
            $announcement = Announcement::factory()->create(['image' => null]);

            expect($announcement->image_url)->toBeNull();
        });

        it('returns asset URL when image exists', function () {
            $announcement = Announcement::factory()->create(['image' => 'announcements/test.jpg']);

            expect($announcement->image_url)->toBe(asset('storage/announcements/test.jpg'));
        });
    });

    describe('factories', function () {
        it('creates inactive announcement', function () {
            $announcement = Announcement::factory()->inactive()->create();

            expect($announcement->is_active)->toBeFalse();
        });

        it('creates expired announcement', function () {
            $announcement = Announcement::factory()->expired()->create();

            expect($announcement->isExpired())->toBeTrue();
        });

        it('creates announcement with image', function () {
            $announcement = Announcement::factory()->withImage()->create();

            expect($announcement->image)->not->toBeNull();
            expect($announcement->image)->toContain('announcements/');
        });
    });
});