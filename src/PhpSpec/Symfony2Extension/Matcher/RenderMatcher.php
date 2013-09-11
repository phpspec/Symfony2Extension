<?php

namespace PhpSpec\Symfony2Extension\Matcher;

use PhpSpec\Exception\Example\MatcherException;
use PhpSpec\Factory\ReflectionFactory;
use PhpSpec\Formatter\Presenter\PresenterInterface;
use PhpSpec\Matcher\MatcherInterface;
use PhpSpec\Wrapper\DelayedCall;
use PhpSpec\Wrapper\Unwrapper;
use Prophecy\Argument;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use Prophecy\Prophecy\MethodProphecy;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RenderMatcher implements MatcherInterface
{
    private $unwrapper;
    private $presenter;

    public function __construct(Unwrapper $unwrapper, PresenterInterface $presenter, ReflectionFactory $factory = null)
    {
        $this->unwrapper = $unwrapper;
        $this->presenter = $presenter;
        $this->factory   = $factory ?: new ReflectionFactory;
    }

    public function supports($name, $subject, array $arguments)
    {
        return $name === 'render' && $subject instanceof ContainerAwareInterface;
    }

    public function positiveMatch($name, $subject, array $arguments)
    {
        return $this->getDelayedCall(array($this, 'verifyPositive'), $subject, $arguments);
    }

    public function negativeMatch($name, $subject, array $arguments)
    {
        return $this->getDelayedCall(array($this, 'verifyNegative'), $subject, $arguments);
    }

    public function getPriority()
    {
        return 150;
    }

    public function verifyPositive(ContainerAwareInterface $controller, $action, $templateName, $templateArgs = array())
    {
        $container = $this->getContainer($controller);

        $methodProphecy = new MethodProphecy($container->get('templating')->getProphecy(), 'renderResponse', array(
            $templateName,
            $templateArgs
        ));

        $methodProphecy->shouldBeCalled();
        $container->get('templating')->getProphecy()->addMethodProphecy($methodProphecy);
        $controller->$action();

        return true;
    }

    public function verifyNegative(ContainerAwareInterface $controller, $action, $templateName, $templateArgs = array())
    {
        $container = $this->getContainer($controller);

        $methodProphecy = new MethodProphecy($container->get('templating')->getProphecy(), 'renderResponse', array(
            $templateName,
            $templateArgs
        ));

        $methodProphecy->shouldNotBeCalled();
        $container->get('templating')->getProphecy()->addMethodProphecy($methodProphecy);
        $controller->$action();

        return true;
    }

    private function getDelayedCall($check, $subject, array $arguments)
    {
        $template = $this->getTemplate($arguments);
        $templateArgs = $this->getTemplateArguments($arguments);
        $unwrapper = $this->unwrapper;

        return new DelayedCall(
            function ($method, $arguments) use($check, $subject, $template, $templateArgs, $unwrapper) {
                $arguments = $unwrapper->unwrapAll($arguments);

                if (preg_match('/^during(.+)Action$/', $method, $matches)) {
                    $action = lcfirst($matches[1]);
                } else {
                    throw new MatcherException(sprintf(
                        "'Wrong callable name passed.\n".
                        "Name that match \"/^during(.+)Action$/'\" regex expected,\n".
                        "Got %s.",
                        $method
                    ));
                }

                $methodName = $action . 'Action';

                if (!method_exists($subject, $methodName) && !method_exists($subject, '__call')) {
                    throw new MethodNotFoundException(
                        sprintf('Method %s::%s not found.', get_class($subject), $methodName),
                        $subject, $methodName, $arguments
                    );
                }

                return call_user_func($check, $subject, $methodName, $template, $templateArgs);
            }
        );
    }

    private function getTemplate(array $arguments)
    {
        if (is_string($arguments[0])) {
            return $arguments[0];
        }

        throw new MatcherException(sprintf(
            "Wrong argument provided in render matcher.\n".
            "template name expected,\n".
            "Got %s.",
            $this->presenter->presentValue($arguments[0])
        ));
    }

    private function getTemplateArguments(array $arguments)
    {
        if (is_array($arguments[1])) {
            return $arguments[1];
        }

        throw new MatcherException(sprintf(
            "Wrong argument provided in render matcher.\n".
            "template name expected,\n".
            "Got %s.",
            $this->presenter->presentValue($arguments[1])
        ));
    }

    private function getContainer(ContainerAwareInterface $controller)
    {
        $reflection = new \ReflectionObject($controller);
        $container = null;

        if ($reflection->hasProperty('container')) {
            $containerProperty = $reflection->getProperty('container');

            $isPublic = $containerProperty->isPublic();
            $containerProperty->setAccessible(true);
            $container = $containerProperty->getValue($controller);
            $containerProperty->setAccessible($isPublic);
        }

        if (!isset($container) || !$container instanceof ContainerInterface) {
            $properies = $reflection->getProperties();

            foreach ($properies as $property) {
                $isPublic = $property->isPublic();
                $property->setAccessible(true);
                $container = $property->getValue($controller);
                $property->setAccessible($isPublic);

                if (isset($container) && $container instanceof ContainerInterface) {
                    break;
                }

                $container = null;
            }
        }

        if (!isset($container) || !$container instanceof ContainerInterface) {
            throw new \RuntimeException(sprintf('%s does not have a container', $reflection->getShortName()));
        }

        return $container;
    }
}
