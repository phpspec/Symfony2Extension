<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use Prophecy\Argument;

class UserControllerSpec extends ControllerBehavior
{
}

class UserSpec extends ObjectBehavior
{
}

class ContainerInitializerMaintainerSpec extends ObjectBehavior
{
    function let(ExampleNode $example, SpecificationNode $specification, \ReflectionClass $classReflection)
    {
        $example->getSpecification()->willReturn($specification);
        $specification->getClassReflection()->willReturn($classReflection);
    }

    function it_is_a_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Runner\Maintainer\MaintainerInterface');
    }

    function it_is_a_container_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\Maintainer\ContainerMaintainer');
    }

    function it_has_an_increased_priority()
    {
        $this->getPriority()->shouldReturn(15);
    }

    function it_supports_controller_behavior(ExampleNode $example, \ReflectionClass $classReflection)
    {
        $classReflection->getName()->willReturn('spec\PhpSpec\Symfony2Extension\Runner\Maintainer\UserControllerSpec');

        $this->supports($example)->shouldReturn(true);
    }

    function it_does_not_support_other_behaviors(ExampleNode $example, \ReflectionClass $classReflection)
    {
        $classReflection->getName()->willReturn('spec\PhpSpec\Symfony2Extension\Runner\Maintainer\UserSpec');

        $this->supports($example)->shouldReturn(false);
    }

    function it_creates_the_container(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators, \ReflectionClass $classReflection, \ReflectionProperty $property)
    {
        $classReflection->getProperty('container')->willReturn($property);

        $property->setAccessible(true)->shouldBeCalled();
        $property->setValue($context, Argument::type('PhpSpec\\Symfony2Extension\\Specification\\Container'))->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }
}
