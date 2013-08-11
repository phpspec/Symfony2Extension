<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Locator\ResourceLocatorInterface;

class PSR0Resource implements ResourceInterface
{
    /**
     * @var array
     */
    private $parts;

    /**
     * @var ResourceLocatorInterface
     */
    private $locator;

    /**
     * @param array                    $parts
     * @param ResourceLocatorInterface $locator
     */
    public function __construct($parts, ResourceLocatorInterface $locator)
    {
        $this->parts = $parts;
        $this->locator = $locator;
    }

    public function getName()
    {
        return end($this->parts);
    }

    public function getSpecName()
    {
        return $this->getName().'Spec';
    }

    public function getSrcFilename()
    {
        // @todo: Implement getSrcFilename() method.
    }

    public function getSrcNamespace()
    {
        // @todo: Implement getSrcNamespace() method.
    }

    public function getSrcClassname()
    {
        // @todo: Implement getSrcClassname() method.
    }

    public function getSpecFilename()
    {
        // @todo: Implement getSpecFilename() method.
    }

    public function getSpecNamespace()
    {
        // @todo: Implement getSpecNamespace() method.
    }

    public function getSpecClassname()
    {
        // @todo: Implement getSpecClassname() method.
    }
}