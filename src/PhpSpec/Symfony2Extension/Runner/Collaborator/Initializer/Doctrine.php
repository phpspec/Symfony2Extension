<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;
use Symfony\Component\HttpFoundation\ParameterBag;

class Doctrine implements InitializerInterface
{
    public function __construct(array $names = array())
    {
        $this->names = $names ?: array(
            'doctrine', 'em', 'om', 'dm', 'repository',
        );
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        switch ($name) {
            case 'doctrine':
                return $this->initDoctrine($collaborators, $className);
            default:
                return;
        }
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {
        if ($collaborators->has('em')) {
            $doctrine->getManager()->willReturn($collaborators->get('em'));
        }
    }

    public function supports($name)
    {
        return in_array($name, $this->names);
    }

    private function initDoctrine(CollaboratorManager $collaborators, $className)
    {
        $doctrine = $collaborators->get('doctrine');
        if (null === $className) {
            $doctrine->beADoubleOf('Doctrine\Common\Persistence\ManagerRegistry');
        }
    }
}
