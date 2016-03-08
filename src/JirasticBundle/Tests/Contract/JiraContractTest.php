<?php
/**
 * Contract Test for the JIRA API
 */

namespace JirasticBundle\Tests\Contract;

use Guzzle\Http\Client;

use JirasticBundle\Gateway\JiraGateway;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @package JirasticBundle\Tests\Contract
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class JiraContractTest
 */
class JiraContractTest extends WebTestCase
{
    /**
     * @var JiraGateway
     */
    private $jiraGateway;

    /**
     * check status endpoint
     *
     * @return void
     */
    public function testStatusEndpoint()
    {
        $response = $this->jiraGateway->getRequest('/rest/api/2/status');

        $this->assertTrue(array_key_exists('name', $response[0]));
        $this->assertTrue(array_key_exists('id', $response[0]));
        $this->assertInternalType('string', $response[0]->name);
        //wtf api?
        $this->assertInternalType('string', $response[0]->id);
    }

    /**
     * check board endpoint
     *
     * @return void
     */
    public function testBoardsEndpoint()
    {
        $response = $this->jiraGateway->getRequest('/rest/agile/1.0/board?type=scrum');
        $this->assertInternalType('integer', $response->values[0]->id);
        $this->assertInternalType('string', $response->values[0]->name);
        $this->assertEquals('scrum', $response->values[0]->type);
    }

    /**
     * check sprints and issue endpoints
     *
     * @return void
     */
    public function testSprintsandIssueEndpoint()
    {
        $response = $this->jiraGateway->getRequest('/rest/greenhopper/1.0/rapidview');
        $boardId = $response->views[5]->id;
        $response = $this->jiraGateway->getRequest(
            '/rest/greenhopper/latest/sprintquery/%d?includeHistoricSprints=true&includeFutureSprints=false',
            array($boardId)
        );

        $this->assertInternalType('integer', $response->sprints[0]->id);
        $this->assertInternalType('string', $response->sprints[0]->name);
        $this->assertInternalType('string', $response->sprints[0]->state);

        $sprintData = $this->jiraGateway->getRequest(
            '/rest/greenhopper/latest/rapid/charts/sprintreport?rapidViewId=%d&sprintId=%d',
            array($boardId, $response->sprints[0]->id)
        );

        $this->assertInternalType('string', $sprintData->sprint->startDate);
        $this->assertInternalType('string', $sprintData->sprint->endDate);
        $this->assertObjectHasAttribute('completedIssues', $sprintData->contents);
        $this->assertObjectHasAttribute('incompletedIssues', $sprintData->contents);

        $allIssues = array_merge(
            $sprintData->contents->completedIssues,
            $sprintData->contents->incompletedIssues
        );

        $allIssuesDetails = $this->jiraGateway->getRequest(
            '/rest/api/latest/search?jql=key in (%s)&expand=renderedFields',
            implode(
                ',',
                array_map(
                    function ($item) {
                        return $item->key;
                    },
                    $allIssues
                )
            )
        )->issues;

        $this->assertInternalType('string', $allIssuesDetails[0]->id);
        $this->assertInternalType('string', $allIssuesDetails[0]->fields->summary);
        $this->assertInternalType('string', $allIssuesDetails[0]->fields->creator->displayName);
        $this->assertInternalType('string', $allIssuesDetails[0]->renderedFields->description);

    }

    /**
     * prepare for each test
     *
     * @return void
     */
    public function setUp()
    {
        $client = static::createClient();
        $container = $client->getKernel()->getContainer();
        $client = new Client($container->getParameter('jirastic_api_url'));
        $this->jiraGateway = new JiraGateway(
            $container->getParameter('jirastic_username'),
            $container->getParameter('jirastic_password'),
            $client
        );
    }
}
