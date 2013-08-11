<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\Util\Filesystem;

class PSR0Locator implements ResourceLocatorInterface
{
    private $srcNamespace;

    private $srcPath;

    private $specPaths = array();

    private $filesystem;

    public function __construct($srcNamespace = '', $srcPath = 'src', $specPaths = array(), Filesystem $filesystem = null)
    {
        $this->srcNamespace = $srcNamespace;
        $this->srcPath = rtrim(realpath($srcPath), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        foreach ($specPaths as $specPath) {
            $paths = glob($specPath, GLOB_ONLYDIR);
            if (!empty($paths)) {
                $paths = array_filter(array_map('realpath', $paths));
                $this->specPaths = array_merge($this->specPaths, $paths);
            }
        }
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    public function getAllResources()
    {
        if (empty($this->specPaths)) {
            return array();
        }

        $files = $this->filesystem->findPhpFilesIn($this->specPaths);
        $resources = array();

        foreach ($files as $file) {
            $path = $file->getRealPath();
            $relative = substr($path, strlen($this->srcPath), -4);
            $relative = str_replace('Spec', '', $relative);

            $resources[] = new PSR0Resource(array_filter(explode(DIRECTORY_SEPARATOR, $relative)), $this->srcPath);
        }

        return $resources;
    }

    public function supportsQuery($query)
    {
        $path = rtrim(realpath($query), DIRECTORY_SEPARATOR);

        return 0 === strpos($path, rtrim($this->srcPath, DIRECTORY_SEPARATOR));
    }

    public function findResources($query)
    {
        // @todo: Implement findResources() method.
    }

    public function supportsClass($classname)
    {
        $classname = str_replace('/', '\\', $classname);

        return '' === $this->srcNamespace || 0  === strpos($classname, $this->srcNamespace);
    }

    public function createResource($classname)
    {
        // @todo: Implement createResource() method.
    }

    public function getPriority()
    {
        return 0;
    }
}