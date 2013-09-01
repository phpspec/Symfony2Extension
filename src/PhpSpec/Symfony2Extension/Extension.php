<?php

namespace PhpSpec\Symfony2Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\Symfony2Extension\Locator\PSR0Locator;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->addConfigurator($this->getLocatorConfigurator());
    }

    /**
     * @return callable
     */
    private function getLocatorConfigurator()
    {
        return function ($c) {
            $suite = $c->getParam('symfony2_locator');
            $namespace = isset($suite['namespace']) ? $suite['namespace'] : '';
            $specSubNamespace = isset($suite['spec_sub_namespace']) ? $suite['spec_sub_namespace'] : 'Spec';
            $srcPath = isset($suite['src_path']) ? $suite['src_path'] : 'src';
            $specPaths = isset($suite['spec_paths']) ? $suite['spec_paths'] : array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec');

            $c->setShared('locator.locators.symfony2_locator',
                function($c) use ($namespace, $specSubNamespace, $srcPath, $specPaths) {
                    return new PSR0Locator($namespace, $specSubNamespace, $srcPath, $specPaths);
                }
            );
        };

    }
}
