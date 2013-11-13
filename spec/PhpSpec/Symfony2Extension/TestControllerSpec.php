<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    function it_renders_template_in_response()
    {
        $this->render('test')->shouldBeLike(new Response('test'));
    }

    function it_renders_view()
    {
        $this->renderView('test')->shouldReturn('test');
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
