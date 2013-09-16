<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerFactory;

class CommonCollaboratorsMaintainerSpec extends ObjectBehavior
{
    public function let(Unwrapper $unwrapper, InitializerFactory $factory)
    {
        $this->beConstructedWith($unwrapper, $factory, array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\Maintainer\CommonCollaboratorsMaintainer');
    }
}
