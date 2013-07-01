Feature: Describing a controller
  As a Developer
  I want to automate creating controller specifications
  In order to avoid repetitive tasks

  Scenario Outline: Describing a controller
    Given the Symfony extension is enabled
    When I describe the "<class>"
    Then a new specification should be generated in the "spec/Acme/Bundle/DemoBundle/Controller/UserControllerSpec.php":
    """
    <?php

    namespace spec\Acme\Bundle\DemoBundle\Controller;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;

    class UserControllerSpec extends ControllerBehavior
    {
        function it_is_initializable()
        {
            $this->shouldHaveType('Acme\Bundle\DemoBundle\Controller\UserController');
        }
    }

    """

    Examples:
      | class                                                 |
      | Acme/Bundle/DemoBundle/Controller/UserController      |
      | spec/Acme/Bundle/DemoBundle/Controller/UserController |

  Scenario: Describing a class
    Given the Symfony extension is enabled
    When I describe the "Acme/Bundle/DemoBundle/User"
    Then a new specification should be generated in the "spec/Acme/Bundle/DemoBundle/UserSpec.php":
    """
    <?php

    namespace spec\Acme\Bundle\DemoBundle;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class UserSpec extends ObjectBehavior
    {
        function it_is_initializable()
        {
            $this->shouldHaveType('Acme\Bundle\DemoBundle\User');
        }
    }

    """

  Scenario: Running a controller spec
    Given the Symfony extension is enabled
    And I described the "Acme/Bundle/DemoBundle/Controller/UserController"
    When I run phpspec
    Then I should see "class Acme\Bundle\DemoBundle\Controller\UserController does not exist"

  Scenario: Generating a controller

  Scenario: Executing a controller spec
