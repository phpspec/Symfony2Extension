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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RenderMatcher implements MatcherInterface
{
    private static $ignoredProperties = array('file', 'line', 'string', 'trace', 'previous');
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
        return $name === 'render' && $subject instanceof Controller;
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

    public function verifyPositive(Controller $controller, $action, $templateName, $templateArgs = array())
    {
        $methodProphecy = new MethodProphecy($controller->get('templating')->getProphecy(), 'renderResponse', array(
            $templateName,
            $templateArgs
        ));

        $methodProphecy->shouldBeCalled();
        $controller->get('templating')->getProphecy()->addMethodProphecy($methodProphecy);
        $controller->$action();

        return true;
    }

    public function verifyNegative(Controller $controller, $action, $templateName, $templateArgs = array())
    {
        $methodProphecy = new MethodProphecy($controller->get('templating')->getProphecy(), 'renderResponse', Argument::type('array'));

        $methodProphecy->shouldNotBeCalled();
        $controller->get('templating')->getProphecy()->addMethodProphecy($methodProphecy);
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

                if (!is_array($arguments) || !isset($arguments[0]) || !is_string($arguments[0])) {
                    throw new MatcherException("Action name is required as a callable argument.");
                }

                $action = $arguments[0];

                if ($method !== 'duringAction') {
                    throw new MatcherException(sprintf(
                        "'Wrong callable name passed.\n".
                        "\"duringAction\" expected,\n".
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
}
