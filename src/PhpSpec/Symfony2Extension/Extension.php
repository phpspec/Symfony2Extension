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
use PhpSpec\Symfony2Extension\Runner\Maintainer\CommonCollaboratorsMaintainer;
use PhpSpec\Symfony2Extension\Runner\Collaborator\DefaultFactory;
use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerFactory;
use PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

class Extension implements ExtensionInterface
{
    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container) //, array $params = array())
    {
        //foreach ($params as $key => $value) {
        //    $container->setParam('symfony2_extension.'.$key, $value);
        //}
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
            'runner.maintainers.common_collaborators',
            function ($c) {
                return new CommonCollaboratorsMaintainer(
                    $c->get('unwrapper'),
                    $c->get('collaborator_factory'),
                    $c->getParam('symfony2_extension.common-collaborators', array())
                );
            }
        );

        $container->setShared('collaborator_factory.default', function ($c) {
            return new DefaultFactory(
                $c->get('unwrapper')
            );
        });

        $container->setShared('collaborator_factory', function ($c) {
            return new InitializerFactory(
                $c->get('collaborator_factory.default'),
                $c->getByPrefix('collaborator.initializer'),
                $c->getParam('symfony2_extension.common-collaborators', array())
            );
        });

        $container->setShared('collaborator.initializer.container', function ($c) {
            return new Initializer\Container(
                $c->getParam('symfony2_extension.common-collaborators', array())
            );
        }); // first!

        $container->setShared('collaborator.initializer.request', function ($c) {
            return new Initializer\Request;
        });

        $container->setShared('collaborator.initializer.session', function ($c) {
            return new Initializer\Session;
        });

        $container->setShared('collaborator.initializer.router', function ($c) {
            return new Initializer\Router;
        });

        $container->setShared('collaborator.initializer.templating', function ($c) {
            return new Initializer\Router;
        });

        $container->setShared('collaborator.initializer.doctrine', function ($c) {
            return new Initializer\Doctrine;
        });
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
            $specPaths = isset($suite['spec_paths']) ? $suite['spec_paths'] : array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec');

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
