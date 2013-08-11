<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Symfony2Extension\Locator\PSR0Locator as Locator;

class PSR0Resource implements ResourceInterface
{
    /**
     * @var array
     */
    private $parts;

    /**
     * @var Locator
     */
    private $locator;

    /**
     * @param array   $parts
     * @param Locator $locator
     */
    public function __construct($parts, Locator $locator)
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
        return $this->locator->getFullSrcPath().implode(DIRECTORY_SEPARATOR, $this->parts).'.php';
    }

    public function getSrcNamespace()
    {
        $parts = $this->parts;
        array_pop($parts);

        return rtrim($this->locator->getSrcNamespace().implode('\\', $parts), '\\');
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