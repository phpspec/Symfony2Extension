Feature: Describing a class
  As a Developer
  I want to automate creating class specifications
  In order to avoid repetitive tasks

  Background:
    Given the Symfony extension is enabled

  Scenario: Spec is generated in the bundle
    When I describe the "Scenario1/Bundle/DemoBundle/Model/User"
    Then a new specification should be generated in the "src/Scenario1/Bundle/DemoBundle/Spec/Model/UserSpec.php":
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

  Scenario Outline: Describing a class supports common Symfony2 bundle structures
    When I describe the "<Class>"
    Then a new specification file "<Specification>" should be created
    
    Examples:
      | Class                                   | Specification                                           |
      | Scenario1/Bundle/DemoBundle/Model/User  | src/Scenario1/Bundle/DemoBundle/Spec/Model/UserSpec.php |
      | Acme/DemoBundle/Model/User              | src/Acme/DemoBundle/Spec/Model/UserSpec.php             |
      | DemoBundle/Model/User                   | src/DemoBundle/Spec/Model/UserSpec.php                  |
      

  Scenario: Running a spec
    Given I described the "Scenario2/Bundle/DemoBundle/Model/User"
    When I run phpspec
    Then I should see "class Scenario2\Bundle\DemoBundle\Model\User does not exist"

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

  Scenario Outline: Generating a class supports common Symfony2 bundle structures
    Given I described the "<Class>"
    When I run phpspec and answer "y" to the first question
    Then a new class file "<Implementation>" should be created
    
    Examples:
      | Class                                   | Implementation                                 |
      | Scenario1/Bundle/DemoBundle/Model/User  | src/Scenario1/Bundle/DemoBundle/Model/User.php |
      | Acme/DemoBundle/Model/User              | src/Acme/DemoBundle/Model/User.php             |
      | DemoBundle/Model/User                   | src/DemoBundle/Model/User.php                  |
      

  Scenario: Executing a class spec
    Given I wrote a spec in the "src/Scenario4/Bundle/DemoBundle/Spec/Model/User.php":
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
