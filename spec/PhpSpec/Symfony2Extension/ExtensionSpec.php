<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\IO;
use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Symfony2Extension\Runner\CollaboratorFactory;

class ExtensionSpec extends ObjectBehavior
{
    private $configurator;

    function let(ServiceContainer $container)
    {
        $container->setShared(Argument::cetera())->willReturn();
        $container->addConfigurator(Argument::any())->willReturn();
    }

    function it_is_a_phpspec_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }

    function it_registers_a_custom_locator_with_configuration(ServiceContainer $container)
    {
        $container->getParam('symfony2_locator')->willReturn(
            array(
                'namespace' => 'Acme',
                'spec_sub_namespace' => 'Specs',
                'src_path' => 'lib',
                'spec_paths' => array('lib/Acme/*/Specs')
            )
        );

        $container->addConfigurator($this->trackedConfigurator())->shouldBeCalled();
        $container->setShared(
            'locator.locators.symfony2_locator',
            $this->service('PhpSpec\Symfony2Extension\Locator\PSR0Locator', $container)
        )->shouldBeCalled();

        $this->load($container);
        $configurator = $this->configurator;
        $configurator($container->getWrappedObject());
    }

    function it_registers_runner_maintainers_for_the_container(ServiceContainer $container, Unwrapper $unwrapper, CollaboratorFactory $factory)
    {
        $container->setShared(
            'runner.maintainers.common_collaborators',
            $this->service('PhpSpec\Symfony2Extension\Runner\Maintainer\CommonCollaboratorsMaintainer', $container)
        )->shouldBeCalled();

        $container->setShared(
            'collaborator_factory',
            $this->service('PhpSpec\Symfony2Extension\Runner\CollaboratorFactory', $container)
        )->shouldBeCalled();

        $container->getParam('symfony2_extension.common-collaborators', array())->willReturn(array());
        $container->get('collaborator_factory')->willReturn($factory);
        $container->get('unwrapper')->willReturn($unwrapper);

        $this->load($container);
    }

    function it_registers_controller_class_generator(ServiceContainer $container, IO $io, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($io);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared(
            'code_generator.generators.symfony2_controller_class',
            $this->service('PhpSpec\Symfony2Extension\CodeGenerator\ControllerClassGenerator', $container)
        )->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_controller_specification_generator(ServiceContainer $container, IO $io, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($io);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared(
            'code_generator.generators.symfony2_controller_specification',
            $this->service('PhpSpec\Symfony2Extension\CodeGenerator\ControllerSpecificationGenerator', $container)
        )->shouldBeCalled();

        $this->load($container);
    }

    private function trackedConfigurator()
    {
        $this->configurator = function () { throw new \LogicException('Configurator was not added'); };

        $configurator = &$this->configurator;

        return Argument::that(
            function ($c) use (&$configurator) {
                if (!is_callable($c)) {
                    return false;
                }

                $configurator = $c;

                return true;
            }
        );
    }

    private function service($class, ServiceContainer $container)
    {
        return Argument::that(
            function ($callback) use ($class, $container) {
                if (!is_callable($callback)) {
                    throw new \LogicException('Expected a callable to be set on the container');
                }

                $result = $callback($container->getWrappedObject());

                if (!$result instanceof $class) {
                    $message = sprintf('Expected the service to be an instance of "%s" but got: "%s"', $class, is_object($result) ? get_class($result) : gettype($result));

                    throw new \LogicException($message);
                }

                return true;
            }
        );
    }
}
