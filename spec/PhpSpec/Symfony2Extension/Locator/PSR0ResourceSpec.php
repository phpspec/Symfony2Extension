<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;

class PSR0ResourceSpec extends ObjectBehavior
{
    function it_is_a_locator_resource()
    {
        $this->shouldHaveType('PhpSpec\Locator\ResourceInterface');
    }
}
