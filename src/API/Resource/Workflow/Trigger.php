<?php

declare(strict_types=1);

namespace comcduarte\Box\API\Resource\Workflow;

use comcduarte\Box\API\Resource\BaseResource;

class Trigger extends BaseResource
{
    public string $type = 'trigger';
    
    /**
     * Array of Trigger Scopes
     * @var array
     */
    public array $scope;
    public string $trigger_type = 'WORKFLOW_MANUAL_START';
}