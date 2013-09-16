<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Symfony2Extension\Runner\Collaborator\FactoryInterface;
use Prophecy\Prophet;
use PhpSpec\Wrapper\Unwrapper;

class CommonCollaboratorsMaintainer implements MaintainerInterface
{
    private $unwrapper;
    private $factory;
    private $commonCollaborators;
    private $prophet;

    public function __construct(Unwrapper $unwrapper, FactoryInterface $factory, array $commonCollaborators = array())
    {
        // @TODO avoid indirect deps ?
        $this->unwrapper = $unwrapper;
        $this->factory = $factory;

        // @TODO should we ? and/or via extension config ?
        $this->commonCollaborators = $commonCollaborators ?: array(
            'router' => 'Symfony\Component\Routing\RouterInterface',
            'session' => 'Symfony\Component\HttpFoundation\Session\Session',
            'request' => 'Symfony\Component\HttpFoundation\Request',
            //'securityContext' => 'SecurityContextIterface',
        );
    }

    public function supports(ExampleNode $example)
    {
        $class = $example->getSpecification()->getResource()->getSrcClassname();
        try {
            $srcRefl = new \ReflectionClass($class);

            return $srcRefl->implementsInterface('Symfony\Component\DependencyInjection\ContainerAwareInterface');
        }
        catch(\Exception $e) {
            return false;
        }
    }

    public function prepare(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prophet = new Prophet(null, $this->unwrapper, null);

        if (!$collaborators->has('container')) {
            $container = $this->factory->create(
                $this->prophet->prophesize(),
                'container',
                'Symfony\Component\DependencyInjection\ContainerInterface'
            );
            $collaborators->set('container', $container);
        }

        foreach ($this->commonCollaborators as $name => $service) {
            list($id, $class) = $this->extractCollaboratorConfig($name, $service);
            if (!$collaborators->has($name)) {
                $collaborator = $this->factory->create($this->prophet->prophesize(), $name, $class);
                $collaborators->set($name, $collaborator);

                $collaborators->get('container')->get($id)->willReturn($collaborator);
            }
        }
    }

    public function teardown(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prophet->checkPredictions();
    }

    public function getPriority()
    {
        return 60; // more than CollaboratorsMaintainer :/
    }

    private function extractCollaboratorConfig($name, $config)
    {
        if (is_array($config)) {
            return each($config);
        }

        return array($name, $config);
    }
}
