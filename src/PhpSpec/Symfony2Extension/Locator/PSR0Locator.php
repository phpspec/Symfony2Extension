<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceLocatorInterface;

class PSR0Locator implements ResourceLocatorInterface
{
    private $srcNamespace;

    private $srcPath;

    public function __construct($srcNamespace = '', $srcPath = 'src')
    {
        $this->srcPath = rtrim(realpath($srcPath), '/\\').DIRECTORY_SEPARATOR;
        $this->srcNamespace = ltrim(trim($srcNamespace, ' \\').'\\', '\\');
    }

    public function getFullSrcPath()
    {
        return $this->srcPath.str_replace('\\', DIRECTORY_SEPARATOR, $this->srcNamespace);
    }

    public function getSrcNamespace()
    {
        return $this->srcNamespace;
    }

    public function getAllResources()
    {
        // @todo: Implement getAllResources() method.
    }

    public function supportsQuery($query)
    {
        // @todo: Implement supportsQuery() method.
    }

    public function findResources($query)
    {
        // @todo: Implement findResources() method.
    }

    public function supportsClass($classname)
    {
        // @todo: Implement supportsClass() method.
    }

    public function createResource($classname)
    {
        // @todo: Implement createResource() method.
    }

    public function getPriority()
    {
        // @todo: Implement getPriority() method.
    }
}