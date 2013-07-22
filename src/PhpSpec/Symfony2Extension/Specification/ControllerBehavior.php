<?php

namespace PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class ControllerBehavior extends ObjectBehavior
{
    /**
     * @var ContainerAwareInterface|null
     */
    protected $container = null;

    /**
     * @todo as soon as phpspec supports events, replace this method
     */
    public function let()
    {
        if ($this->getWrappedObject() instanceof ContainerAwareInterface) {
            $this->setContainer($this->container = new Container());
        }
    }
}
