<?php

namespace ThirdSense\Generators;

use Illuminate\Support\ServiceProvider;
use ThirdSense\Generators\Commands\RepositoryMakeCommand;
use ThirdSense\Generators\Commands\TransformerMakeCommand;

/**
 * Class GeneratorsServiceProvider
 * @package ThirdSense\Generators
 */
class LumenServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommandGenerator();
        $this->registerConsoleGenerator();
        $this->registerEventGenerator();
        $this->registerJobGenerator();
        $this->registerListenerGenerator();
        $this->registerModelGenerator();
        $this->registerProviderGenerator();
        $this->registerRepositoryGenerator();
        $this->registerTestGenerator();
        $this->registerTransformerGenerator();
    }

    /**
     * Register the make:command generator.
     */
    private function registerCommandGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.Command',
            function ($app) {
                return $app[CommandMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.Command');
    }

    /**
     * Register the make:console generator.
     */
    private function registerConsoleGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.console',
            function ($app) {
                return $app[ConsoleMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.console');
    }

    /**
     * Register the make:event generator.
     */
    private function registerEventGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.event',
            function ($app) {
                return $app[EventMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.event');
    }

    /**
     * Register the make:job generator.
     */
    private function registerJobGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.job',
            function ($app) {
                return $app[JobMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.job');
    }

    /**
     * Register the make:listener generator.
     */
    private function registerListenerGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.listener',
            function ($app) {
                return $app[ListenerMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.listener');
    }

    /**
     * Register the make:model generator.
     */
    private function registerModelGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.model',
            function ($app) {
                return $app[ModelMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.model');
    }

    /**
     * Register the make:provider generator.
     */
    private function registerProviderGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.provider',
            function ($app) {
                return $app[ProviderMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.provider');
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
     * Register the make:test generator.
     */
    private function registerTestGenerator()
    {
        $this->app->singleton(
            'command.thirdsense.test',
            function ($app) {
                return $app[TestMakeCommand::class];
            }
        );

        $this->commands('command.thirdsense.test');
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
