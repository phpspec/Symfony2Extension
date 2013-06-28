<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PhpSpec\Locator\PSR0\PSR0Locator;

class ControllerResourceSpec extends ObjectBehavior
{
    function let(PSR0Locator $locator)
    {
        $this->beConstructedWith(array('Acme', 'Bundle', 'DemoBundle', 'Controller', 'UserController'), $locator);
    }

    function it_is_a_psr0_resource()
    {
        $this->shouldHaveType('PhpSpec\Locator\PSR0\PSR0Resource');
    }
}
