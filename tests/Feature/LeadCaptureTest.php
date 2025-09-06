<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LeadCaptureTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_can_be_stored_with_blank_email(): void
    {
        Storage::fake('local');

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
        Storage::fake('local');

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

    public function test_lead_is_written_to_json_file(): void
    {
        Storage::fake('local');

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

        $this->postJson('/lead', [
            'slug' => $link->slug,
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ])->assertOk();

        Storage::disk('local')->assertExists('leads.json');
        $data = json_decode(Storage::disk('local')->get('leads.json'), true);
        $this->assertEquals('John Doe', $data[0]['name']);
    }

    public function test_dynamic_fields_are_stored_as_json(): void
    {
        Storage::fake('local');

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

        $this->postJson('/lead', [
            'slug' => $link->slug,
            'company' => 'Acme',
            'phone' => '1234567890',
        ])->assertOk();

        $this->assertDatabaseHas('leads', [
            'document_id' => $document->id,
            'data->company' => 'Acme',
            'data->phone' => '1234567890',
        ]);
    }
}
