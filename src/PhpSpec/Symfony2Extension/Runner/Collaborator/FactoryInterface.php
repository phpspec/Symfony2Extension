<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use Prophecy\Prophecy\ObjectProphecy;
use PhpSpec\Runner\CollaboratorManager;

interface FactoryInterface
{
    public function create(CollaboratorManager $collaborators, ObjectProphecy $prophecy, $name, $className = null);
}
