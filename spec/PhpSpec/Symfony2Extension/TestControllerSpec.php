<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TestControllerSpec extends ObjectBehavior
{
    public function it_generates_url($container, $router)
    {
        $this->setContainer($container);
        $router->generate('homepage', array(), false)->willReturn('/');
        $this->generateUrl('homepage')->shouldReturn('/');
    }
}
