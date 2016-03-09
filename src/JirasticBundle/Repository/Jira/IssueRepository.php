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
     * IssueRepository constructor.
     * @param JiraGateway    $jiraGateway    Gateway
     * @param IssuePrototype $issuePrototype Issue Prototype
     * @param ConfigUtils    $configUtils    ConfigUtils
     * @param Container      $container      Container
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
            $issueObj->setTestInstruction(
                $issue->renderedFields->{'customfield_'.$this->container->getParameter(
                    'jirastic_testInstructionFieldId'
                )}
            );
            $issueObj->setCreatorName($issue->fields->creator->displayName);
            $issueObj->setDescription($issue->renderedFields->description);


            $ownerFieldId = $this->container->getParameter('jirastic_ownerNameFieldId');
            if ($issue->fields->{'customfield_'.'16025'}) {
                $issueObj->setOwnerName($issue->fields->{'customfield_'.$ownerFieldId}->displayName);
            }

            if (isset($issue->fields->assignee->displayName)) {
                $issueObj->setAssigneeName($issue->fields->assignee->displayName);
            }

            $storyPointsFieldId = $this->container->getParameter('jirastic_storyPointsFieldId');
            if (isset($issue->fields->{'customfield_'.$storyPointsFieldId})) {
                $issueObj->setStoryPoints($issue->fields->{'customfield_'.$storyPointsFieldId});
            }

            $storyPointsEstimateFieldId = $this->container->getParameter('jirastic_storyPointsEstimateFieldId');
            if (isset($issue->fields->{'customfield_'.$storyPointsEstimateFieldId})) {
                $issueObj->setStoryPointsEstimate($issue->fields->{'customfield_'.$storyPointsEstimateFieldId});
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
