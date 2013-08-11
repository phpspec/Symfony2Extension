<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\Util\Filesystem;

class PSR0Locator implements ResourceLocatorInterface
{
    private $srcNamespace;

    private $specSubNamespace;

    private $srcPath;

    private $specPaths = array();

    private $filesystem;

    public function __construct($srcNamespace = '', $specSubNamespace = 'Spec', $srcPath = 'src', $specPaths = array(), Filesystem $filesystem = null)
    {
        $this->srcNamespace = $srcNamespace;
        $this->specSubNamespace = $specSubNamespace;
        $this->srcPath = rtrim(realpath($srcPath), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $this->specPaths = $this->expandSpecPaths($specPaths);
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

            $resources[] = $this->createResource($relative);
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
        $classname = str_replace('/', '\\', $classname);
        $classname = str_replace(array($this->specSubNamespace, 'Spec'), '', $classname);

        if ('' === $this->srcNamespace || 0 === strpos($classname, $this->srcNamespace)) {
            return new PSR0Resource(array_filter(explode('\\', $classname)), $this->specSubNamespace, $this->srcPath);
        }

        return null;
    }

    public function getPriority()
    {
        return 0;
    }

    /**
     * @param array $specPaths
     *
     * @return array
     */
    private function expandSpecPaths(array $specPaths)
    {
        $result = array();

        foreach ($specPaths as $specPath) {
            $paths = glob($specPath, GLOB_ONLYDIR);
            if (!empty($paths)) {
                $paths = array_filter(array_map('realpath', $paths));
                $result = array_merge($result, $paths);
            }
        }

        return $result;
    }
}