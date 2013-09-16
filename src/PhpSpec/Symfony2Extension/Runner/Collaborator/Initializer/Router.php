<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;
use Prophecy\Argument;

class Router implements InitializerInterface
{
    private $name;

    public function __construct($name = 'router')
    {
        $this->name = $name;
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        $router = $collaborators->get($name);
        if (null === $className) {
            $router->beADoubleOf('Symfony\Component\Routing\RouterInterface');
        }
        $router->generate(Argument::cetera())->willReturnArgument();
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {

    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
