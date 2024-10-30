<?php

namespace RezaulHReza\AiService\Commands;

use Illuminate\Console\Command;

class AiServiceCommand extends Command
{
    public $signature = 'ai-service';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
