<?php

declare(strict_types=1);

namespace comcduarte\Box\API\Resource\Workflow;

use comcduarte\Box\API\Exception\ClientErrorException;
use comcduarte\Box\API\Resource\AbstractResource;
use comcduarte\Box\API\Resource\ClientError;
use comcduarte\Box\API\Resource\Query;
use comcduarte\Box\API\Resource\Workflows;

class Workflow extends AbstractResource
{
    public string $type = 'workflow';
    public string $description;
    
    /**
     * Array of Flow Objects
     * @var array
     */
    public array $flows;
    
    public bool $is_enabled;
    public string $name;
   
    public function list_workflows(Query $query = null): Workflows|ClientError
    {
        if (!isset($query)) {
            throw new ClientErrorException('query not set.');
        }
        
        $endpoint = 'https://api.box.com/2.0/workflows';
        $params = [
        ];
        
        if (isset($query)) {
            $endpoint .= '?:query';
            $params[':query'] = '';
            
            foreach ($query->getArrayCopy() as $field => $value) {
                $params[':query'] .= sprintf('%s=%s', $field, $value);
            }
        }
        
        $uri = $this->generate_uri($endpoint, $params);
        $this->response = $this->get($uri);
        
        switch ($this->response->getStatusCode())
        {
            case 200:
                /**
                 * Returns the workflow.
                 */
                $workflows = new Workflows();
                $workflows->hydrate($this->response);
                return $workflows;
            case 400:
                /**
                 * Returned if the trigger type is not WORKFLOW_MANUAL_START
                 */
            case 404:
                /**
                 * Returned if the folder is not found, or the user does not have access to the folder.
                 */
            default:
                /**
                 * An unexpected client error.
                 */
                return $this->error();
        }
    }
}