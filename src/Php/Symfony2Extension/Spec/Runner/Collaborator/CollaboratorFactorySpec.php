<?php

namespace spec\Symfony2Extension\Spec\Runner\Collaborator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;

class CollaboratorFactorySpec extends ObjectBehavior
{
    function let(Unwrapper $unwrapper)
    {
        $this->beConstructedWith($unwrapper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Php\Symfony2Extension\Runner\Collaborator\CollaboratorFactory');
    }
}
