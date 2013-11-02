<?php

namespace PhpSpec\Symfony2Extension\Specification;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Subject;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ControllerBehavior extends ObjectBehavior
{
    /**
     * @var ContainerInterface|null
     */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;

        $this->object->setContainer($container);
    }
}
