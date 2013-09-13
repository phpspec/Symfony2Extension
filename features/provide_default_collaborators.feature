Feature: Provide default collaborators
  As a Developer
  I want to avoid configuring framework mocks every time by hand
  In order to have a working preconfigured set of mocks

  Background:
    Given the Symfony extension is enabled with:
        """
        extensions:
            PhpSpec\Symfony2Extension\Extension:
                router: Symfony\Component\Routing\RouterInterface
        """

  Scenario: Controller has access to common collaborator
    Given I wrote a spec in the "src/CommonCollaborator/Spec/Controller.php":
    """
    <?php

    namespace CommonCollaborator\Spec;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;

    class Controller extends ControllerBehavior
    {
        function it_has_access_to_router($router) // magic!
        {
            $this->generateUrl('homepage')->shouldHaveType('string');
        }
    }

    """
    And I wrote a class in the "src/CommonCollaborator/Controller.php":
    """
    <?php

    namespace CommonCollaborator;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

    class Controller extends BaseController
    {
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
