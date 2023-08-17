<?php

namespace Tests\Feature;

use App\Jobs\ProcessContactCreation;
use App\Jobs\ProcessContactUpdate;
use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Repositories\ContactRepositoryInterface;


class ContactJobsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    public function test_process_contact_creation_job_works()
    {
        $data = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $repository = app(ContactRepositoryInterface::class);

        $job = new ProcessContactCreation($data);
        $job->handle($repository);

        $this->assertDatabaseHas('contacts', $data);
    }

    public function test_process_contact_update_job_works()
    {
        $contact = Contact::factory()->create();

        $updatedData = [
            'name' => $this->faker->name,
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
        ];

        $repository = app(ContactRepositoryInterface::class);

        $job = new ProcessContactUpdate($contact->id, $updatedData);
        $job->handle($repository);

        $this->assertDatabaseHas('contacts', array_merge(['id' => $contact->id], $updatedData));
    }
}
