<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_can_list_contacts()
    {
        $contacts = Contact::factory()->count(5)->create();

        $response = $this->getJson('http://localhost/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

    public function test_can_create_contact()
    {
        $data = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->postJson('http://localhost/api/contacts', $data);

        $response->assertStatus(201);
        $response->assertJsonFragment($data);
    }

    public function test_can_show_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson('http://localhost/api/contacts/' . $contact->id);

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'name' => $contact->name,
            'phone' => $contact->phone,
            'email' => $contact->email,
        ]);
    }

    public function test_can_update_contact()
    {
        $contact = Contact::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->putJson('http://localhost/api/contacts/' . $contact->id, $data);

        $response->assertStatus(200);
        $response->assertJsonFragment($data);
    }

    public function test_can_delete_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson('http://localhost/api/contacts/' . $contact->id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    public function it_validates_phone_format()
    {
        $response = $this->json('POST', 'http://localhost/api/contacts', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => 'invalid-phone',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    public function it_validates_email_format()
    {
        $response = $this->json('POST', 'http://localhost/api/contacts', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function it_requires_a_email()
    {
        $response = $this->json('POST', 'http://localhost/api/contacts', [
            'name' => 'John Doe',
            'phone' => '1234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function it_requires_a_phone()
    {
        $response = $this->json('POST', 'http://localhost/api/contacts', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('phone');
    }

    public function it_prevents_duplicate_emails()
    {
        $existingContact = Contact::factory()->create();

        $response = $this->json('POST', 'http://localhost/api/contacts', [
            'name' => 'Another Name',
            'email' => $existingContact->email,
            'phone' => '1234567890',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }
}
