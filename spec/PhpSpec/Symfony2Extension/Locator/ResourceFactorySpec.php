<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceFactorySpec extends ObjectBehavior
{
    function it_creates_a_psr0_resource_by_default()
    {
        $this->create(array('Acme', 'Model', 'User'), 'Spec', 'src')
            ->shouldReturnResource('PhpSpec\\Symfony2Extension\\Locator\\PSR0Resource', 'Acme\\Model\\User');
    }

    function it_creates_a_psr0_resource_for_short_namespaces()
    {
        $this->create(array('Acme', 'User'))
            ->shouldReturnResource('PhpSpec\\Symfony2Extension\\Locator\\PSR0Resource', 'Acme\\User');
        $this->create(array('User'))
            ->shouldReturnResource('PhpSpec\\Symfony2Extension\\Locator\\PSR0Resource', 'User');
    }

    function it_creates_a_controller_resource_for_a_bundle_controller()
    {
        $this->create(array('Acme', 'DemoBundle', 'Controller', 'UserController'), 'Spec', 'src')
            ->shouldReturnResource(
                'PhpSpec\\Symfony2Extension\\Locator\\ControllerResource',
                'Acme\\DemoBundle\\Controller\\UserController'
            );
    }

    function it_creates_a_psr0_resource_for_a__controller_in_a_non_standard_location()
    {
        $resource = $this->create(array('Acme', 'DemoBundle', 'Model', 'UserController'), 'Spec', 'src');
        $resource->shouldNotBeAnInstanceOf('PhpSpec\\Symfony2Extension\\Locator\\ControllerResource');
        $resource->shouldBeResource(
            'PhpSpec\\Symfony2Extension\\Locator\\PSR0Resource',
            'Acme\\DemoBundle\\Model\\UserController'
        );
    }

    public function getMatchers()
    {
        return array(
            'returnResource' => array($this, 'shouldReturnResource'),
            'beResource' => array($this, 'shouldReturnResource')
        );
    }

    public function shouldReturnResource($subject, $resourceNamespace, $srcClassname)
    {
        if (!$subject instanceof $resourceNamespace || $subject->getSrcClassname() !== $srcClassname) {
            $message = sprintf(
                'Expected a "%s" (%s) resource but got %s (%s)',
                $srcClassname,
                $resourceNamespace,
                $subject->getSrcClassname(),
                get_class($subject)
            );

            throw new \LogicException($message);
        }

        return true;
    }
}
