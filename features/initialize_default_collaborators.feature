Feature: Initialize default collaborators
  As a Developer
  I want default collaborators to be preconfigured
  In order to avoid complex let methods

  Background:
    Given the Symfony extension is enabled with:
        """
        extensions:
            - PhpSpec\Symfony2Extension\Extension

        symfony2_extension.common-collaborators:
            container: { service_container: ~ }
            router: ~
            request: ~
            session: ~
            doctrine: ~
        """

  Scenario: Controller spec has access to common collaborators
    Given I wrote a spec in the "src/Scenario8/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario8\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\ObjectBehavior;
    use Prophecy\Argument;

    class UserControllerSpec extends ObjectBehavior
    {
        function its_generateUrl_generates_urls($container)
        {
            $this->setContainer($container);
            $this->generateUrl('homepage')->shouldReturn('homepage'); // preconfigured router!
        }
    }

    """
    And I wrote a class in the "src/Scenario8/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario8\Bundle\DemoBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    class UserController extends Controller
    {
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
