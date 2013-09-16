<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Wrapper\Collaborator;
use Symfony\Component\HttpFoundation\ParameterBag;

class Doctrine implements InitializerInterface
{
    public function __construct(array $names = array())
    {
        $this->names = $names ?: array(
            'doctrine', 'em', 'om', 'dm', 'repository',
        );
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
        return in_array($name, $this->names);
    }
}
