<?php

namespace App\Jobs;

use App\Repositories\ContactRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContactUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;
    protected $data;

    public function __construct($id, array $data)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function handle(ContactRepositoryInterface $repository)
    {
        $repository->update($this->id, $this->data);
    }
}
