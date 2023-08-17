<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface ContactRepositoryInterface
{
    public function all(): Collection;
    public function find(string $id);
    public function create(array $attributes);
    public function update(string $id, array $attributes);
    public function delete(string $id);
}
