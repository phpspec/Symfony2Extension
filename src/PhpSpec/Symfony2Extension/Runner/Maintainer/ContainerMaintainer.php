<?php


namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MaintainerInterface;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;

abstract class ContainerMaintainer implements MaintainerInterface
{
    /**
     * @param ExampleNode $example
     *
     * @return boolean
     */
    public function supports(ExampleNode $example)
    {
        $specClassName = $example->getSpecification()->getClassReflection()->getName();

        return in_array('PhpSpec\\Symfony2Extension\\Specification\\ControllerBehavior', class_parents($specClassName));
    }

    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function teardown(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
    }
}