<?php

namespace PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Specification\Container;
use PhpSpec\Wrapper\Subject;

class ControllerBehavior extends ObjectBehavior
{
    /**
     * @var Container|null
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function setContainer(Container $container)
    {
        $this->container = $container;

        $this->object->setContainer($container);
    }
}
