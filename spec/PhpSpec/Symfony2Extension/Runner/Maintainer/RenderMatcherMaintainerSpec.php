<?php

namespace spec\PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\ObjectBehavior;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;

class RenderMatcherMaintainerSpec extends ObjectBehavior
{
    function let(PresenterInterface $presenter, Unwrapper $unwrapper)
    {
        $this->beConstructedWith($presenter, $unwrapper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Runner\Maintainer\RenderMatcherMaintainer');
    }

    function it_is_a_maintainer()
    {
        $this->shouldHaveType('PhpSpec\Runner\Maintainer\MaintainerInterface');
    }

    function it_add_render_matcher_to_matchers_manager(ExampleNode $example, SpecificationInterface $context,
        MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $matchers->add(Argument::type('PhpSpec\Symfony2Extension\Matcher\RenderMatcher'))->shouldBeCalled();

        $this->prepare($example, $context, $matchers, $collaborators);
    }
}
