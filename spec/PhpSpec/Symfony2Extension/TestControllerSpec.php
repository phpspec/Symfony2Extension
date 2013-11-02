<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class TestControllerSpec extends ObjectBehavior
{
    public function let($container)
    {
        $this->setContainer($container);
    }

    public function it_generates_url()
    {
        $this->generateUrl('homepage')->shouldReturn('homepage');
    }

    public function it_flushes()
    {
        $this->flush();
    }

    public function it_finds()
    {
        $this->find()->shouldReturn(array());
    }
}
