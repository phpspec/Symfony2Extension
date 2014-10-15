<?php

namespace PhpSpec\Symfony2Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\ServiceContainer;
use PhpSpec\Symfony2Extension\CodeGenerator\ControllerClassGenerator;
use PhpSpec\Symfony2Extension\CodeGenerator\ControllerSpecificationGenerator;
use PhpSpec\Symfony2Extension\Locator\PSR0Locator;
use PhpSpec\Symfony2Extension\Runner\Maintainer\ContainerInitializerMaintainer;
use PhpSpec\Symfony2Extension\Runner\Maintainer\ContainerInjectorMaintainer;
use PhpSpec\Symfony2Extension\Specification\Container;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $this->registerConfigurators($container);
        $this->registerRunnerMaintainers($container);
        $this->registerCodeGenerators($container);
    }

    /**
     * @param ServiceContainer $container
     */
    private function registerRunnerMaintainers(ServiceContainer $container)
    {
        $container->setShared(
            'runner.maintainers.container_initializer',
            function ($c) {
                return new ContainerInitializerMaintainer($c->get('unwrapper'));
            }
        );

        $container->setShared(
            'runner.maintainers.container_injector',
            function ($c) {
                return new ContainerInjectorMaintainer();
            }
        );
    }

    /**
     * @param ServiceContainer $container
     */
    private function registerConfigurators(ServiceContainer $container)
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
            $specPaths = isset($suite['spec_paths']) ? $suite['spec_paths'] : array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec', 'src/*/Spec');

            $c->setShared('locator.locators.symfony2_locator',
                function($c) use ($namespace, $specSubNamespace, $srcPath, $specPaths) {
                    return new PSR0Locator($namespace, $specSubNamespace, $srcPath, $specPaths);
                }
            );
        };

    }

    /**
     * @param ServiceContainer $container
     */
    private function registerCodeGenerators(ServiceContainer $container)
    {
        $container->setShared(
            'code_generator.generators.symfony2_controller_class',
            function ($c) {
                return new ControllerClassGenerator($c->get('console.io'), $c->get('code_generator.templates'));
            }
        );

        $container->setShared(
            'code_generator.generators.symfony2_controller_specification',
            function ($c) {
                return new ControllerSpecificationGenerator($c->get('console.io'), $c->get('code_generator.templates'));
            }
        );
    }
}
