<?php

namespace spec\PhpSpec\Symfony2Extension\Runner;

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
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\CollaboratorFactory');
    }
}
