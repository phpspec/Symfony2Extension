<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use Prophecy\Prophecy\ObjectProphecy;

class InitializerFactory implements FactoryInterface
{
    private $factory;
    private $initializers;

    public function __construct(FactoryInterface $factory, array $initializers = array())
    {
        $this->factory = $factory;
        $this->initializers = $initializers;
    }

    public function create(ObjectProphecy $prophecy, $name, $className = null, array $arguments = array())
    {
        $collaborator = $this->factory->create($prophecy, $name, $className, $arguments);
        if ($initializer = $this->getInitializer($name)) {
            $initializer->initialize($collaborator, $className, $arguments);
        }

        return $collaborator;
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
