<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\Locator\ResourceLocatorInterface;
use PhpSpec\ObjectBehavior;

class PSR0ResourceSpec extends ObjectBehavior
{
    function let(ResourceLocatorInterface $locator)
    {
        $this->beConstructedWith(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), $locator);
    }

    function it_is_a_locator_resource()
    {
        $this->shouldHaveType('PhpSpec\Locator\ResourceInterface');
    }

    function it_uses_the_last_segment_as_a_name()
    {
        $this->getName()->shouldReturn('User');
    }

    function it_uses_the_last_segment_plus_Spec_suffix_as_a_specName()
    {
        $this->getSpecName()->shouldReturn('UserSpec');
    }
}
