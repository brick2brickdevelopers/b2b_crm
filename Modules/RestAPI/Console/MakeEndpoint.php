<?php

namespace Modules\RestAPI\Console;

use Illuminate\Console\Command;
use Modules\RestAPI\Classes\Generator\EndpointGenerator;

class MakeEndpoint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make-endpoint {endpoint} {module}';

    /**cd M
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate endpoint';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $module = $this->argument('module');
        $endpoint = $this->argument('endpoint');

        with(new EndpointGenerator($module))
            ->setFilesystem($this->laravel['files'])
            ->setEndpoint($endpoint)
            ->setModule($this->laravel['modules'])
            ->setConfig($this->laravel['config'])
            ->setConsole($this)
            ->generate();
    }
}
