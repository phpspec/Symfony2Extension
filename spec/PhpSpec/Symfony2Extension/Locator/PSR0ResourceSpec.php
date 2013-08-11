<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;

class PSR0ResourceSpec extends ObjectBehavior
{
    private $namespaceParts = array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User');

    private $srcPath = '/home/user/myproject/src/';

    private $specSuffix = 'Spec';

    function let()
    {
        $this->beConstructedWith($this->namespaceParts, $this->srcPath, $this->specSuffix);
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

    function it_generates_the_src_filename_from_provided_parts()
    {
        $srcFilename = $this->srcPath
            .'Acme'.DIRECTORY_SEPARATOR
            .'Bundle'.DIRECTORY_SEPARATOR
            .'DemoBundle'.DIRECTORY_SEPARATOR
            .'Model'.DIRECTORY_SEPARATOR
            .'User.php';

        $this->getSrcFilename()->shouldReturn($srcFilename);
    }

    function it_generates_the_src_namespace_from_provided_parts()
    {
        $this->getSrcNamespace()->shouldReturn('Acme\\Bundle\\DemoBundle\\Model');
    }

    function it_generates_a_proper_src_namespace_even_if_there_is_only_one_part()
    {
        $this->beConstructedWith(array('User'), 'src');

        $this->getSrcNamespace()->shouldReturn('');
    }

    function it_generates_the_src_classname_from_provided_parts()
    {
        $this->getSrcClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Model\\User');
    }

    function it_generates_a_bundle_spec_filename_from_provided_parts_using_the_spec_suffix()
    {
        $specFilename = $this->srcPath
            .'Acme'.DIRECTORY_SEPARATOR
            .'Bundle'.DIRECTORY_SEPARATOR
            .'DemoBundle'.DIRECTORY_SEPARATOR
            .$this->specSuffix.DIRECTORY_SEPARATOR
            .'Model'.DIRECTORY_SEPARATOR
            .'UserSpec.php';

        $this->getSpecFilename()->shouldReturn($specFilename);
    }

    function it_generates_a_regular_spec_filename_from_provided_parts_using_the_spec_suffix()
    {
        $this->beConstructedWith(array('Acme', 'Model', 'User'), $this->srcPath, $this->specSuffix);

        $specFilename = $this->srcPath
            .'Acme'.DIRECTORY_SEPARATOR
            .'Model'.DIRECTORY_SEPARATOR
            .$this->specSuffix.DIRECTORY_SEPARATOR
            .'UserSpec.php';

        $this->getSpecFilename()->shouldReturn($specFilename);
    }

    function it_generates_a_spec_filename_from_a_single_part()
    {
        $this->beConstructedWith(array('User'), $this->srcPath, $this->specSuffix);

        $specFilename = $this->srcPath
            .$this->specSuffix.DIRECTORY_SEPARATOR
            .'UserSpec.php';

        $this->getSpecFilename()->shouldReturn($specFilename);
    }

    function it_generates_a_bundle_spec_namespace_from_provided_parts()
    {
        $this->getSpecNamespace()->shouldReturn('Acme\\Bundle\\DemoBundle\\Spec\\Model');
    }

    function it_generates_a_regular_spec_namespace_from_provided_parts()
    {
        $this->beConstructedWith(array('Acme', 'Model', 'User'), $this->srcPath, $this->specSuffix);

        $this->getSpecNamespace()->shouldReturn('Acme\\Model\\Spec');
    }

    function it_generates_a_proper_spec_namespace_even_if_there_is_only_one_part()
    {
        $this->beConstructedWith(array('User'), $this->srcPath, $this->specSuffix);

        $this->getSpecNamespace()->shouldReturn('Spec');
    }

    function it_generates_a_spec_classname_from_provided_parts()
    {
        $this->getSpecClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Spec\\Model\\UserSpec');
    }
}
