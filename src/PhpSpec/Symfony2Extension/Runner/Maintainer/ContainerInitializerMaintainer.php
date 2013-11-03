<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Wrapper\Collaborator;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Prophet;

class ContainerInitializerMaintainer extends ContainerMaintainer
{
    /**
     * @var Unwrapper
     */
    private $unwrapper;

    /**
     * @var Prophet
     */
    private $prophet;

    /**
     * @param Unwrapper $unwrapper
     */
    public function __construct(Unwrapper $unwrapper)
    {
        $this->unwrapper = $unwrapper;
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prophet = new Prophet(null, $this->unwrapper, null);

        if (!$collaborators->has('container')) {
            $container = new Collaborator($this->prophet->prophesize());
            $container->beADoubleOf('Symfony\Component\DependencyInjection\ContainerInterface');
            $collaborators->set('container', $container);
        }

        $container = $collaborators->get('container');

        $containerProperty = $example->getSpecification()->getClassReflection()->getProperty('container');
        $containerProperty->setAccessible(true);
        $containerProperty->setValue($context, $container);
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $this->prophet->checkPredictions();
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 15;
    }
}
