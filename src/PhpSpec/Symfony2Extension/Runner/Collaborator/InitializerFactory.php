<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use Prophecy\Prophecy\ObjectProphecy;
use PhpSpec\Runner\CollaboratorManager;

class InitializerFactory implements FactoryInterface
{
    private $factory;
    private $initializers;
    private $commonCollaborators;

    public function __construct(FactoryInterface $factory, array $initializers = array(), array $commonCollaborators = array())
    {
        $this->factory = $factory;
        $this->initializers = $initializers;
        $this->commonCollaborators = $commonCollaborators;
    }

    public function create(CollaboratorManager $collaborators, ObjectProphecy $prophecy, $name, $className = null)
    {
        if (!$collaborators->has($name)) {
            $collaborator = $this->factory->create($collaborators, $prophecy, $name, $className);
            $collaborators->set($name, $collaborator);
        }
        else {
            $collaborator = $collaborators->get($name);
        }
        if ($initializer = $this->getInitializer($name)) {
            $initializer->initialize($collaborators, $name, $className);
        }

        return $collaborator;
    }

    function postInitialize(CollaboratorManager $collaborators)
    {
        foreach ($this->commonCollaborators as $name => $config) {
            if ($initializer = $this->getInitializer($name)) {
                $initializer->postInitialize($collaborators);
            }
        }
    }

    private function getInitializer($name)
    {
        foreach ($this->initializers as $initializer) {
            if ($initializer->supports($name)) {
                return $initializer;
            }
        }
    }
}
