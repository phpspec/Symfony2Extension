<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\PSR0\PSR0Locator;
use PhpSpec\Locator\ResourceInterface;

class ControllerLocator extends PSR0Locator
{
    /**
     * @param string $query
     *
     * @return boolean
     */
    public function supportsQuery($query)
    {
        return 1 === preg_match('#.*/.*?Controller.php#', $query);
    }

    /**
     * @param string $classname
     *
     * @return boolean
     */
    public function supportsClass($classname)
    {
        $classname = str_replace('/', '\\', $classname);

        return 1 === preg_match('#.*\\.*?Controller(Spec|)$#', $classname);
    }

    /**
     * @param string $classname
     *
     * @return ResourceInterface
     */
    public function createResource($classname)
    {
        $classname = str_replace('/', '\\', $classname);

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
}
