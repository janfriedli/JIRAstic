<?php
/**
 * StatusMappingRepository
 */
namespace JirasticBundle\Repository;

use Doctrine\ORM\EntityManager;
use JirasticBundle\Entity\StatusMapping;
use JirasticBundle\Gateway\JiraGateway;

/**
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class StatusMappingRepository
 */
class StatusMappingRepository extends \Doctrine\ORM\EntityRepository
{
}
