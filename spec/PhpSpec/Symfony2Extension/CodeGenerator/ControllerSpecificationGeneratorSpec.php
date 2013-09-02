<?php

namespace spec\PhpSpec\Symfony2Extension\CodeGenerator;

use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\IO;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Locator\ControllerResource;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ControllerSpecificationGeneratorSpec extends ObjectBehavior
{
    function let(IO $io, TemplateRenderer $templateRenderer, Filesystem $filesystem)
    {
        $this->beConstructedWith($io, $templateRenderer, $filesystem);
    }

    function it_is_a_specification_generator()
    {
        $this->shouldHaveType('PhpSpec\CodeGenerator\Generator\SpecificationGenerator');
    }

    function it_supports_controller_specification_resources(ControllerResource $resource)
    {
        $this->supports($resource, 'specification', array())->shouldBe(true);
    }

    function it_does_not_support_regular_resources(ResourceInterface $resource)
    {
        $this->supports($resource, 'specification', array())->shouldBe(false);
    }

    function it_does_not_support_method_generation(ControllerResource $resource)
    {
        $this->supports($resource, 'method', array())->shouldBe(false);
    }

    function it_generates_a_controller_specification_template(ResourceInterface $resource, TemplateRenderer $templateRenderer, Filesystem $filesystem)
    {
        $resource->getSpecFilename()->willReturn('spec/Controller/DemoControllerSpec.php');
        $resource->getSpecName()->willReturn('DemoControllerSpec');
        $resource->getSpecNamespace()->willReturn('spec\\Controller');
        $resource->getSrcClassname()->willReturn('Controller\\DemoController');

        $filesystem->pathExists('spec/Controller/DemoControllerSpec.php')->willReturn(false);
        $filesystem->isDirectory('spec')->willReturn(true);
        $filesystem->isDirectory('spec/Controller')->willReturn(true);

        $templateRenderer->render('specification', Argument::any())->willReturn(null);
        $templateRenderer->renderString(Argument::type('string'), Argument::type('array'))->willReturn('RENDERED TEMPLATE');

        $filesystem->putFileContents('spec/Controller/DemoControllerSpec.php', 'RENDERED TEMPLATE')->shouldBeCalled();

        $this->generate($resource, array())->shouldReturn(null);
    }

    function it_has_a_high_priority()
    {
        $this->getPriority()->shouldReturn(10);
    }
}
