<?php

namespace LaravelSuperBan\SuperBan\Commands;

use Illuminate\Console\Command;

class SuperBanCommand extends Command
{
    public $signature = 'laravel-superban';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
