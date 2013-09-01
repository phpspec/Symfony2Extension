<?php

namespace spec\PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\WrapperInterface;
use Prophecy\Argument;

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

    function it_unwraps_a_service_if_wrapped(\stdClass $service)
    {
        $this->set('my_service', new Collaborator($service));

        $this->get('my_service')->shouldBe($service);
    }

    function it_returns_a_service_if_not_wrapped(\stdClass $service)
    {
        $this->set('my_service', $service);

        $this->get('my_service')->shouldBe($service);
    }
}
