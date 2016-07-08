<?php
/**
 * Class SecurityLoginListener
 */

namespace JirasticBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Doctrine\ORM\EntityManager;

/**
 * @package JirasticBundle\EventListener
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class SecurityLoginListener
 */
class SecurityLoginListener
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var AuthorizationChecker
     */
    protected $security;

    /**
     * @var TraceableEventDispatcher
     */
    protected $dispatcher;

    /**
     * @var Security
     */
    private $securityContext;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * SecurityLoginListener constructor.
     * @param Router                   $router        Router
     * @param AuthorizationChecker     $security      Security
     * @param TraceableEventDispatcher $dispatcher    Dispatcher
     * @param EntityManager            $entityManager Entity manager
     */
    public function __construct(
        Router $router,
        AuthorizationChecker $security,
        EventDispatcherInterface $dispatcher,
        SecurityContext $securityContext,
        EntityManager $entityManager
    ) {
        $this->router = $router;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    /**
     * @param InteractiveLoginEvent $event Event
     * @return void
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $this->dispatcher->addListener(KernelEvents::RESPONSE, array($this, 'onKernelResponse'));
    }

    /**
     * @param FilterResponseEvent $event Event
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->security->isGranted('ROLE_USER')) {
            if($this->isFirstLogin()) {
                $response = new RedirectResponse($this->router->generate('admin_welcome'));
            } else {
                $response = new RedirectResponse($this->router->generate('boards'));
            }
        } else {
            throw new AccessDeniedHttpException();
        }

        $event->setResponse($response);
    }

    private function isFirstLogin ()
    {
        $userLoggedIn = $this->securityContext->getToken()->getUser();
        $user = $this->entityManager
            ->getRepository('JirasticBundle:User')
            ->findOneById($userLoggedIn->getId());

        if (is_null($user->getLoggedInBefore()) or $user->getLoggedInBefore() == false){
            $user->setLoggedInBefore(true);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            return true;
        } else {
            return false;
        }
    }
}
