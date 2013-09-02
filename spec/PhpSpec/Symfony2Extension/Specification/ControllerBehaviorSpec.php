<?php

namespace spec\PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use PhpSpec\Wrapper\Subject;

class AccessibleControllerBehavior extends ControllerBehavior
{
    public function getContainer()
    {
        return $this->container;
    }
}

class ContainerAwareSubject extends Subject
{
    public function setContainer($container)
    {
    }
}

class ControllerBehaviorSpec extends ObjectBehavior
{
    function it_is_an_object_behavior()
    {
        $this->shouldHaveType('PhpSpec\ObjectBehavior');
    }

    function it_provides_an_internal_reference_to_the_container(ContainerAwareSubject $subject, \stdClass $container)
    {
        $this->beAnInstanceOf('spec\\PhpSpec\\Symfony2Extension\\Specification\\AccessibleControllerBehavior');
        $this->getWrappedObject()->setSpecificationSubject($subject->getWrappedObject());

        $subject->setContainer($container)->shouldBeCalled();

        $this->setContainer($container);

        $this->getContainer()->shouldBe($container);
    }
}
