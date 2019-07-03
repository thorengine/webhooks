<?php
namespace WebhooksApi\Github\Event;

class Repository extends Event
{
    public function process()
    {
        if ($this->isCreated()) {

            $container = $this->getContainer();
            $githubClient = $container->get('httpClient');

            // We need to wait a bit until the branch is created otherwise the request fails
            sleep(1);

            // Apply master branch protection
            $protectionUri = "/repos/{$this->getRepository()->getFullName()}/branches/master/protection";
            $protectionPayload = array(
                'required_status_checks' => null,
                'enforce_admins' => null,
                'required_pull_request_reviews' => array(
                    'dismissal_restrictions' => array(
                        'users' => array('proofek'),
                        'teams' => array()
                    ),
                    'dismiss_stale_reviews' => true,
                    'require_code_owner_reviews' => true,
                    'required_approving_review_count' => 2
                ),
                'restrictions' => array(
                    'users' => array('proofek'),
                    'teams' => array()
                )
            );

            $apiResponse = $githubClient->put(
                $protectionUri, 
                array(
                    'headers' => array(
                        'Accept' => 'application/vnd.github.luke-cage-preview+json'
                    ),
                    'body' => json_encode($protectionPayload)
                )
            );

            $responseCode = $apiResponse->getStatusCode();
            if ($responseCode == '200') {
                $container->get('logger')->info("Protection applied for master branch in '{$this->getRepository()->getFullName()}'");
            }

            // Raise an issue to inform about protection applied
            $issueUri = "/repos/{$this->getRepository()->getFullName()}/issues";
            $issuePayload = array(
                'title' => "Protection applied for master branch",
                'body' => <<<TXT
Notification for @proofek. Protection applied for master branch with the following settings:
- Require pull request reviews before merging: yes
  - Required approving reviews: 2
  - Dismiss stale pull request approvals when new commits are pushed: yes
  - Require review from Code Owners: yes
  - Restrict who can dismiss pull request reviews: proofek
- Require status checks to pass before merging: no
- Require signed commits: no
- Include administrators: no
- Restrict who can push to matching branches: proofek
TXT
            );

            $apiResponse = $githubClient->post(
                $issueUri, 
                array(
                    'body' => json_encode($issuePayload)
                )
            );
            $responseCode = $apiResponse->getStatusCode();
            if ($responseCode == '201') {
                $container->get('logger')->info("Issue successfully raised in '{$this->getRepository()->getFullName()}'");
            }

        }
    }
}
