<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator;

use PhpSpec\Wrapper\Collaborator;

interface InitializerInterface
{
    /**
     * add common default behavior to a collaborator
     **/
    public function initialize(Collaborator $collaborator, $className, array $arguments);

    /**
     * return bool if supports the collaborator name
     *
     * Typically, the argument name of a spec method (ex: for $request, it would be 'request'
     **/
    public function supports($name);
}
