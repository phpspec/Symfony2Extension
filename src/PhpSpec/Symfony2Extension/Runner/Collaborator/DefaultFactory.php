<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Wrapper\Collaborator;
use Prophecy\Prophecy\ObjectProphecy;

class DefaultFactory implements FactoryInterface
{
    private $unwrapper;

    public function __construct(Unwrapper $unwrapper)
    {
        $this->unwrapper = $unwrapper;
    }

    public function create(ObjectProphecy $prophecy, $name, $className = null, array $arguments = array())
    {
        $collaborator = new Collaborator($prophecy, $this->unwrapper);
        if (null !== $className) {
            $collaborator->beADoubleOf($className);
        }
        if (!empty($arguments)) {
            $collaborator->beConstructedWith($arguments);
        }

        return $collaborator;
    }
}
