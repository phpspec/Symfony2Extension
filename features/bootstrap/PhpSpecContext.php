<?php

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use PhpSpec\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class PhpSpecContext extends BehatContext
{
    private $workDir = null;

    /**
     * @BeforeScenario
     */
    public function createWorkDir()
    {
        $this->workDir = sys_get_temp_dir().'/PhpSpecSymfony2Extension/'.microtime(true).'/';

        mkdir($this->workDir, 0777, true);
        chdir($this->workDir);
    }

    /**
     * @AfterScenario
     */
    public function removeWorkDir()
    {
        system('rm -rf '.$this->workDir);
    }

    /**
     * @Given /^(?:|the )Symfony extension is enabled$/
     */
    public function theSymfonyExtendsionIsEnabled()
    {
        $phpspecyml = <<<YML
extensions:
  - PhpSpec\Symfony2Extension\Extension
YML;

        file_put_contents($this->workDir.'phpspec.yml', $phpspecyml);
    }

    /**
     * @When /^(?:|I )run phpspec with "(?P<input>.*)"$/
     */
    public function iRunPhpspecWith($input)
    {
        $input = new StringInput($input);
        $input->setInteractive(false);

        $output = new StreamOutput(fopen('php://memory', 'w', false));

        $application = new Application('2.0-dev');
        $application->doRun($input, $output);
    }

    /**
     * @Then /^(?:|a )new specification should be generated in (?:|the )"(?P<specFile>[^"]*Spec.php)":$/
     */
    public function aNewSpecificationShouldBeGeneratedInTheSpecFile($specFile, PyStringNode $string)
    {
        if (!file_exists($specFile)) {
            throw new \LogicException('Spec file was not created');
        }

        expect(file_get_contents($specFile))->toBe($string->getRaw());
    }
}
