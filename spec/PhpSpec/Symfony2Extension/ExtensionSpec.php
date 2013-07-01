<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\IO;

class ExtensionSpec extends ObjectBehavior
{
    function let(ServiceContainer $container)
    {
        $container->setShared(Argument::cetera())->willReturn();
        $container->addConfigurator(Argument::any())->willReturn();
    }

    function it_is_an_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }

    function it_registers_the_controller_locator($container)
    {
        $container->setShared(
            'locator.locators.symfony2_controller_locator',
            $this->service('PhpSpec\Symfony2Extension\Locator\ControllerLocator', $container)
        )->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_the_controller_specification_code_generator($container, IO $io, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($io);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared(
            'code_generator.generators.symfony2_controller_specification',
            $this->service('PhpSpec\Symfony2Extension\CodeGenerator\ControllerSpecificationGenerator', $container)
        )->shouldBeCalled();

        $this->load($container);
    }

    function it_registers_the_controller_class_code_generator($container, IO $io, TemplateRenderer $templateRenderer)
    {
        $container->get('console.io')->willReturn($io);
        $container->get('code_generator.templates')->willReturn($templateRenderer);

        $container->setShared(
            'code_generator.generators.symfony2_controller_class',
            $this->service('PhpSpec\Symfony2Extension\CodeGenerator\ControllerClassGenerator', $container)
        )->shouldBeCalled();

        $this->load($container);
    }

    protected function service($class, $container)
    {
        return Argument::that(function ($callback) use ($class, $container) {
            if (is_callable($callback)) {
                $result = $callback($container->getWrappedObject());

                return $result instanceof $class;
            }

            return false;
        });
    }
}
