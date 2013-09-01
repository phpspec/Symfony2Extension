<?php

namespace PhpSpec\Symfony2Extension\Locator;

class ResourceFactory
{
    /**
     * @param array  $namespaceParts
     * @param string $specSubNamespace
     * @param string $srcPath
     *
     * @return PSR0Resource
     */
    public function create(array $namespaceParts, $specSubNamespace = 'Spec', $srcPath = 'src')
    {
        $count = count($namespaceParts);
        $lastPart = isset($namespaceParts[$count - 1]) ? $namespaceParts[$count - 1] : null;
        $directory = isset($namespaceParts[$count - 2]) ? $namespaceParts[$count - 2] : null;

        if ('Controller' === $directory && strrpos($lastPart, 'Controller')) {
            return new ControllerResource($namespaceParts, $specSubNamespace, $srcPath);
        }

        return new PSR0Resource($namespaceParts, $specSubNamespace, $srcPath);
    }
}
