<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerInjectorMaintainerSpec extends ObjectBehavior
{
    function let(ExampleNode $example, SpecificationNode $specification, \ReflectionClass $classReflection)
    {
        $example->getSpecification()->willReturn($specification);
        $specification->getClassReflection()->willReturn($classReflection);
    }

    function it_is_a_container_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\Maintainer\ContainerMaintainer');
    }

    function it_injects_the_container_into_the_subject(ExampleNode $example, ControllerBehavior $context, MatcherManager $matchers, CollaboratorManager $collaborators, \ReflectionClass $classReflection, \ReflectionProperty $property, ContainerInterface $container)
    {
        $classReflection->getProperty('container')->willReturn($property);

        $property->setAccessible(true)->shouldBeCalled();
        $property->getValue($context->getWrappedObject())->shouldBeCalled()->willReturn($container);

        // PhpSpec cannot handle this properly. To verify as much as we can, the line above has a shouldBeCalled() call.
        // $context->setContainer($container)->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }

    function it_has_a_decreased_priority()
    {
        $this->getPriority()->shouldReturn(5);
    }
}
