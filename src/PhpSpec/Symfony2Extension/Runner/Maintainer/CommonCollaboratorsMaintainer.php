<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\Symfony2Extension\Runner\Collaborator\CollaboratorFactory;
use Prophecy\Prophet;
use PhpSpec\Wrapper\Unwrapper;

class CommonCollaboratorsMaintainer implements MaintainerInterface
{
    private $unwrapper;
    private $factory;
    private $commonCollaborators;
    private $commonServices;
    private $prophet;

    public function __construct(Unwrapper $unwrapper, CollaboratorFactory $factory, array $commonCollaborators = array(), array $commonServices = array())
    {
        // @TODO avoid indirect deps ?
        $this->unwrapper = $unwrapper;
        $this->factory = $factory;
        $this->commonCollaborators = $commonCollaborators ?: array(
            'router' => 'Symfony\Component\Routing\RouterInterface',
            'session' => 'Symfony\Component\HttpFoundation\Session\Session',
            'request' => 'Symfony\Component\HttpFoundation\Request',
            //'securityContext' => 'SecurityContextIterface',
        );

        $this->commonServices = $commonServices ?: array(
            'router' => 'router',
            'session' => 'session',
            'request' => 'request',
            //'securityContext' => 'security.context',
        );
    }

    public function supports(ExampleNode $example)
    {
        $specClassName = $example->getSpecification()->getClassReflection()->getName();

        return in_array('PhpSpec\Symfony2Extension\Specification\ControllerBehavior', class_parents($specClassName));
    }

    public function prepare(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prophet = new Prophet(null, $this->unwrapper, null);

        if (!$collaborators->has('container')) {
            $container = $this->factory->create(
                $this->prophet->prophesize(),
                'Symfony\Component\DependencyInjection\ContainerInterface'
            );
            $collaborators->set('container', $container);
        }

        foreach ($this->commonCollaborators as $name => $className) {
            if (!$collaborators->has($name)) {
                $collaborator = $this->factory->create($this->prophet->prophesize(), $className);
                $collaborators->set($name, $collaborator);

                if ($collaborators->has('container')) {
                    $collaborators->get('container')->get($this->commonServices[$name])->willReturn($collaborator);
                }
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
}
