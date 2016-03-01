<?php
/**
 * Class SecurityLoginListener makes sure the redirection is different for every role
 */

namespace JirasticBundle\EventListener;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

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
     * SecurityLoginListener constructor.
     * @param Router                   $router     Router
     * @param AuthorizationChecker     $security   Security
     * @param TraceableEventDispatcher $dispatcher Dispatcher
     */
    public function __construct(Router $router, AuthorizationChecker $security, TraceableEventDispatcher $dispatcher)
    {
        $this->router = $router;
        $this->security = $security;
        $this->dispatcher = $dispatcher;
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
        // 'If' order is important! because of the role hierarchy
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('admin_boards'));
        } elseif ($this->security->isGranted('ROLE_JIRASTIC_USER')) {
            $response = new RedirectResponse($this->router->generate('boards'));
        } else {
            throw new AccessDeniedHttpException();
        }

        $event->setResponse($response);
    }
}
