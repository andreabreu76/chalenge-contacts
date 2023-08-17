<?php

namespace App\Jobs;

use App\Repositories\ContactRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContactCreation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function getData()
    {
        return $this->data;
    }

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(ContactRepositoryInterface $repository)
    {
        $repository->create($this->data);
    }
}
