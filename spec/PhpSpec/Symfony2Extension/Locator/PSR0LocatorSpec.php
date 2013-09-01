<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use PhpSpec\Symfony2Extension\Locator\PSR0Resource;
use PhpSpec\Symfony2Extension\Locator\ResourceFactory;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class PSR0LocatorSpec extends ObjectBehavior
{
    private $workspace;

    private $srcPath;

    private $filesystem;

    function let(Filesystem $fs, ResourceFactory $resourceFactory)
    {
        $this->workspace = sys_get_temp_dir().DIRECTORY_SEPARATOR.md5(microtime()).DIRECTORY_SEPARATOR;

        $srcPath = $this->workspace.'src'.DIRECTORY_SEPARATOR;
        $this->filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $this->filesystem->mkdir($srcPath);
        $this->filesystem->mkdir($srcPath.'Acme/Model/Spec');
        $this->filesystem->dumpFile($srcPath.'Acme/Bundle/DemoBundle/Model/User.php', '');
        $this->filesystem->dumpFile($srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php', '');

        $this->srcPath = realpath($srcPath).DIRECTORY_SEPARATOR;
        $this->workspace = realpath($this->workspace).DIRECTORY_SEPARATOR;

        chdir($this->workspace);

        $this->beConstructedWith('Acme', 'Spec', 'src', array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec'), $fs, $resourceFactory);
    }

    function letgo()
    {
        $this->filesystem->remove($this->workspace);
    }

    function it_is_a_locator()
    {
        $this->shouldHaveType('PhpSpec\Locator\ResourceLocatorInterface');
    }

    function it_resolves_glob_patterns_when_looking_for_resources(Filesystem $fs, ResourceFactory $resourceFactory, PSR0Resource $resource, \SplFileInfo $file)
    {
        $fs->findPhpFilesIn(array($this->srcPath.'Acme/Bundle/DemoBundle/Spec', $this->srcPath.'Acme/Model/Spec'))->willReturn(array($file));
        $file->getRealPath()->willReturn($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php');

        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->getAllResources()->shouldReturn(array($resource));
    }

    function it_returns_an_empty_array_if_none_of_tracked_specPaths_exists(Filesystem $fs)
    {
        $this->filesystem->remove($this->srcPath.'Acme');

        $fs->findPhpFilesIn(Argument::any())->shouldNotBeCalled();

        $this->getAllResources()->shouldReturn(array());
    }

    function it_supports_folder_queries_in_the_srcPath()
    {
        $this->supportsQuery($this->srcPath.'Acme')->shouldReturn(true);
    }

    function it_supports_srcPath_queries()
    {
        $this->supportsQuery($this->srcPath)->shouldReturn(true);
    }

    function it_supports_absolute_file_queries_in_srcPath()
    {
        $this->supportsQuery(realpath($this->srcPath.'Acme/Bundle/DemoBundle/Model/User.php'))->shouldReturn(true);
    }

    function it_supports_relative_file_queries_in_srcPath()
    {
        $this->supportsQuery('src/Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php')->shouldReturn(true);
    }

    function it_does_not_support_queries_for_missing_files()
    {
        $this->supportsQuery('src/Acme/Model/Spec/UserSpec.php')->shouldReturn(false);
    }

    function it_does_not_support_any_other_queries()
    {
        $this->supportsQuery('/')->shouldReturn(false);
    }

    function it_finds_spec_resources_via_the_srcPath(Filesystem $fs, ResourceFactory $resourceFactory, PSR0Resource $resource, \SplFileInfo $file)
    {
        $fs->findPhpFilesIn(array($this->srcPath.'Acme/Bundle/DemoBundle/Spec', $this->srcPath.'Acme/Model/Spec'))->willReturn(array($file));
        $file->getRealPath()->willReturn($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php');

        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->findResources('src')->shouldReturn(array($resource));
    }

    function it_finds_spec_resources_via_a_subSrcPath(Filesystem $fs, ResourceFactory $resourceFactory, PSR0Resource $resource, \SplFileInfo $file)
    {
        $fs->findPhpFilesIn(array($this->srcPath.'Acme/Bundle/DemoBundle/Spec'))->willReturn(array($file));
        $file->getRealPath()->willReturn($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php');

        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->findResources('src/Acme/Bundle/DemoBundle')->shouldReturn(array($resource));
    }

    function it_finds_spec_resources_via_a_specPath(Filesystem $fs, ResourceFactory $resourceFactory, PSR0Resource $resource, \SplFileInfo $file)
    {
        $fs->findPhpFilesIn(array($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model'))->willReturn(array($file));
        $file->getRealPath()->willReturn($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php');

        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->findResources('src/Acme/Bundle/DemoBundle/Spec/Model')->shouldReturn(array($resource));
    }

    function it_finds_a_single_spec_via_a_srcPath(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->findResources('src/Acme/Bundle/DemoBundle/Model/User.php')->shouldReturn(array($resource));
    }

    function it_finds_a_single_spec_via_a_specPath(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->findResources('src/Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php')->shouldReturn(array($resource));
    }

    function it_returns_an_empty_array_if_path_does_not_exist()
    {
        $this->findResources('src/Acme/Bundle/MissingBundle')->shouldHaveCount(0);
    }

    function it_returns_an_empty_array_if_nothing_is_found()
    {
        $this->filesystem->remove($this->srcPath.'Acme');

        $this->findResources('src')->shouldHaveCount(0);
    }

    function it_supports_classes_from_srcNamespace(Filesystem $fs)
    {
        $this->beConstructedWith('Acme\\Model', 'Spec', 'src', array('src/*/*/Spec'), $fs);

        $this->supportsClass('Acme\\Model\\User')->shouldReturn(true);
    }

    function it_supports_forward_slashed_classes_from_srcNamespace(Filesystem $fs)
    {
        $this->beConstructedWith('Acme\\Model', 'Spec', 'src', array('src/*/*/Spec'), $fs);

        $this->supportsClass('Acme/Model/User')->shouldReturn(true);
    }

    function it_supports_any_class_if_srcNamespace_is_empty(Filesystem $fs)
    {
        $this->beConstructedWith('', 'Spec', 'src', array('src/*/*/Spec'), $fs);

        $this->supportsClass('User')->shouldReturn(true);
    }

    function it_does_not_support_any_other_class(Filesystem $fs)
    {
        $this->beConstructedWith('Acme', 'Spec', 'src', array('src/*/*/Spec'), $fs);

        $this->supportsClass('Foo\Any')->shouldReturn(false);
    }

    function it_creates_a_resource_from_a_bundle_src_class(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme\Bundle\DemoBundle\Model\User')->shouldReturn($resource);
    }

    function it_creates_a_resource_from_any_src_class(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme\Model\User')->shouldReturn($resource);
    }

    function it_creates_a_resource_from_a_forward_slashed_src_class(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme/Bundle/DemoBundle/Model/User')->shouldReturn($resource);
    }

    function it_creates_a_resource_from_a_spec_class(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme\Bundle\DemoBundle\Spec\Model\UserSpec')->shouldReturn($resource);
    }

    function it_creates_a_resource_from_a_spec_class_with_a_custom_specSubNamespace(Filesystem $fs)
    {
        $this->beConstructedWith('Acme', 'Specs', 'src', array('src/*/Bundle/*Bundle/Specs', 'src/*/*/Specs'), $fs);

        $resource = $this->createResource('Acme\Bundle\DemoBundle\Specs\Model\UserSpec');

        $resource->shouldBeAnInstanceOf('PhpSpec\Symfony2Extension\Locator\PSR0Resource');
        $resource->getSrcClassname()->shouldReturn('Acme\Bundle\DemoBundle\Model\User');
        $resource->getSpecClassname()->shouldReturn('Acme\Bundle\DemoBundle\Specs\Model\UserSpec');
    }

    function it_creates_a_resource_from_a_forward_slashed_spec_class(ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $resourceFactory->create(array('Acme', 'Bundle', 'DemoBundle', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme/Bundle/DemoBundle/Spec/Model/UserSpec')->shouldReturn($resource);
    }

    function it_creates_a_resource_from_a_src_class_even_if_the_srcNamespace_is_empty(Filesystem $fs, ResourceFactory $resourceFactory, PSR0Resource $resource)
    {
        $this->beConstructedWith('', 'Spec', 'src', array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec'), $fs, $resourceFactory);

        $resourceFactory->create(array('Acme', 'Model', 'User'), 'Spec', $this->srcPath)
            ->shouldBeCalled()->willReturn($resource);

        $this->createResource('Acme\Model\User')->shouldReturn($resource);
    }

    function it_returns_null_if_srcNamespace_does_not_match()
    {
        $this->createResource('Foo\Model\User')->shouldReturn(null);
    }

    function its_priority_is_zero()
    {
        $this->getPriority()->shouldReturn(0);
    }
}
