<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_an_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }
}
