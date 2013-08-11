<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;

class ExtensionSpec extends ObjectBehavior
{
    function it_is_a_phpspec_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }
}
