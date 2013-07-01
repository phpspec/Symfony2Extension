<?php

namespace PhpSpec\Symfony2Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\Symfony2Extension\Locator\ControllerLocator;
use PhpSpec\Symfony2Extension\CodeGenerator\ControllerClassGenerator;
use PhpSpec\Symfony2Extension\CodeGenerator\ControllerSpecificationGenerator;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $container->setShared('code_generator.generators.symfony2_controller_specification', function($c) {
            return new ControllerSpecificationGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('code_generator.generators.symfony2_controller_class', function($c) {
            return new ControllerClassGenerator(
                $c->get('console.io'),
                $c->get('code_generator.templates')
            );
        });

        $container->setShared('locator.locators.symfony2_controller_locator',
            function($c) {
                return new ControllerLocator();
            }
        );
    }
}
