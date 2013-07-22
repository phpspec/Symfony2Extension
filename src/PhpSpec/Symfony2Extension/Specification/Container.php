<?php

namespace PhpSpec\Symfony2Extension\Specification;

use PhpSpec\Wrapper\WrapperInterface;
use Symfony\Component\DependencyInjection\Container as BaseContainer;

class Container extends BaseContainer
{
    /**
     * @param string  $id
     * @param integer $invalidBehavior
     *
     * @return mixed
     */
    public function get($id, $invalidBehavior = self::EXCEPTION_ON_INVALID_REFERENCE)
    {
        $service = parent::get($id, $invalidBehavior);

        if ($service instanceof WrapperInterface) {
            return $service->getWrappedObject();
        }

        return $service;
    }
}