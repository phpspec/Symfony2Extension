<?php

use Behat\Behat\Context\BehatContext;

class FeatureContext extends BehatContext
{
    public function __construct(array $parameters)
    {
        $this->useContext('phpspec', new PhpSpecContext());
    }
}
