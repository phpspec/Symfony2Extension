<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;
use Symfony\Component\HttpFoundation\ParameterBag;

class Templating implements InitializerInterface
{
    private $name;

    public function __construct($name = 'templating')
    {
        $this->name = $name;
    }

    public function initialize(Collaborator $collaborator, $className, array $arguments)
    {
        if (null === $className) {
            $collaborator->beADoubleOf('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        }
        $collaborator->renderResponse(Argument::cetera())->willReturnArgument();
    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
