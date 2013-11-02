<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;
use Symfony\Component\HttpFoundation\ParameterBag;

class Request implements InitializerInterface
{
    private $name;

    public function __construct($name = 'request')
    {
        $this->name = $name;
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        $request = $collaborators->get($name);
        if (null === $className) {
            $request->beADoubleOf('Symfony\Component\HttpFoundation\Request');
        }
        $request->attributes = new ParameterBag;
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {

    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
