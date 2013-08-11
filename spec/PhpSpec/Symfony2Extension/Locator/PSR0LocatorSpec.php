<?php

namespace spec\PhpSpec\Symfony2Extension\Locator;

use PhpSpec\ObjectBehavior;
use PhpSpec\Util\Filesystem;
use Prophecy\Argument;

class PSR0LocatorSpec extends ObjectBehavior
{
    private $workspace;

    private $srcPath;

    private $filesystem;

    function let(Filesystem $fs)
    {
        $this->workspace = sys_get_temp_dir().DIRECTORY_SEPARATOR.md5(microtime()).DIRECTORY_SEPARATOR;

        $srcPath = $this->workspace.'src'.DIRECTORY_SEPARATOR;
        $this->filesystem = new \Symfony\Component\Filesystem\Filesystem();
        $this->filesystem->mkdir($srcPath);
        $this->filesystem->mkdir($srcPath.'Acme/Model/Spec');
        $this->filesystem->dumpFile($srcPath.'Acme/Bundle/DemoBundle/Model/User.php', '');
        $this->filesystem->dumpFile($srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php', '');

        $this->srcPath = realpath($srcPath).DIRECTORY_SEPARATOR;

        chdir($this->workspace);

        $specPaths = array('src/*/Bundle/*Bundle/Spec', 'src/*/*/Spec');

        $this->beConstructedWith('', 'src', $specPaths, $fs);
    }

    function letgo()
    {
        $this->filesystem->remove($this->workspace);
    }

    function it_is_a_locator()
    {
        $this->shouldHaveType('PhpSpec\Locator\ResourceLocatorInterface');
    }

    function it_resolves_glob_patterns_when_looking_for_resources(Filesystem $fs, \SplFileInfo $file)
    {
        $fs->findPhpFilesIn(array($this->srcPath.'Acme/Bundle/DemoBundle/Spec', $this->srcPath.'Acme/Model/Spec'))->willReturn(array($file));
        $file->getRealPath()->willReturn($this->srcPath.'Acme/Bundle/DemoBundle/Spec/Model/UserSpec.php');

        $resources = $this->getAllResources();
        $resources->shouldHaveCount(1);
        $resources[0]->getSpecClassname()->shouldReturn('Acme\\Bundle\\DemoBundle\\Spec\\Model\\UserSpec');
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

    function it_supports_classes_from_srcNamespace(Filesystem $fs)
    {
        $specPaths = array('src/*/*/Spec');

        $this->beConstructedWith('Acme\\Model', 'src', $specPaths, $fs);

        $this->supportsClass('Acme\\Model\\User')->shouldReturn(true);
    }

    function it_supports_forward_slashed_classes_from_srcNamespace(Filesystem $fs)
    {
        $specPaths = array('src/*/*/Spec');

        $this->beConstructedWith('Acme\\Model', 'src', $specPaths, $fs);

        $this->supportsClass('Acme/Model/User')->shouldReturn(true);
    }

    function it_supports_any_class_if_srcNamespace_is_empty(Filesystem $fs)
    {
        $specPaths = array('src/*/*/Spec');

        $this->beConstructedWith('', 'src', $specPaths, $fs);

        $this->supportsClass('User')->shouldReturn(true);
    }

    function it_does_not_support_any_other_class()
    {
        $specPaths = array('src/*/*/Spec');

        $this->beConstructedWith('Acme', 'src', $specPaths, $fs);

        $this->supportsClass('Foo\Any')->shouldReturn(false);
    }

    function its_priority_is_zero()
    {
        $this->getPriority()->shouldReturn(0);
    }
}
