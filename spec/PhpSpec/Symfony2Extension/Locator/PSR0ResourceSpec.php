<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Locator\PSR0Locator as Locator;

class PSR0ResourceSpec extends ObjectBehavior
{
    function let(Locator $locator)
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

    function it_generates_the_src_filename_from_provided_parts_using_the_locator(Locator $locator)
    {
        $srcPath = '/home/jzalas/myproject/src/';
        $srcFilename = $srcPath
            .'Acme'.DIRECTORY_SEPARATOR
            .'Bundle'.DIRECTORY_SEPARATOR
            .'DemoBundle'.DIRECTORY_SEPARATOR
            .'Model'.DIRECTORY_SEPARATOR
            .'User.php';

        $locator->getFullSrcPath()->willReturn($srcPath);

        $this->getSrcFilename()->shouldReturn($srcFilename);
    }

    function it_generates_the_src_namespace_from_provided_parts_using_the_locator(Locator $locator)
    {
        $locator->getSrcNamespace()->willReturn('Local\\');

        $this->getSrcNamespace()->shouldReturn('Local\\Acme\\Bundle\\DemoBundle\\Model');
    }

    function it_generates_proper_src_namespace_even_if_there_is_only_one_part(Locator $locator)
    {
        $this->beConstructedWith(array('config'), $locator);

        $locator->getSrcNamespace()->willReturn('Local\\');

        $this->getSrcNamespace()->shouldReturn('Local');
    }
}
