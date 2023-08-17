<?php

namespace App\Repositories;

use App\Models\Contact;
use Illuminate\Support\Collection;

class EloquentContactRepository implements ContactRepositoryInterface
{
    public function all(): Collection
    {
        return Contact::all();
    }

    public function find(string $id)
    {
        return Contact::find($id);
    }

    public function create(array $attributes)
    {
        return Contact::create($attributes);
    }

    public function update(string $id, array $attributes)
    {
        $contact = Contact::find($id);
        $contact->update($attributes);
        return $contact;
    }

    public function delete(string $id)
    {
        return Contact::destroy($id);
    }
}
