<?php

namespace ThirdSense\Generators;

use Illuminate\Support\ServiceProvider;
use ThirdSense\Generators\Commands\RepositoryMakeCommand;
use ThirdSense\Generators\Commands\TransformerMakeCommand;

/**
 * Class GeneratorsServiceProvider
 * @package ThirdSense\Generators
 */
class LaravelServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRepositoryGenerator();
        $this->registerTransformerGenerator();
    }

    /**
     * Register the make:repository generator.
     */
    private function registerRepositoryGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.repository',
            function ($app) {
                return $app[RepositoryMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.repository');
    }

    /**
     * Register the make:transformer generator.
     */
    private function registerTransformerGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.transformer',
            function ($app) {
                return $app[TransformerMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.transformer');
    }
}
