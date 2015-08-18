<?php

namespace ThirdSense\Generators\Commands;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class TransformerMakeCommand
 * @package ThirdSense\Generators\Commands
 */
class TransformerMakeCommand extends Command
{
    use AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:transformer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new transformer class';

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * @var array|string
     */
    protected $className;

    /**
     * @var
     */
    protected $entityNamespace;

    /**
     * @var mixed
     */
    protected $entityName;

    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->className       = $this->argument('name');
        $this->entityName      = $this->extractName($this->argument('entity'));
        $this->entityNamespace = $this->extractNamespace($this->argument('entity'));

        $this->makeTransformer();
    }

    /**
     * Generate the desired transformer.
     */
    protected function makeTransformer()
    {
        if ($this->files->exists($path = $this->getTransformerPath())) {
            return $this->error($this->className . ' already exists!');
        }
        $this->makeDirectory($path);
        $this->files->put($path, $this->compileTransformerStub());
        $this->info('Transformer created successfully.');
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * Get the path to where we should create the transformer.
     *
     * @return string
     */
    protected function getTransformerPath()
    {
        return './app/Transformers/' . $this->className . '.php';
    }

    /**
     * Compile the transformer stub.
     *
     * @return string
     */
    protected function compileTransformerStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/transformer.stub');
        $this
            ->replaceAppNamespace($stub)
            ->replaceClassName($stub)
            ->replaceEntityName($stub);

        return $stub;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     *
     * @return $this
     */
    protected function replaceAppNamespace(&$stub)
    {
        $stub = str_replace('{{app-namespace}}', $this->getAppNamespace(), $stub);

        return $this;
    }

    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     *
     * @return $this
     */
    protected function replaceClassName(&$stub)
    {
        $stub = str_replace('{{name}}', $this->className, $stub);

        return $this;
    }

    /**
     * Replace the table name in the stub.
     *
     * @param  string $stub
     *
     * @return $this
     */
    protected function replaceEntityName(&$stub)
    {
        if (!empty($this->entityName)) {
            $stub = str_replace(
                '{{entity-use-statement}}',
                'use ' . $this->entityNamespace . '\\' . $this->entityName . ';',
                $stub
            );
            $stub = str_replace('{{entity-name}}', $this->entityName, $stub);
            $stub = str_replace('{{entity-variable}}', ' $' . camel_case($this->entityName), $stub);
            $stub = str_replace('{{entity-base-route}}', camel_case($this->entityName), $stub);
        } else {
            $stub = str_replace('{{entity-use-statement}}', '', $stub);
            $stub = str_replace('{{entity-name}}', '', $stub);
            $stub = str_replace('{{entity-variable}}', '$data', $stub);
            $stub = str_replace('{{entity-base-route}}', 'data', $stub);
        }

        return $this;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the transformer'],
            ['entity', InputArgument::OPTIONAL, 'The name of the associated entity'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

    /**
     * Extract the name of the class from the fully qualified class.
     *
     * @param $argument
     *
     * @return mixed
     */
    protected function extractName($argument)
    {
        $parts = $this->classParts($argument);

        return array_pop($parts);
    }


    /**
     * Extract the namespace part of the fully qualified class.
     *
     * @param $argument
     *
     * @return string
     */
    protected function extractNamespace($argument)
    {
        $parts = $this->classParts($argument);

        // remove the classname
        array_pop($parts);

        return implode('\\', $parts);
    }

    /**
     *
     * @param $argument
     *
     * @return array
     */
    protected function classParts($argument)
    {
        return explode('/', $argument);
    }
}
