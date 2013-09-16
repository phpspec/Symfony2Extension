<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class Session implements InitializerInterface
{
    private $name;

    public function __construct($name = 'session')
    {
        $this->name = $name;
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        $session = $collaborators->get($name);
        if (null === $className) {
            $session->beADoubleOf('Symfony\Component\HttpFoundation\Session\Session');
        }

        $session->getFlashBag()->willReturn(new FlashBag);
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {
        if ($collaborators->has('request')) {
            $collaborators->get('request')->getSession()->willReturn($collaborators->get($this->name));
        }
    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
