<?php

namespace spec\PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\WrapperInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * We cannot use the WrapperInterface directly since getWrappedObject() cannot be stubbed.
 */
class Collaborator implements WrapperInterface
{
    private $wrappedObject = null;

    public function __construct($wrappedObject)
    {
        $this->wrappedObject = $wrappedObject;
    }

    public function getWrappedObject()
    {
        return $this->wrappedObject;
    }
}

class ContainerSpec extends ObjectBehavior
{
    function it_is_a_symfony_container()
    {
        $this->shouldHaveType('Symfony\Component\DependencyInjection\Container');
    }

    function it_unwraps_a_service_if_wrapped(Router $router)
    {
        $this->set('router', new Collaborator($router));

        $this->get('router')->shouldBe($router);
    }

    function it_returns_a_service_if_not_wrapped(Router $router)
    {
        $this->set('router', $router);

        $this->get('router')->shouldBe($router);
    }
}
