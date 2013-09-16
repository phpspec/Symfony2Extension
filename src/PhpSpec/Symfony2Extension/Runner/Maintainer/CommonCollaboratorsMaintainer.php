<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\SpecificationInterface;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use Prophecy\Prophet;
use PhpSpec\Wrapper\Unwrapper;
use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerFactory;

class CommonCollaboratorsMaintainer implements MaintainerInterface
{
    private $unwrapper;
    private $factory;
    private $commonCollaborators;
    private $prophet;

    public function __construct(Unwrapper $unwrapper, InitializerFactory $factory, array $commonCollaborators = array())
    {
        $this->unwrapper = $unwrapper; // @TODO avoid indirect deps
        $this->factory = $factory;

        $this->commonCollaborators = $commonCollaborators;
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

        foreach ($this->commonCollaborators as $name => $service) {
            list($id, $class) = $this->extractCollaboratorConfig($name, $service);

            $collaborator = $this->factory->create(
                $collaborators,
                $this->prophet->prophesize(),
                $name,
                $class
            );
        }
        $this->factory->postInitialize($collaborators, array_keys($this->commonCollaborators));
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
