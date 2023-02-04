<?php

namespace App\Console\Commands;

use App\Jobs\ProcessRegistration;
use App\Lib\CustomerCreator;
use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestLimit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:limit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Shopify API limit';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $registrations = Registration::query()->limit(100)->get();
        foreach ($registrations as $registration) {
           // CustomerCreator::create($registration->toArray());
            ProcessRegistration::dispatch($registration);
        }
        return 0;
    }
}
