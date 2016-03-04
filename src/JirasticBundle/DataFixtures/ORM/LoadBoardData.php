<?php
/**
 * Initial Data for Boards
 */
namespace JirasticBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JirasticBundle\Entity\Board;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @package JirasticBundle\DataFixtures\ORM
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class LoadBoardData
 */
class LoadBoardData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * URI for boards
     * Another possible endpoint: /rest/greenhopper/1.0/rapidview
     */
    const URI = '/rest/agile/1.0/board?type=scrum';

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container Service Container
     * @return void
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load initial Data
     *
     * @param ObjectManager $manager Object Manager
     * @return void
     */
    public function load(ObjectManager $manager)
    {
        $jiratGateway = $this->container->get('jirastic.gateway.jira');
        $response = $jiratGateway->getRequest(self::URI);

        foreach ($response->values as $jiraBoard) {
            $board = new Board();
            $board->setName($jiraBoard->name);
            $board->setJiraId($jiraBoard->id);
            $manager->persist($board);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 0;
    }
}
