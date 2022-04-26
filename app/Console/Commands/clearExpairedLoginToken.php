<?php

namespace App\Console\Commands;

use App\Models\LoginToken;
use Illuminate\Console\Command;

class clearExpairedLoginToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:clear-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'clear expaired login token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        return LoginToken::expaired()->delete();
    }
}
