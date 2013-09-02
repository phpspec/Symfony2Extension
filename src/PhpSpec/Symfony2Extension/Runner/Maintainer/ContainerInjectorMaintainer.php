<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;

class ContainerInjectorMaintainer extends ContainerMaintainer
{
    /**
     * @param ExampleNode            $example
     * @param SpecificationInterface $context
     * @param MatcherManager         $matchers
     * @param CollaboratorManager    $collaborators
     */
    public function prepare(ExampleNode $example, SpecificationInterface $context, MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $containerProperty = $example->getSpecification()->getClassReflection()->getProperty('container');
        $containerProperty->setAccessible(true);
        $context->setContainer($containerProperty->getValue($context));
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 5;
    }
}
