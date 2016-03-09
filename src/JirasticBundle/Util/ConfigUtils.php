<?php
/**
 * Prepares the Config data
 */
namespace JirasticBundle\Util;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

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
     * ConfigUtils constructor.
     * @param EntityManager $entityManager Entity Manager
     * @param Container     $container     Container
     */
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
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
}