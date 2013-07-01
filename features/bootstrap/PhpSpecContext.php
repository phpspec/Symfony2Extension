<?php

use Behat\Behat\Context\BehatContext;
use Behat\Gherkin\Node\PyStringNode;
use PhpSpec\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\StreamOutput;

class PhpSpecContext extends BehatContext
{
    private $workDir = null;

    private $applicationTester = null;

    /**
     * @BeforeScenario
     */
    public function createWorkDir()
    {
        // Unfortunately we cannot make the directory name unique.
        // Since scenarios might be using the same class names, we cannot change
        // paths between scenarios.
        $this->workDir = sys_get_temp_dir().'/PhpSpecSymfony2Extension/';

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
     * @When /^(?:|I )run phpspec$/
     */
    public function iRunPhpspec()
    {
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->run('run --no-interaction');
    }

    /**
     * @When /^(?:|I )run phpspec and answer "(?P<answer>[^"]*)" to (?:|the )first question$/
     */
    public function iRunPhpspecAndAnswerToTheFirstQuestion($answer)
    {
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->putToInputStream(sprintf("%s\n", $answer));
        $this->applicationTester->run('run --no-interaction');
    }

    /**
     * @When /^(?:|I )describe(?:|d) (?:|the )"(?P<class>[^"]*)"$/
     */
    public function iDescribeThe($class)
    {
        $this->applicationTester = $this->createApplicationTester();
        $this->applicationTester->run(sprintf('describe %s --no-interaction', $class));
    }

    /**
     * @Given /^(?:|I )wrote (?:|a )spec in (?:|the )"(?P<file>[^"]+)":$/
     * @Given /^(?:|I )wrote (?:|a )class in (?:|the )"(?P<file>[^"]+)":$/
     */
    public function iWroteSpecInThe($file, PyStringNode $string)
    {
        file_put_contents($file, $string->getRaw());
    }

    /**
     * @Then /^(?:|a )new specification should be generated in (?:|the )"(?P<file>[^"]*Spec.php)":$/
     * @Then /^(?:|a )new class should be generated in (?:|the )"(?P<file>[^"]+)":$/
     */
    public function aNewSpecificationShouldBeGeneratedInTheSpecFile($file, PyStringNode $string)
    {
        if (!file_exists($file)) {
            throw new \LogicException(sprintf('"%s" file was not created', $file));
        }

        expect(file_get_contents($file))->toBe($string->getRaw());
    }

    /**
     * @Then /^(?:|I )should see "(?P<message>[^"]*)"$/
     */
    public function iShouldSee($message)
    {
        expect($this->applicationTester->getDisplay())->toMatch('/'.preg_quote($message, '/').'/sm');
    }

    /**
     * @return ApplicationTester
     */
    private function createApplicationTester()
    {
        $application = new Application('2.0-dev');
        $application->setAutoExit(false);

        return new ApplicationTester($application);
    }
}
