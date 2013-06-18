<?php

namespace PhpSpec\Symfony2Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

class Extension implements ExtensionInterface
{
    public function load(ServiceContainer $container)
    {
    }
}
