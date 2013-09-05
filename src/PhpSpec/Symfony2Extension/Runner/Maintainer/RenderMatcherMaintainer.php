<?php

namespace PhpSpec\Symfony2Extension\Runner\Maintainer;

use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Loader\Node\ExampleNode;
use PhpSpec\Matcher;
use PhpSpec\Runner\CollaboratorManager;
use PhpSpec\Runner\Maintainer\MatchersMaintainer;
use PhpSpec\Runner\MatcherManager;
use PhpSpec\SpecificationInterface;
use PhpSpec\Symfony2Extension\Matcher\RenderMatcher;
use PhpSpec\Wrapper\Unwrapper;

class RenderMatcherMaintainer extends MatchersMaintainer
{
    private $presenter;
    private $unwrapper;

    public function __construct(PresenterInterface $presenter, Unwrapper $unwrapper)
    {
        $this->presenter = $presenter;
        $this->unwrapper = $unwrapper;
    }

    public function prepare(ExampleNode $example, SpecificationInterface $context,
                            MatcherManager $matchers, CollaboratorManager $collaborators)
    {
        $matchers->add(new RenderMatcher($this->unwrapper, $this->presenter));
    }
}