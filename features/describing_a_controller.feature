Feature: Describing a controller
  As a Developer
  I want to automate creating controller specifications
  In order to avoid repetitive tasks

  Background:
    Given the Symfony extension is enabled

  Scenario Outline: Controller spec is generated
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

  Scenario: Non-controller spec is generated with a default template
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
    Given I described the "Acme/Bundle/DemoBundle/Controller/UserController"
    When I run phpspec
    Then I should see "class Acme\Bundle\DemoBundle\Controller\UserController does not exist"

  Scenario: Generating a controller
    Given I described the "Acme/Bundle/DemoBundle/Controller/UserController"
    When I run phpspec and answer "y" to the first question
    Then a new class should be generated in the "src/Acme/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Acme\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;

    class UserController extends ContainerAware
    {
    }

    """

  @wip
  Scenario: Executing a controller spec with response inspection
    Given I wrote a spec in the "spec/Acme/Bundle/DemoBundle/Controller/UserController":
    """
    <?php

    namespace spec\Acme\Bundle\DemoBundle\Controller;

    use Doctrine\ORM\EntityRepository;
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
    And I wrote a class in the "src/Acme/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Acme\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;

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
  Scenario: Executing a controller spec with service expectations
    Given I wrote a spec in the "spec/Acme/Bundle/DemoBundle/Controller/UserControllerSpec.php":
    """
    <?php

    namespace spec\Acme\Bundle\DemoBundle\Controller;

    use Doctrine\Bundle\DoctrineBundle\Registry;
    use Doctrine\ORM\EntityRepository;
    use PhpSpec\Symfony2Extension\Specification\ControllerBehavior;
    use Prophecy\Argument;
    use Symfony\Component\Templating\EngineInterface;

    class UserControllerSpec extends ControllerBehavior
    {
        function let(Registry $doctrine, EntityRepository $repository, EngineInterface $templating)
        {
            $doctrine->getManager()->willReturn($repository);
            $this->container->get('doctrine')->willReturn($doctrine);
            $this->container->get('templating')->willReturn($templating);
        }

        function it_should_render_list_of_users(EntityRepository $repository)
        {
            $repository->findAll()->willReturn(array('user1', 'user2'));

            $this->shouldRender('AcmeUserBundle:User:list.html.twig', array('users' => array('user1', 'user2')))
                ->duringAction('list');
        }
    }

    """
    And I wrote a class in the "src/Acme/Bundle/DemoBundle/Controller/UserController.php":
    """
    <?php

    namespace Acme\Bundle\DemoBundle\Controller;

    use Symfony\Component\DependencyInjection\ContainerAware;

    class UserController extends ContainerAware
    {
        public function listAction()
        {
            $repository = $this->get('doctrine')->getManager();

            return $this->container->get('templating')->renderResponse(
                'AcmeUserBundle:User:list.html.twig', array('users' => $repository->findAll())
            );
        }
    }

    """
    When I run phpspec
    Then I should see "1 example (1 passed)"
