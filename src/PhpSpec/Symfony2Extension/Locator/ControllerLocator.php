<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\PSR0\PSR0Locator;
use PhpSpec\Locator\ResourceInterface;

class ControllerLocator extends PSR0Locator
{
    /**
     * @return array
     */
    public function getAllResources()
    {
        return array();
    }

    /**
     * @param string $classname
     *
     * @return boolean
     */
    public function supportsClass($classname)
    {
        $classname = $this->fixClassNamespace($classname);

        return $this->isControllerClass($classname) && parent::supportsClass($classname);
    }

    /**
     * @param string $classname
     *
     * @return ResourceInterface
     */
    public function createResource($classname)
    {
        $classname = $this->fixClassNamespace($classname);

        if (0 === strpos($classname, $this->getSpecNamespace())) {
            $relative = substr($classname, strlen($this->getSpecNamespace()));

            return new ControllerResource(explode('\\', $relative), $this);
        }

        if ('' === $this->getSrcNamespace() || 0 === strpos($classname, $this->getSrcNamespace())) {
            $relative = substr($classname, strlen($this->getSrcNamespace()));

            return new ControllerResource(explode('\\', $relative), $this);
        }

        return null;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @param string $classname
     *
     * @return string
     */
    private function fixClassNamespace($classname)
    {
        return str_replace('/', '\\', $classname);
    }

    /**
     * @param string $classname
     *
     * @return boolean
     */
    private function isControllerClass($classname)
    {
        return 1 === preg_match('#.*Bundle\\\\Controller\\\\.*?Controller(Spec|)$#', $classname);
    }
}
