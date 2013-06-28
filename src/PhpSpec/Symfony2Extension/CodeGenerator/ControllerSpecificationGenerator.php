<?php

namespace PhpSpec\Symfony2Extension\CodeGenerator;

use PhpSpec\CodeGenerator\Generator\GeneratorInterface;
use PhpSpec\Locator\ResourceInterface;
use PhpSpec\Symfony2Extension\Locator\ControllerResource;
use PhpSpec\Console\IO;
use PhpSpec\CodeGenerator\TemplateRenderer;
use PhpSpec\Util\Filesystem;
use PhpSpec\CodeGenerator\Generator\SpecificationGenerator;

class ControllerSpecificationGenerator extends SpecificationGenerator
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
        return 'specification' === $generation && $resource instanceof ControllerResource;
    }

    /**
     * @return string
     */
    protected function getTemplate()
    {
        return file_get_contents(__FILE__, null, null, __COMPILER_HALT_OFFSET__);
    }
}
__halt_compiler();<?php

namespace %namespace%;

use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
use Prophecy\Argument;

class %name% extends ControllerBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('%subject%');
    }
}
