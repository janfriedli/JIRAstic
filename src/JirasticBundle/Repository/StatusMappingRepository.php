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
    /**
     * insert all missing jira statuses in the db
     *
     * @param EntityManager $em          Entity Manager
     * @param JiraGateway   $jiraGateway Jira Gateway
     * @return void
     */
    public function loadStatusesFromJira(EntityManager $em, JiraGateway $jiraGateway)
    {
        $states = $jiraGateway->getRequest('/rest/api/2/status');

        foreach ($states as $state) {
            if (!$this->checkIfExistInDb($em, $state)) {
                $newStatusMapping = new StatusMapping();
                $newStatusMapping->setFieldName($state->id);
                $newStatusMapping->setDescription($state->description);
                $newStatusMapping->setName($state->name);
                $em->persist($newStatusMapping);
            }
        }

        $em->flush();
    }

    /**
     * make sure entry doesn't already exist
     *
     * @param EntityManager $em    Em
     * @param State         $state State
     * @return bool
     */
    private function checkifExistInDb($em, $state)
    {
        $flag = $em->getRepository('JirasticBundle:StatusMapping')->findOneBy(array('fieldName' => $state->id));
        if ($flag) {
            return true;
        }

        return false;
    }
}
