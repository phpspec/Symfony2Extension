<?php

namespace spec\PhpSpec\Symfony2Extension\Matcher;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RenderMatcherSpec extends ObjectBehavior
{
    function let(Unwrapper $unwrapper, PresenterInterface $presenter)
    {
        $this->beConstructedWith($unwrapper, $presenter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('PhpSpec\Symfony2Extension\Matcher\RenderMatcher');
    }

    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Matcher\MatcherInterface');
    }

    function it_have_priority_equal_to_150()
    {
        $this->getPriority()->shouldReturn(150);
    }

    function it_supports_the_render_matcher_name_only_for_subject_that_behave_like_controller(Controller $subject)
    {
        $this->supports('render', $subject, array())->shouldReturn(true);
    }
}
