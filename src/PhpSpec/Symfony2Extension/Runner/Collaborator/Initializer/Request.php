<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;
use Symfony\Component\HttpFoundation\ParameterBag;

class Request implements InitializerInterface
{
    private $name;

    public function __construct($name = 'request')
    {
        $this->name = $name;
    }

    public function initialize(Collaborator $collaborator, $className, array $arguments)
    {
        if (null === $className) {
            $collaborator->beADoubleOf('Symfony\Component\HttpFoundation\Request');
        }
        $collaborator->attributes = new ParameterBag;
    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
