<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Symfony2Extension\Runner\Collaborator\FactoryInterface;
use Prophecy\Prophecy\ObjectProphecy;
use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;

class InitializerFactorySpec extends ObjectBehavior
{
    private $closure;

    function let(FactoryInterface $factory, Collaborator $collaborator, InitializerInterface $initializer)
    {
        $this->beConstructedWith($factory, array(
            $initializer,
        ));
        $factory->create(Argument::cetera())->willReturn($collaborator);
    }

    function its_create_should_initialize_known_collaborators(ObjectProphecy $prophecy, $collaborator, $initializer)
    {
        $initializer->supports('router')->willReturn(true);
        $initializer->initialize(Argument::cetera())->shouldBeCalled();
        $this->create($prophecy, 'router');
    }

    function its_create_should_not_initialize_unknown_collaborators(ObjectProphecy $prophecy, $collaborator, $initializer)
    {
        $initializer->supports('request')->willReturn(false);
        $initializer->initialize(Argument::cetera())->shouldNotBeCalled();
        $this->create($prophecy, 'request');
    }
}
