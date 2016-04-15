<?php
/**
 * Issue Repository provides data for Issues
 */

namespace JirasticBundle\Repository\Jira;

use JirasticBundle\Gateway\JiraGateway;
use JirasticBundle\Prototype\IssuePrototype;
use JirasticBundle\Repository\Jira\IssueRepositoryInterface;
use JirasticBundle\Util\ConfigUtils;
use Symfony\Component\DependencyInjection\Container;

/**
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 *
 * Class IssueRepository
 */
class IssueRepository implements IssueRepositoryInterface
{
    /**
     * @var JiraGateway
     */
    private $jiraGateway;

    /**
     * @var array
     */
    private $issues;

    /**
     * @var IssuePrototype
     */
    private $issuePrototype;

    /**
     * @var ConfigUtils
     */
    private $configUtils;

    /**
     * @var Container
     */
    private $container;

    /**
     * IssueRepository constructor
     * @param JiraGateway    $jiraGateway                Gateway
     * @param IssuePrototype $issuePrototype             Issue Prototype
     * @param ConfigUtils    $configUtils                ConfigUtils
     * @param Container      $container                  Container
     */
    public function __construct(
        JiraGateway $jiraGateway,
        IssuePrototype $issuePrototype,
        ConfigUtils $configUtils,
        Container $container
    ) {
        $this->jiraGateway = $jiraGateway;
        $this->issuePrototype = $issuePrototype;
        $this->configUtils = $configUtils;
        $this->container = $container;
    }

    /**
     * initialize all Issues
     *
     * @param array $allSprintIssues All issues
     * @throws \Exception
     * @return void
     */
    private function initIssues($allSprintIssues)
    {
        if (!empty($allSprintIssues)) {
            $allIssuesDetails = $this->jiraGateway->getRequest(
                '/rest/api/latest/search?jql=key in (%s)&expand=renderedFields',
                implode(
                    ',',
                    array_map(
                        function ($item) {
                            return $item->key;
                        },
                        $allSprintIssues
                    )
                )
            )->issues;
        } else {
            $allIssuesDetails = [];
        }

        $statusMapping = $this->configUtils->getStatusMapping();
        $mappedIssues = $this->configUtils->getMappedIssues();

        foreach ($allIssuesDetails as $issue) {

            $issueObj = clone $this->issuePrototype;
            $statusId = $issue->fields->status->id;

            if (!array_key_exists($statusId, $statusMapping)) {
                throw new \Exception(
                    sprintf(
                        'The following states aren\'t configured or mapped: %s (%d)',
                        $issue->fields->status->name,
                        $statusId
                    )
                );
            } else {
                $issueObj->setKey($issue->key);
            }

            $issueObj->setId($issue->id);
            $issueObj->setSummary($issue->fields->summary);
            $issueObj->setCreatorName($issue->fields->creator->displayName);
            $issueObj->setDescription($issue->renderedFields->description);

            $customfields = $this->configUtils->getCustomfields();
            $testInstructions = $customfields->getTestinstructions();
            if (property_exists($issue->renderedFields, $testInstructions)) {
                $issueObj->setTestInstruction(
                    $issue->renderedFields->{$testInstructions}
                );
            }
            
            $ownerName = $customfields->getStoryOwner();
            if (property_exists($issue->fields, $ownerName) && isset($issue->fields->{$ownerName})) {
                $issueObj->setOwnerName($issue->fields->{$ownerName}->displayName);
            }

            if (isset($issue->fields->assignee->displayName)) {
                $issueObj->setAssigneeName($issue->fields->assignee->displayName);
            }

            $storyPoints = $customfields->getStoryPoints();
            if (property_exists($issue->fields, $storyPoints)) {
                $issueObj->setStoryPoints($issue->fields->{$storyPoints});
            }

            $storyPointsEstimated = $customfields->getStoryPointsEstimated();
            if (property_exists($issue->fields, $storyPointsEstimated)) {
                $issueObj->setStoryPointsEstimate($issue->fields->{$storyPointsEstimated});
            }

            $mappedIssues[$statusMapping[$statusId]]['issues'][] = $issueObj;
        }

        $mappedIssues = array_map(
            function ($item) {
                $item['total'] = (array_key_exists('issues', $item) ? count($item['issues']) : 0);
                return $item;
            },
            $mappedIssues
        );

        $this->issues = $mappedIssues;
    }

    /**
     * Returns all Issues
     *
     * @param array $allSPrintIssues All issues
     * @return array
     * @throws \Exception
     */
    public function getAllIssues($allSPrintIssues)
    {
        if (!$this->issues) {
            $this->initIssues($allSPrintIssues);
        }

        return $this->issues;
    }
}
