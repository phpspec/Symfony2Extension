<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class TestControllerSpec extends ObjectBehavior
{
    public function it_generates_url($container, $router, $request, $doctrine)
    {
        $this->setContainer($container);
        $doctrine->getManager();
        $this->generateUrl('homepage')->shouldReturn('homepage');
    }
}
