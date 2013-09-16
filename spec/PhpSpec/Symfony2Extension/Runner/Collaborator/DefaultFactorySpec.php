<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Prophecy\ObjectProphecy;

class DefaultFactorySpec extends ObjectBehavior
{
    function let(Unwrapper $unwrapper)
    {
        $this->beConstructedWith($unwrapper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\Collaborator\DefaultFactory');
    }

    function its_create_should_create_collaborators(ObjectProphecy $prophecy)
    {
        $this->create($prophecy, 'router', 'Symfony\Component\Routing\RouterInterface')->shouldHaveType('PhpSpec\Wrapper\Collaborator');
    }
}
