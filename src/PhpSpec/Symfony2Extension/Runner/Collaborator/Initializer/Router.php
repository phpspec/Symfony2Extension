<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Argument;

class Router implements InitializerInterface
{
    private $name;

    public function __construct($name = 'router')
    {
        $this->name = $name;
    }

    public function initialize(Collaborator $collaborator, $className, array $arguments)
    {
        if (null === $className) {
            $collaborator->beADoubleOf('Symfony\Component\Routing\RouterInterface');
        }
        $collaborator->generate(Argument::cetera())->willReturnArgument();
    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
