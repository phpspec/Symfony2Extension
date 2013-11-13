<?php

namespace PhpSpec\Symfony2Extension\Runner\Collaborator\Initializer;

use PhpSpec\Symfony2Extension\Runner\Collaborator\InitializerInterface;
use PhpSpec\Runner\CollaboratorManager;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Prophecy\Argument;

class Templating implements InitializerInterface
{
    private $name;

    public function __construct($name = 'templating')
    {
        $this->name = $name;
    }

    public function initialize(CollaboratorManager $collaborators, $name, $className = null)
    {
        $templating = $collaborators->get($name);
        if (null === $className) {
            $templating->beADoubleOf('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        }
        $templating->render(Argument::cetera())->willReturnArgument();
        $templating->renderResponse(Argument::cetera())->will(function($arguments) {
            return new Response($arguments[0]);
        });
    }

    public function postInitialize(CollaboratorManager $collaborators)
    {

    }

    public function supports($name)
    {
        return $this->name === $name;
    }
}
