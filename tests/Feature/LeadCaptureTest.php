<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class LeadCaptureTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_can_be_stored_with_blank_email(): void
    {
        // Create a user, document and link for the lead capture.
        $user = User::factory()->create();
        $document = Document::create([
            'user_id' => $user->id,
            'filename' => 'test.pdf',
            'filepath' => 'test.pdf',
            'size' => 100,
        ]);
        $link = Link::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'slug' => Str::random(10),
            'permissions' => json_encode([]),
        ]);

        $response = $this->postJson('/lead', [
            'slug' => $link->slug,
            'name' => 'John Doe',
            'email' => '',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('leads', [
            'document_id' => $document->id,
            'name' => 'John Doe',
            'email' => null,
        ]);
    }

    public function test_lead_can_be_stored_without_name(): void
    {
        $user = User::factory()->create();
        $document = Document::create([
            'user_id' => $user->id,
            'filename' => 'test.pdf',
            'filepath' => 'test.pdf',
            'size' => 100,
        ]);
        $link = Link::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'slug' => Str::random(10),
            'permissions' => json_encode([]),
        ]);

        $response = $this->postJson('/lead', [
            'slug' => $link->slug,
            'name' => '',
            'email' => 'john@example.com',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('leads', [
            'document_id' => $document->id,
            'name' => '',
            'email' => 'john@example.com',
        ]);
    }
}
