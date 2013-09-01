<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\Util\Filesystem;

class PSR0Locator implements ResourceLocatorInterface
{
    /**
     * @var string
     */
    private $srcNamespace;

    /**
     * @var string
     */
    private $specSubNamespace;

    /**
     * @var string
     */
    private $srcPath;

    /**
     * @var array
     */
    private $specPaths = array();

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var ResourceFactory
     */
    private $resourceFactory;

    /**
     * @param string          $srcNamespace
     * @param string          $specSubNamespace
     * @param string          $srcPath
     * @param array           $specPaths
     * @param Filesystem      $filesystem
     * @param ResourceFactory $resourceFactory
     */
    public function __construct($srcNamespace = '', $specSubNamespace = 'Spec', $srcPath = 'src', $specPaths = array(), Filesystem $filesystem = null, ResourceFactory $resourceFactory = null)
    {
        $this->srcNamespace = $srcNamespace;
        $this->specSubNamespace = $specSubNamespace;
        $this->srcPath = rtrim(realpath($srcPath), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        $this->specPaths = $this->expandSpecPaths($specPaths);
        $this->filesystem = $filesystem ?: new Filesystem();
        $this->resourceFactory = $resourceFactory ?: new ResourceFactory();
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

    /**
     * @return array
     */
    public function getAllResources()
    {
        return $this->findResources($this->srcPath);
    }

    /**
     * @param string $query
     *
     * @return boolean
     */
    public function supportsQuery($query)
    {
        $path = rtrim(realpath($query), DIRECTORY_SEPARATOR);

        return 0 === strpos($path, rtrim($this->srcPath, DIRECTORY_SEPARATOR));
    }

    /**
     * @param string $query
     *
     * @return PSR0Resource[]
     */
    public function findResources($query)
    {
        $path = realpath($query);

        if (!$path) {
            return array();
        }

        if ('.php' === substr($path, -4)) {
            return array($this->createResourceFromSpecFile($path));
        }

        return $this->findResourcesInSpecPaths($path);
    }

    /**
     * @param string $path
     *
     * @return PSR0Resource|null
     */
    private function createResourceFromSpecFile($path)
    {
        $relativePath = substr($path, strlen($this->srcPath), -4);
        $relativePath = str_replace('Spec', '', $relativePath);

        return $this->createResource($relativePath);
    }

    /**
     * @param string $path
     *
     * @return array
     */
    private function findResourcesInSpecPaths($path)
    {
        $paths = $this->filterSpecPaths($path);

        if (empty($paths)) {
            return array();
        }

        $files = $this->filesystem->findPhpFilesIn($paths);

        return $this->createResourcesFromSpecFiles($files);
    }

    /**
     * Filters out the spec paths which are not child or parent of the path.
     *
     * @param string $path
     *
     * @return array
     */
    private function filterSpecPaths($path)
    {
        $specPaths = array_map(
            function ($value) use ($path) {
                return 0 === strpos($path, $value) ? $path : $value;
            },
            $this->specPaths
        );

        $specPaths = array_filter(
            $specPaths,
            function ($value) use ($path) {
                return 0 === strpos($value, $path);
            }
        );

        return $specPaths;
    }

    /**
     * @param array $files
     *
     * @return PSR0Resource[]
     */
    private function createResourcesFromSpecFiles(array $files)
    {
        $resources = array();

        foreach ($files as $file) {
            $resources[] = $this->createResourceFromSpecFile($file->getRealPath());
        }

        return $resources;
    }

    /**
     * @param string $classname
     *
     * @return boolean
     */
    public function supportsClass($classname)
    {
        $classname = str_replace('/', '\\', $classname);

        return '' === $this->srcNamespace || 0  === strpos($classname, $this->srcNamespace);
    }

    /**
     * @param string $classname
     *
     * @return PSR0Resource|null
     */
    public function createResource($classname)
    {
        $classname = str_replace('/', '\\', $classname);
        $classname = str_replace(array($this->specSubNamespace, 'Spec'), '', $classname);
        $classname = str_replace('\\\\', '\\', $classname);

        if ('' === $this->srcNamespace || 0 === strpos($classname, $this->srcNamespace)) {
            return $this->resourceFactory->create(explode('\\', $classname), $this->specSubNamespace, $this->srcPath);
        }

        return null;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 0;
    }
}