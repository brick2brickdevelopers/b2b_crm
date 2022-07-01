<?php

namespace Modules\RestAPI\Classes\Generator;

class EndpointGenerator extends \Nwidart\Modules\Generators\ModuleGenerator
{
    protected $endpoint = null;

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * Get the list of files will created.
     *
     * @return array
     */
    public function getFiles()
    {

        return [
            'controller/scaffold' => 'Http/Controllers/$STUDLY_NAME$Controller.php',
            'scaffold/requests/create' => 'Http/Requests/$STUDLY_NAME$/CreateRequest.php',
            'scaffold/requests/index' => 'Http/Requests/$STUDLY_NAME$/IndexRequest.php',
            'scaffold/requests/update' => 'Http/Requests/$STUDLY_NAME$/UpdateRequest.php',
            'scaffold/requests/delete' => 'Http/Requests/$STUDLY_NAME$/DeleteRequest.php',
            'scaffold/requests/show' => 'Http/Requests/$STUDLY_NAME$/ShowRequest.php',
            'scaffold/model' => 'Entities/$STUDLY_NAME$.php',
        ];
    }

    /**
     * Get snake name for the endpoint
     *
     * @return array
     */
    protected function getSnakeNameReplacement()
    {
        return snake_case($this->getEndpoint());
    }

    /**
     * Get upper case name for endpoint
     *
     * @return array
     */
    protected function getUpperNameReplacement()
    {
        return strtoupper(snake_case($this->getEndpoint()));
    }

    /**
     * Get lower case name for endpoint
     *
     * @return array
     */
    protected function getLowerNameReplacement()
    {
        return strtolower(snake_case($this->getEndpoint()));
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getStudlyNameReplacement()
    {
        return ucfirst(studly_case(strtolower($this->getEndpoint())));
    }

    /**
     * Get the module name in studly case.
     *
     * @return string
     */
    protected function getModuleReplacement()
    {
        return ucfirst(studly_case(strtolower($this->getName())));
    }

    /**
     * Generate the module.
     */
    public function generate()
    {
        $this->generateFiles();
        $this->generateResources();
    }

    /**
     * Generate the files for endpoint
     *
     * @return array
     */
    public function generateFiles()
    {
        foreach ($this->getFiles() as $stub => $file) {
            $path = $this->module->getModulePath($this->getName()) . $file;

            $replacements = $this->getReplacement($stub);

            foreach ($replacements as $key => $replacement) {
                $path = str_replace('$' . $key . '$', $replacement, $path);
            }

            if (!$this->filesystem->isDirectory($dir = dirname($path))) {
                $this->filesystem->makeDirectory($dir, 0775, true);
            }

            $this->filesystem->put($path, $this->getStubContents($stub));

            $this->console->info('Created : ' . $path);
        }
    }

    public function generateResources()
    {
//        $this->console->call('module:make-migration', [
//            'name'     => 'create_' . $this->getEndpoint() . '_table',
//            'module'   => $this->getName(),
//        ]);
    }
}
