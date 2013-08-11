# split into multiple files and add missing scenarios (like run with parameters)
Feature: Describing a controller
  As a Developer
  I want to automate creating controller specifications
  In order to avoid repetitive tasks

  Background:
    Given the Symfony extension is enabled

  @wip
  Scenario Outline: Controller spec is generated
    When I describe the "<class>"
    Then a new specification should be generated in the "Scenario1/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario1\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;

    class UserControllerSpec extends ControllerBehavior
    {
        function it_is_initializable()
        {
            $this->shouldHaveType('Scenario1\Bundle\DemoBundle\Spec\Controller\UserController');
        }
    }

    """

    Examples:
      | class                                                      |
      | Scenario1/Bundle/DemoBundle/Controller/UserController      |
      | Scenario1/Bundle/DemoBundle/Spec/Controller/UserController |

  @wip
  Scenario: Generating a controller
    Given I described the "Scenario4/Bundle/DemoBundle/Controller/UserController"
    When I run phpspec and answer "y" to the first question
    Then a new class should be generated in the "src/Scenario4/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario4\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;

    class UserController extends ContainerAware
    {
    }

    """

  @wip
  Scenario: Executing a controller spec with a response inspection
    Given I wrote a spec in the "Scenario5/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario5\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;

    class UserControllerSpec extends ControllerBehavior
    {
        function it_should_respond_to_the_list_action_call()
        {
            $response = $this->listAction();
            $response->shouldHaveType('Symfony\Component\HttpFoundation\Response');
            $response->getStatusCode()->shouldBe(200);
        }
    }

    """
    And I wrote a class in the "src/Scenario5/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario5\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;
    use Symfony\Component\HttpFoundation\Response;

    class UserController extends ContainerAware
    {
        public function listAction()
        {
            return new Response();
        }
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"

  @wip
  Scenario: Executing a controller spec with a service
    Given I wrote a spec in the "Scenario6/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario6\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;
    use Symfony\Component\Routing\Router;

    class UserControllerSpec extends ControllerBehavior
    {
        function it_should_redirect_to_the_homepage(Router $router)
        {
            $this->container->set('router', $router);

            $router->generate('homepage')->willReturn('/');

            $response = $this->listAction();
            $response->shouldHaveType('Symfony\Component\HttpFoundation\RedirectResponse');
            $response->getTargetUrl()->shouldBe('/');
        }
    }

    """
    And I wrote a class in the "src/Scenario6/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario6\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;
    use Symfony\Component\HttpFoundation\RedirectResponse;

    class UserController extends ContainerAware
    {
        public function listAction()
        {
            $url = $this->container->get('router')->generate('homepage');

            return new RedirectResponse($url);
        }
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"

  @wip
  Scenario: Executing a controller spec with a render matcher
    Given I wrote a spec in the "Scenario7/Bundle/DemoBundle/Spec/Controller/UserControllerSpec.php":
    """
    <?php

    namespace Scenario7\Bundle\DemoBundle\Spec\Controller;

    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;
    use Symfony\Component\Templating\EngineInterface;

    class UserControllerSpec extends ControllerBehavior
    {
        function it_should_render_list_of_users(EngineInterface $templating)
        {
            $this->container->set('templating', $templating);

            $this->shouldRender('Scenario7UserBundle:User:list.html.twig', array('users' => array()))
                ->duringAction('list');
        }
    }

    """
    And I wrote a class in the "src/Scenario7/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Scenario7\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;

    class UserController extends ContainerAware
    {
        public function listAction()
        {
            return $this->container->get('templating')->renderResponse(
                'Scenario7UserBundle:User:list.html.twig', array('users' => array())
            );
        }
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
