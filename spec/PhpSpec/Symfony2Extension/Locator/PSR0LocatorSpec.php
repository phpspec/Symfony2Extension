<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;

class PSR0LocatorSpec extends ObjectBehavior
{
    function it_is_a_locator()
    {
        $this->shouldHaveType('PhpSpec\Locator\ResourceLocatorInterface');
    }

    function it_converts_the_fullSrcPath_to_an_absolute_path()
    {
        $this->beConstructedWith('Acme\Bundle', dirname(__DIR__));

        $this->getFullSrcPath()->shouldReturn(
            dirname(__DIR__).DIRECTORY_SEPARATOR.'Acme'.DIRECTORY_SEPARATOR.'Bundle'.DIRECTORY_SEPARATOR
        );
    }

    function it_generates_a_proper_fullSrcPath_even_with_an_empty_namespace()
    {
        $this->beConstructedWith('', dirname(__DIR__));

        $this->getFullSrcPath()->shouldReturn(dirname(__DIR__).DIRECTORY_SEPARATOR);
    }

    function it_exposes_the_srcNamespace_it_was_constructed_with()
    {
        $this->beConstructedWith('Acme\\Bundle');

        $this->getSrcNamespace()->shouldReturn('Acme\\Bundle\\');
    }

    function it_trims_the_srcNamespace()
    {
        $this->beConstructedWith('\\Acme\\Bundle\\');

        $this->getSrcNamespace()->shouldReturn('Acme\\Bundle\\');
    }

    function it_supports_an_empty_namespace_argument()
    {
        $this->beConstructedWith('');

        $this->getSrcNamespace()->shouldReturn('');
    }
}
