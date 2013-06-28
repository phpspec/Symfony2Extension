<?php

namespace spec\PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ControllerBehaviorSpec extends ObjectBehavior
{
    function it_is_an_object_behavior()
    {
        $this->shouldHaveType('PhpSpec\ObjectBehavior');
    }
}
