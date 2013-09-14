Feature: Provide default collaborators
  As a Developer
  I want to avoid configuring framework mocks every time by hand
  In order to have a working preconfigured set of mocks

  Background:
    Given the Symfony extension is enabled with:
        """
        extensions:
            - PhpSpec\Symfony2Extension\Extension

        symfony2_extension.common-collaborators:
            router: Symfony\Component\Routing\RouterInterface
        """

  Scenario: Controller spec has access to common collaborators
    Given I wrote a spec in the "src/Scenario7/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario7\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class UserControllerSpec extends ObjectBehavior
    {
        function its_generateUrl_generates_urls($container)
        {
            $this->setContainer($container);
            $this->generateUrl('homepage');
        }
    }

    """
    And I wrote a class in the "src/Scenario7/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario7\Bundle\DemoBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class UserController extends Controller
    {
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
