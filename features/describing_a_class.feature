Feature: Describing a class
  As a Developer
  I want to automate creating class specifications
  In order to avoid repetitive tasks

  Background:
    Given the Symfony extension is enabled

  @wip @dev
  Scenario: Spec is generated in the bundle
    When I describe the "Scenario1/Bundle/DemoBundle/Model/User"
    Then a new specification should be generated in the "Scenario1/Bundle/DemoBundle/Spec/Model/UserSpec.php":
    """
    <?php

    namespace Scenario1\Bundle\DemoBundle\Spec\Model;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class UserSpec extends ObjectBehavior
    {
        function it_is_initializable()
        {
            $this->shouldHaveType('Scenario1\Bundle\DemoBundle\Model\User');
        }
    }

    """

  @wip
  Scenario: Running a spec
    Given I described the "Scenario2/Bundle/DemoBundle/Model/User"
    When I run phpspec
    Then I should see "class Scenario2\Bundle\DemoBundle\Model\User does not exist"

  @wip
  Scenario: Generating a class
    Given I described the "Scenario3/Bundle/DemoBundle/Model/User"
    When I run phpspec and answer "y" to the first question
    Then a new class should be generated in the "src/Scenario3/Bundle/DemoBundle/Model/User.php":
    """
    <?php

    namespace Scenario3\Bundle\DemoBundle\Model;

    class User
    {
    }

    """

  @wip
  Scenario: Executing a class spec
    Given I wrote a spec in the "Scenario4/Bundle/DemoBundle/Spec/Model/User.php":
    """
    <?php

    namespace Scenario4\Bundle\DemoBundle\Spec\Model;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class UserSpec extends ObjectBehavior
    {
        function it_has_a_string_representation()
        {
            $this->beConstructedWith('Kuba');

            $this->__toString()->shouldReturn('Kuba');
        }
    }

    """
    And I wrote a class in the "src/Scenario4/Bundle/DemoBundle/Model/User.php":
    """
    <?php

    namespace Scenario4\Bundle\DemoBundle\Model;

    class User
    {
        private $name;

        public function __construct($name)
        {
            $this->name = $name;
        }

        public function __toString()
        {
            return $this->name;
        }
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
