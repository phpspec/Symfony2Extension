<?php

namespace spec\PhpSpec\Symfony2Extension\CodeGenerator;

use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Console\IO;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Locator\ControllerResource;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class ControllerClassGeneratorSpec extends ObjectBehavior
{
    function let(IO $io, TemplateRenderer $templateRenderer, Filesystem $filesystem)
    {
        $this->beConstructedWith($io, $templateRenderer, $filesystem);
    }

    /*
    function it_is_a_class_generator()
    {
        $this->shouldHaveType('PhpSpec\CodeGenerator\Generator\ClassGenerator');
    }

    function it_supports_controller_class_resources(ControllerResource $resource)
    {
        $this->supports($resource, 'class', array())->shouldBe(true);
    }

    function it_does_not_support_other_resources(ResourceInterface $resource)
    {
        $this->supports($resource, 'class', array())->shouldBe(false);
    }

    function it_does_not_support_method_generation(ControllerResource $resource)
    {
        $this->supports($resource, 'method', array())->shouldBe(false);
    }

    function it_generates_a_controller_class_template(ResourceInterface $resource, TemplateRenderer $templateRenderer, Filesystem $filesystem)
    {
        $resource->getSrcFilename()->willReturn('src/Controller/DemoController.php');
        $resource->getName()->willReturn('DemoController');
        $resource->getSrcNamespace()->willReturn('Controller');
        $resource->getSrcClassname()->willReturn('Controller\\DemoController');

        $filesystem->pathExists('src/Controller/DemoController.php')->willReturn(false);
        $filesystem->isDirectory('src')->willReturn(true);
        $filesystem->isDirectory('src/Controller')->willReturn(true);

        $templateRenderer->render('class', Argument::any())->willReturn(null);
        $templateRenderer->renderString(Argument::type('string'), Argument::type('array'))->willReturn('RENDERED TEMPLATE');

        $filesystem->putFileContents('src/Controller/DemoController.php', 'RENDERED TEMPLATE')->shouldBeCalled();

        $this->generate($resource, array())->shouldReturn(null);
    }

    function it_has_a_high_priority()
    {
        $this->getPriority()->shouldReturn(10);
    }

    function it_is_test()
    {
        $this->shouldThrow('\InvalidArgumentException')->duringGenerate('arg1', 'arg2');
    }
        */
}
