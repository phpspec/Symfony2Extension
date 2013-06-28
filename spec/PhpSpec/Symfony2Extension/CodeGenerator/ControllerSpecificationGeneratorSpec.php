<?php

namespace spec\PhpSpec\Symfony2Extension\CodeGenerator;

use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Locator\ControllerResource;
use Prophecy\Argument;
use PhpSpec\Console\IO;
use PhpSpec\CodeGenerator\TemplateRenderer;

class ControllerSpecificationGeneratorSpec extends ObjectBehavior
{
    function let(IO $io, TemplateRenderer $templateRenderer)
    {
        $this->beConstructedWith($io, $templateRenderer);
    }

    function it_is_a_generator()
    {
        $this->shouldHaveType('PhpSpec\CodeGenerator\Generator\GeneratorInterface');
    }

    function it_supports_controller_specification_resources(ControllerResource $controllerResource, ResourceInterface $resource)
    {
        $this->supports($controllerResource, 'specification', array())->shouldBe(true);
        $this->supports($resource, 'specification', array())->shouldBe(false);
    }

    function it_does_not_support_method_generation(ControllerResource $controllerResource)
    {
        $this->supports($controllerResource, 'method', array())->shouldBe(false);
    }
}
