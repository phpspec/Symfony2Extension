<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerLocatorSpec extends ObjectBehavior
{
    function let()
    {
        $srcNamespace = 'Acme';
        $specNamespacePrefix = 'spec';
        $srcPath = 'src';
        $specPath = '.';

        $this->beConstructedWith($srcNamespace, $specNamespacePrefix, $srcPath, $specPath);
    }

    function it_is_a_psr0_locator()
    {
        $this->shouldHaveType('PhpSpec\Locator\PSR0\PSR0Locator');
    }

    function it_supports_controller_queries()
    {
        $this->supportsQuery('spec/Acme/Bundle/DemoBundle/Controller/DemoController.php')->shouldReturn(true);
        $this->supportsQuery('spec/Acme/DemoBundle/Controller/DemoController.php')->shouldReturn(true);
        $this->supportsQuery('spec/Acme/Bundle/DemoBundle/Controller/Demo.php')->shouldReturn(false);
    }

    function it_supports_controller_classes()
    {
        $this->supportsClass('Acme\\Bundle\\DemoBundle\\Controller\\DemoController')->shouldReturn(true);
        $this->supportsClass('Acme\\Bundle\\DemoBundle\\Controller\\Demo')->shouldReturn(false);
        $this->supportsClass('Acme/Bundle/DemoBundle/Controller/DemoController')->shouldReturn(true);
    }

    function it_supports_controller_spec_classes()
    {
        $this->supportsClass('spec\\Acme\\Bundle\\DemoBundle\\Controller\\DemoControllerSpec')->shouldReturn(true);
        $this->supportsClass('spec\\Acme\\Bundle\\DemoBundle\\Controller\\DemoSpec')->shouldReturn(false);
        $this->supportsClass('spec/Acme/Bundle/DemoBundle/Controller/DemoControllerSpec')->shouldReturn(true);
    }

    function it_creates_a_controller_resource_from_spec_class()
    {
        $resource = $this->createResource('spec\\Acme\\Bundle\\DemoBundle\\Controller\\DemoController');

        $resource->shouldBeAnInstanceOf('PhpSpec\Symfony2Extension\Locator\ControllerResource');
        $resource->getSrcClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Controller\\DemoController');
    }

    function it_creates_a_controller_resource_from_a_class()
    {
        $resource = $this->createResource('Acme\\Bundle\\DemoBundle\\Controller\\DemoController');

        $resource->shouldBeAnInstanceOf('PhpSpec\Symfony2Extension\Locator\ControllerResource');
        $resource->getSrcClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Controller\\DemoController');
    }

    function it_creates_a_controller_resource_from_a_class_with_path_separator()
    {
        $resource = $this->createResource('Acme/Bundle/DemoBundle/Controller/DemoController');

        $resource->shouldBeAnInstanceOf('PhpSpec\Symfony2Extension\Locator\ControllerResource');
        $resource->getSrcClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Controller\\DemoController');
    }

    function it_does_not_create_a_controller_resource_from_an_invalid_class()
    {
        $this->createResource('Foo\\Bundle\\DemoBundle\\Controller\\DemoController')->shouldReturn(null);
    }

    function it_has_a_high_priority()
    {
        $this->getPriority()->shouldBe(10);
    }
}
