<?php
/**
 * Prepares the Config data
 */
namespace JirasticBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @package JirasticBundle\Util
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class ConfigUtils
 */
class ConfigUtils
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var string
     */
    private $token;

    /**
     * ConfigUtils constructor.
     * @param EntityManager         $entityManager Entity Manager
     * @param Container             $container     Container
     * @param TokenStorageInterface $tokenStorage  Token storage
     */
    public function __construct(EntityManager $entityManager, Container $container, TokenStorageInterface $tokenStorage)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->token = $tokenStorage;
    }

    /**
     * Fetch status mapping from db
     *
     * @return array
     */
    public function getStatusMapping()
    {
        $mapping = array();
        foreach ($this->getStatesByBoardId($this->getBoardId()) as $state) {
            foreach ($state->getStatusMapping() as $map) {
                $mapping[$map->getFieldName()] = strtolower(str_replace(' ', '', $state->getTitleShort()));
            }
        }

        return $mapping;
    }

    /**
     * Get States from db and prepare the array
     *
     * @return array
     */
    public function getMappedIssues()
    {
        $ret = array();
        foreach ($this->getStatesByBoardId($this->getBoardId()) as $state) {
            $titleShort = strtolower(str_replace(' ', '', $state->getTitleShort()));
            $ret[$titleShort] = array(
                'id' => $titleShort,
                'title' => $state->getTitle(),
                'titleShort' => $state->getTitleShort(),
                'icon' => $state->getIcon(),
                'class' => $state->getClass(),
                'bgcolor' => $state->getBgcolor(),
                'orderId' => $state->getOrderId()
            );
        }

        // order slides depending on the Order id
        uasort(
            $ret,
            function ($a, $b) {
                if ($a['orderId'] > $b['orderId']) {
                    return 1;
                } else {
                    return 0;
                }
            }
        );

        return $ret;
    }

    /**
     * @param int $id Id
     * @return array
     */
    private function getStatesByBoardId($id)
    {
        $board = $this->entityManager->getRepository('JirasticBundle:Board')->findOneBy(array('jiraId' => $id));
        return $board->getStatuses();
    }

    /**
     * Get board id from Request
     *
     * @return int
     */
    private function getBoardId()
    {
        $params = $this->container->get('request')->attributes->get('_route_params');
        return intval($params['boardId']);
    }

    /**
     * @return null|object
     */
    public function getCustomfields()
    {
        $customfields = $this->entityManager
            ->getRepository('JirasticBundle:Customfield')
            ->findOneByUser($this->token->getToken()->getUser());
        return $customfields;
    }

    /**
     * @return bool
     */
    public function customFieldsSet()
    {
        if ($this->getCustomfields()) {
            return true;
        }
        
        return false;
    }
}
