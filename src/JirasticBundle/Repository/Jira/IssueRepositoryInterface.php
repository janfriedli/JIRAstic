<?php
/**
 * IssueRepository Interface
 */

namespace JirasticBundle\Repository\Jira;

/**
 * Interface to make sure every Issue repository behaves the same
 * @package JirasticBundle\Repository
 * @author   Jan Friedli <jan.friedli@swisscom.com>
 * @license  https://opensource.org/licenses/GPL-3.0 Public License
 * @link     http://www.swisscom.ch
 */
interface IssueRepositoryInterface
{
    /**
     * Returns all Issues
     * @param array $allSPrintIssues All issues belonging to this sprint
     * @return array
     */
    public function getAllIssues($allSPrintIssues);
}
