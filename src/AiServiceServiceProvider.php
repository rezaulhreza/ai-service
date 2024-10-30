<?php

namespace RezaulHReza\AiService;

use RezaulHReza\AiService\Commands\AiServiceCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class AiServiceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('ai-service')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(AiServiceCommand::class);
    }
}
