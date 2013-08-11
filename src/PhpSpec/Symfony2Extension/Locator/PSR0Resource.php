<?php

namespace PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceInterface;

class PSR0Resource implements ResourceInterface
{
    /**
     * @var array
     */
    private $parts;

    /**
     * @var string
     */
    private $srcPath;

    /**
     * @var string
     */
    private $specSuffix;

    /**
     * @param array  $namespaceParts
     * @param string $srcPath
     * @param string $specSuffix
     */
    public function __construct($namespaceParts, $srcPath, $specSuffix = 'Spec')
    {
        $this->parts = $namespaceParts;
        $this->srcPath = $srcPath;
        $this->specSuffix = 'Spec';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return end($this->parts);
    }

    /**
     * @return string
     */
    public function getSpecName()
    {
        return $this->getName().'Spec';
    }

    /**
     * @return string
     */
    public function getSrcFilename()
    {
        return $this->srcPath.implode(DIRECTORY_SEPARATOR, $this->parts).'.php';
    }

    /**
     * @return string
     */
    public function getSrcNamespace()
    {
        $parts = $this->parts;
        array_pop($parts);

        return rtrim(implode('\\', $parts), '\\');
    }

    /**
     * @return string
     */
    public function getSrcClassname()
    {
        return implode('\\', $this->parts);
    }

    /**
     * @return string
     */
    public function getSpecFilename()
    {
        $parts = $this->getSpecParts();

        return $this->srcPath.implode(DIRECTORY_SEPARATOR, $parts).'Spec.php';
    }

    /**
     * @return string
     */
    public function getSpecNamespace()
    {
        $parts = $this->getSpecParts();
        array_pop($parts);

        return rtrim(implode('\\', $parts), '\\');
    }

    /**
     * @return string
     */
    public function getSpecClassname()
    {
        $parts = $this->getSpecParts();

        return implode('\\', $parts).'Spec';
    }

    /**
     * @return array
     */
    private function getSpecParts()
    {
        if (count($this->parts) < 2) {
            $parts = $this->parts;
            array_unshift($parts, $this->specSuffix);

            return $parts;
        }

        $parts = array();

        foreach ($this->parts as $i => $part) {
            $parts[] = $part;

            if ((1 === $i && 'Bundle' !== $part) || preg_match('/^.+Bundle$/', $part)) {
                $parts[] = $this->specSuffix;
            }
        }

        return $parts;
    }
}