<?php
/**
 * Default Controller for the Presentation
 */
namespace JirasticBundle\Controller;

use JirasticBundle\Util\ConfigUtils;
use Symfony\Component\HttpFoundation\Request;
use JirasticBundle\Service\JiraAPI;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\FormFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

/**
 * @package JirasticBundle\Controller
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class DefaultController
 */
class PresentationController
{
    /**
     * @var JiraApi
     */
    private $jiraApi;

    /**
     * @var EngineInterface
     */
    private $templating;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ConfigUtils
     */
    private $configUtils;

    /**
     * @var Session
     */
    private $session;


    /**
     * Inject used Dependencies
     *
     * DefaultController constructor.
     * @param EngineInterface $templating    Templating Engine
     * @param Router          $router        Router
     * @param JiraAPI         $jiraApi       JiraApi
     * @param FormFactory     $formFactory   Form Factory
     * @param EntityManager   $entityManager Entity Manager
     * @param ConfigUtils     $configUtils   Config Utils
     * @param Session         $session       Session
     */
    public function __construct(
        EngineInterface $templating,
        Router $router,
        JiraAPI $jiraApi,
        FormFactory $formFactory,
        EntityManager $entityManager,
        ConfigUtils $configUtils,
        Session $session
    ) {
        $this->templating = $templating;
        $this->router = $router;
        $this->jiraApi = $jiraApi;
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->configUtils = $configUtils;
        $this->session = $session;
    }

    /**
     * redirect to boards / is not really needed
     *
     * @param Request $request Request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        return new RedirectResponse($this->router->generate('boards', array()));
    }

    /**
     * Show a list of all available boards
     *
     * @param Request $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function boardsAction(Request $request)
    {
        if(!$this->configUtils->customFieldsSet()){
            $this->session->getFlashBag()
                ->add(
                'warning',
                'Customfields are not configured. Contact the administrator'
            );
        }

        return $this->templating->renderResponse(
            'JirasticBundle:presentation:boards.html.twig',
            array(
                'boards' => $this->jiraApi->getBoards(),
            )
        );
    }

    /**
     * Get all Sprints of the selected Board
     *
     * @param int $boardId Baord Id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sprintsAction($boardId)
    {
        return $this->templating->renderResponse(
            'JirasticBundle:presentation:sprints.html.twig',
            array(
                'boards' => $this->jiraApi->getBoards(),
                'activeBoard' => $this->jiraApi->getBoardById($boardId),
                'sprints' => $this->jiraApi->getSprintsOfBoard($boardId)
            )
        );
    }

    /**
     * @param int $boardId  Board Id
     * @param int $sprintId Sprint Id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function presentationAction($boardId, $sprintId)
    {
        $sprint = $this->jiraApi->getBoardById($boardId)->getSprintById($sprintId);
        return $this->templating->renderResponse(
            'JirasticBundle:presentation:presentation.html.twig',
            array(
                'mappedIssues' => $sprint->getIssues(),
                'sprint' => $sprint,
                'sprintSuccessful' => $sprint->isSuccessful()
            )
        );
    }
}
