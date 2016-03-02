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
     * ConfigUtils constructor.
     * @param EntityManager $entityManager Entity Manager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
