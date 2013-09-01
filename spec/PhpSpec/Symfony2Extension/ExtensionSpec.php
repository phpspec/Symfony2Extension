<?php

namespace spec\PhpSpec\Symfony2Extension;

use PhpSpec\ObjectBehavior;
use PhpSpec\ServiceContainer;
use Prophecy\Argument;

class ExtensionSpec extends ObjectBehavior
{
    private $configurator;

    function it_is_a_phpspec_extension()
    {
        $this->shouldHaveType('PhpSpec\Extension\ExtensionInterface');
    }

    function it_adds_a_custom_locator_with_configuration(ServiceContainer $container)
    {
        $container->getParam('symfony2_locator')->willReturn(
            array(
                'namespace' => 'Acme',
                'spec_sub_namespace' => 'Specs',
                'src_path' => 'lib',
                'spec_paths' => array('lib/Acme/*/Specs')
            )
        );

        $container->addConfigurator($this->trackedConfigurator())->shouldBeCalled();
        $container->setShared(
            'locator.locators.symfony2_locator',
            $this->service('PhpSpec\Symfony2Extension\Locator\PSR0Locator', $container)
        )->shouldBeCalled();

        $this->load($container);
        $configurator = $this->configurator;
        $configurator($container->getWrappedObject());
    }

    private function trackedConfigurator()
    {
        $this->configurator = function () { throw new \LogicException('Configurator was not added'); };

        $configurator = &$this->configurator;

        return Argument::that(
            function ($c) use (&$configurator) {
                if (!is_callable($c)) {
                    return false;
                }

                $configurator = $c;

                return true;
            }
        );
    }

    private function service($class, ServiceContainer $container)
    {
        return Argument::that(
            function ($callback) use ($class, $container) {
                if (!is_callable($callback)) {
                    throw new \LogicException('Expected a callable to be set on the container');
                }

                $result = $callback($container->getWrappedObject());

                if (!$result instanceof $class) {
                    $message = sprintf('Expected the service to be an instance of "%s" but got: "%s"', $class, is_object($result) ? get_class($result) : gettype($result));

                    throw new \LogicException($message);
                }

                return true;
            }
        );
    }
}
