<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use Prophecy\Prophecy\ObjectProphecy;

interface FactoryInterface
{
    public function create(ObjectProphecy $prophecy, $name, $className = null, array $arguments = array());
}
