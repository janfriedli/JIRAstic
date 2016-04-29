<?php
/**
 * Initial Data for Statuses
 */
namespace JirasticBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JirasticBundle\Entity\Status;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * @package JirasticBundle\DataFixtures\ORM
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class LoadStatusData
 */
class LoadStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * Load initial Data
     *
     * @param ObjectManager $manager Object Manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getinitialData() as $data) {
            $state = new Status();
            $state->setTitle($data['title']);
            $state->setTitleShort($data['titleShort']);
            $state->setBgcolor($data['bgcolor']);
            $state->setClass($data['class']);
            $state->setIcon($data['icon']);
            $state->setOrderId($data['order']);

            $manager->persist($state);
        }

        //make sure we can find the states
        $manager->flush();
        $states = $manager->getRepository('JirasticBundle:Status')->findAll();
        $boards = $manager->getRepository('JirasticBundle:Board')->findAll();

        if (!$boards) {
            throw new Exception(
                'No boards available'
            );
        }



        //@todo find a nicer way
        foreach ($boards as $board) {
            foreach ($states as $state) {
                $board->addStatus($state);
            }
            $manager->persist($board);
        }

        $manager->flush();

        $this->applyMapping($manager, array(1, 4), 'open');
        $this->applyMapping($manager, array(5), 'resolved');
        $this->applyMapping($manager, array(6), 'closed');
        $this->applyMapping($manager, array(3), 'in progress');
    }

    /**
     * Do the mapping
     *
     * @param ObjectManager $manager    Object Manager
     * @param array         $ids        Array of Id's
     * @param string        $titleShort Shortened Title
     * @return void
     */
    private function applyMapping($manager, $ids, $titleShort)
    {
        $statusMapping = $manager->getRepository('JirasticBundle:StatusMapping')
            ->findBy(array('fieldName' => $ids));
        $open = $manager->getRepository('JirasticBundle:Status')->findOneBy(array('titleShort' => $titleShort));
        $this->addMappings($statusMapping, $open, $manager);
    }

    /**
     * @param array         $statusMapping StatusMappings
     * @param State         $state         State
     * @param ObjectManager $manager       Object Manager
     * @return void
     */
    private function addMappings($statusMapping, $state, $manager)
    {
        foreach ($statusMapping as $map) {
            $state->addStatusMapping($map);
            $manager->persist($state);
        }

        $manager->flush();
    }

    /**
     * serve initial data
     *
     * @return array
     */
    private function getinitialData()
    {
        return array(
            array(
                'title' =>'closed stories',
                'titleShort' => 'closed',
                'bgcolor' => '#1e7145',
                'class' => 'resolved',
                'icon' => 'fa-check-circle-o',
                'order' => 0
            ),
            array(
                'title' =>'resolved stories',
                'titleShort' => 'resolved',
                'bgcolor' => '#1e7145',
                'class' => 'resolved',
                'icon' => 'fa-check-circle-o',
                'order' => 10
            ),
            array(
                'title' =>'stories in progress',
                'titleShort' => 'in progress',
                'bgcolor' => '#2b5797',
                'class' => 'inprogress',
                'icon' => 'fa-cog',
                'order' => 20
            ),
            array(
                'title' =>'unresolved stories',
                'titleShort' => 'open',
                'bgcolor' => '#b91d47',
                'class' => 'unresolved',
                'icon' => 'fa-exclamation-triangle',
                'order' => 30
            )
        );
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 2;
    }
}
