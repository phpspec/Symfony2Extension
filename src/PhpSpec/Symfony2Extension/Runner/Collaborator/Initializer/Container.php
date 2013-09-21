<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;

class Container implements InitializerInterface
{
    private $name;
    private $commonCollaborators;

    public function __construct(array $commonCollaborators = array(), $name = 'container')
    {
        $this->commonCollaborators = $commonCollaborators;
        $this->name = $name;
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        $container = $collaborators->get($name);
        if (null === $className) {
            $container->beADoubleOf('Symfony\Component\DependencyInjection\ContainerInterface');
        }
        $container->has('service_container')->willReturn(true);
        $container->get('service_container')->willReturn($container);
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {
        foreach ($this->commonCollaborators as $name => $config) {
            list($id, $class) = $this->extractCollaboratorConfig($name, $config);

            $collaborators->get('container')->has($id)->willReturn(true);
            $collaborators->get('container')->get($id)->willReturn($collaborators->get($name));
        }
    }

    public function supports($name)
    {
        return $this->name === $name;
    }

    private function extractCollaboratorConfig($name, $config)
    {
        if (is_array($config)) {
            return each($config);
        }

        return array($name, $config);
    }
}
