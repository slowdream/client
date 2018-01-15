<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class FullUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $hard;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($hard = false)
    {
        $this->hard = $hard;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        dispatch(new update\UpdateBackend($this->hard));
        dispatch(new update\UpdateFrontend);
    }
}
