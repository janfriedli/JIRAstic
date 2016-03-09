<?php
/**
 * Initial Data for Status Mapping
 */
namespace JirasticBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JirasticBundle\Entity\StatusMapping;

/**
 * @package JirasticBundle\DataFixtures\ORM
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class LoadStatusMappingData
 */
class LoadStatusMappingData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load initial Data
     *
     * @param ObjectManager $manager Object Manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getInitialData() as $data) {
            $statusMapping = new StatusMapping();
            $statusMapping->setName($data['name']);
            $statusMapping->setDescription($data['description']);
            $statusMapping->setFieldName($data['fieldName']);

            $manager->persist($statusMapping);
        }

        $manager->flush();
    }

    /**
     * serve initial data
     *
     * @return array
     */
    private function getInitialData()
    {
        return array(
            array(
                'name' =>'Open',
                'description' => 'The issue is open and ready for the assignee to start work on it.',
                'fieldName' => 1,
            ),
            array(
                'name' =>'Reopened',
                'description' => 'This issue was once resolved, but the resolution was deemed incorrect.'.
                    'From here issues are either marked assigned or resolved.',
                'fieldName' => 4,
            ),
            array(
                'name' =>'Resolved',
                'description' => 'A resolution has been taken, and it is awaiting verification by reporter.'.
                    ' From here issues are either reopened, or are closed.',
                'fieldName' => 5,
            ),
            array(
                'name' =>'In Progress',
                'description' => 'A resolution has been taken, and it is awaiting verification by reporter. '.
                    'From here issues are either reopened, or are closed.',
                'fieldName' => 3,
            ),
            array(
                'name' =>'Closed',
                'description' => 'The issue is considered finished, the resolution is correct. '.
                    'Issues which are closed can be reopened.',
                'fieldName' => 6,
            )
        );
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
