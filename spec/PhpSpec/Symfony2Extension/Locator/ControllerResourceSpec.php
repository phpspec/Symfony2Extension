<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerResourceSpec extends ObjectBehavior
{
    function it_is_a_psr0_resource()
    {
        $this->beConstructedWith(array('Acme', 'DemoBundle', 'Controller', 'UserController'));

        $this->shouldHaveType('PhpSpec\Symfony2Extension\Locator\PSR0Resource');
    }
}
