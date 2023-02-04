<?php

namespace App\Jobs;

use App\Lib\CustomerCreator;
use App\Models\Registration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Shopify\Utils;
use Spatie\RateLimitedMiddleware\RateLimited;

class ProcessRegistration implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public Registration $registration;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    public function middleware()
    {
        $rateLimitedMiddleware = (new RateLimited())
            ->allow(39)
            ->everyMinute()
            ->key('rl_' . $this->registration->shop)
            ->releaseAfterSeconds(90);

        return [$rateLimitedMiddleware];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        CustomerCreator::create($this->registration->toArray());
    }
}
