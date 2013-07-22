<?php

namespace spec\PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class MySpec extends ControllerBehavior
{
    public function getContainer()
    {
        return $this->container;
    }
}

class ControllerBehaviorSpec extends ObjectBehavior
{
    function it_is_an_object_behavior()
    {
        $this->shouldHaveType('PhpSpec\ObjectBehavior');
    }

    function it_sets_up_the_container_on_an_aware_controller(ContainerAwareInterface $controller)
    {
        $this->beAnInstanceOf('spec\PhpSpec\Symfony2Extension\Specification\MySpec');

        $this->getWrappedObject()->setSpecificationSubject($controller);

        $this->let();

        $this->getContainer()->shouldHaveType('PhpSpec\Symfony2Extension\Specification\Container');
    }

    function it_does_not_set_the_container_on_a_regular_controller(\stdClass $controller)
    {
        $this->beAnInstanceOf('spec\PhpSpec\Symfony2Extension\Specification\MySpec');

        $this->getWrappedObject()->setSpecificationSubject($controller);

        $this->let();

        $this->getContainer()->shouldReturn(null);
    }
}
