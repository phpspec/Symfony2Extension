<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Prophecy\ObjectProphecy;
use PhpSpec\Runner\CollaboratorManager;

class DefaultFactory implements FactoryInterface
{
    private $unwrapper;

    public function __construct(Unwrapper $unwrapper)
    {
        $this->unwrapper = $unwrapper;
    }

    public function create(CollaboratorManager $collaborators, ObjectProphecy $prophecy, $name, $className = null)
    {
        $collaborator = new Collaborator($prophecy, $this->unwrapper);
        if (null !== $className) {
            $collaborator->beADoubleOf($className);
        }

        return $collaborator;
    }
}
