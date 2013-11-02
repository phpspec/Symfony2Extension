<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;
use Prophecy\Argument;

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
            case 'em':
            case 'om':
            case 'dm':
                return $this->initManager($collaborators, $className);
            case 'repository':
                return $this->initRepository($collaborators, $className);
            default:
                return;
        }
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {
        if ($collaborators->has('em')) {
            $collaborators->get('doctrine')->getManager()->willReturn($collaborators->get('em'));
        }
        if ($collaborators->has('repository')) {
            $collaborators->get('doctrine')
                ->getRepository(Argument::type('string'))
                ->willReturn($collaborators->get('repository'))
            ;
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

    private function initManager(CollaboratorManager $collaborators, $className)
    {
        $em = $collaborators->get('em');
        if (null === $className) {
            $em->beADoubleOf('Doctrine\Common\Persistence\ObjectManager');
        }
    }

    private function initRepository(CollaboratorManager $collaborators, $className)
    {
        $repository = $collaborators->get('repository');
        if (null === $className) {
            $repository->beADoubleOf('Doctrine\Common\Persistence\ObjectRepository');
        }

        $repository->findAll()->willReturn(array());
    }
}
