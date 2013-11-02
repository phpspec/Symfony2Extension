<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Loader\Node\SpecificationNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserControllerSpec extends ControllerBehavior
{
}

class UserSpec extends ObjectBehavior
{
}

class ContainerInitializerMaintainerSpec extends ObjectBehavior
{
    function let(Unwrapper $unwrapper, ExampleNode $example, SpecificationNode $specification, \ReflectionClass $classReflection)
    {
        $this->beConstructedWith($unwrapper);

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

    function it_sets_the_container_if_found_in_collaborators(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators, \ReflectionClass $classReflection, \ReflectionProperty $property, ContainerInterface $container)
    {
        $classReflection->getProperty('container')->willReturn($property);

        $collaborators->has('container')->willReturn(true);
        $collaborators->get('container')->willReturn($container);

        $property->setAccessible(true)->shouldBeCalled();
        $property->setValue($context, $container)->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }

    function it_creates_the_container_collaborator_if_it_is_not_found(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators, \ReflectionClass $classReflection, \ReflectionProperty $property, ContainerInterface $container)
    {
        $classReflection->getProperty('container')->willReturn($property);

        $collaborators->has('container')->willReturn(false);
        $collaborators->set('container', Argument::type('Symfony\Component\DependencyInjection\ContainerInterface'))
            ->will(function ($arguments, $collaborators) {
                $collaborators->get('container')->willReturn($arguments[1]);
            });

        $property->setAccessible(true)->shouldBeCalled();
        $property->setValue($context, Argument::type('Symfony\Component\DependencyInjection\ContainerInterface'))->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }
}
