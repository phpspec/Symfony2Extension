<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\Runner\CollaboratorManager;

interface InitializerInterface
{
    /**
     * add common default behavior to a collaborator
     **/
    public function initialize(CollaboratorManager $collaborators, $name, $className = null);

    /**
     * add more behaviors with knowledge of other collaborators
     **/
    public function postInitialize(CollaboratorManager $collaborators);

    /**
     * return bool if supports the collaborator name
     *
     * Typically, the argument name of a spec method (ex: for $request, it would be 'request'
     **/
    public function supports($name);
}
