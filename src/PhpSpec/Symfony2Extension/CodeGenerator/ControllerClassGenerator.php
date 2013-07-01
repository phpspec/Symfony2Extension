<?php

namespace PhpSpec\Symfony2Extension\CodeGenerator;

use PhpSpec\CodeGenerator\Generator\ClassGenerator;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Symfony2Extension\Locator\ControllerResource;

class ControllerClassGenerator extends ClassGenerator
{
    /**
     * @param ResourceInterface $resource
     * @param string            $generation
     * @param array             $data
     *
     * @return boolean
     */
    public function supports(ResourceInterface $resource, $generation, array $data)
    {
        return 'class' === $generation && $resource instanceof ControllerResource;
    }

    /**
     * @return integer
     */
    public function getPriority()
    {
        return 10;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__);
    }
}
__halt_compiler();<?php%namespace_block%

use Symfony\Component\DependencyInjection\ContainerAware;

class %name% extends ContainerAware
{
}
