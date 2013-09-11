<?php

namespace spec\PhpSpec\Symfony2Extension\Matcher;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserController extends ContainerAware
{
    public function listAction()
    {
        return $this->container->get('templating')->renderResponse(
            'Scenario7UserBundle:User:list.html.twig', array('users' => array())
        );
    }

    public function dummyAction()
    {
    }
}

class CustomUserController implements ContainerAwareInterface
{
    protected $c;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->c = $container;
    }

    public function listAction()
    {
        return $this->c->get('templating')->renderResponse(
            'Scenario7UserBundle:User:list.html.twig', array('users' => array())
        );
    }

    public function dummyAction()
    {
    }
}

class RenderMatcherSpec extends ObjectBehavior
{
    function let(Unwrapper $unwrapper, PresenterInterface $presenter)
    {
        $this->beConstructedWith($unwrapper, $presenter);
    }

    function it_is_a_matcher()
    {
        $this->shouldBeAnInstanceOf('PhpSpec\Matcher\MatcherInterface');
    }

    function it_have_priority_lower_than_other_phpspec_matchers()
    {
        $this->getPriority()->shouldReturn(150);
    }

    function it_supports_the_render_matcher_name_only_for_subject_that_implements_container_aware_interface(ContainerAware $subject)
    {
        $this->supports('render', $subject, array())->shouldReturn(true);
    }

    function it_verify_controller_with_container_under_protected_container_property(Container $container, EngineInterface $templating)
    {
        $container->get('templating')->shouldBeCalled()->willReturn($templating->getWrappedObject());

        $controller = new UserController();
        $controller->setContainer($container->getWrappedObject());

        $this->verifyPositive($controller, 'listAction', 'Scenario7UserBundle:User:list.html.twig', array('users' => array()))
            ->shouldReturn(true);
        $this->verifyNegative($controller, 'dummyAction', 'Scenario7UserBundlistser:dummy.html.twig', array('users' => array()))
            ->shouldReturn(true);
    }

    function it_verify_controller_with_container_under_protected_custom_property(Container $container, EngineInterface $templating)
    {
        $container->get('templating')->shouldBeCalled()->willReturn($templating->getWrappedObject());

        $controller = new CustomUserController();
        $controller->setContainer($container->getWrappedObject());

        $this->verifyPositive($controller, 'listAction', 'Scenario7UserBundle:User:list.html.twig', array('users' => array()))
            ->shouldReturn(true);
        $this->verifyNegative($controller, 'dummyAction', 'Scenario7UserBundle:User:dummy.html.twig', array('users' => array()))
            ->shouldReturn(true);
    }

    function it_throw_exception_when_controller_does_not_have_container_during_verify_positive()
    {
        $controller = new UserController();

        $this->shouldThrow(new \RuntimeException('UserController does not have a container'))
            ->duringVerifyPositive($controller, 'listAction', 'Scenario7UserBundle:User:list.html.twig', array('users' => array()));

        $this->shouldThrow(new \RuntimeException('UserController does not have a container'))
            ->duringVerifyNegative($controller, 'dummyAction', 'Scenario7UserBundle:User:list.html.twig', array('users' => array()));
    }
}
