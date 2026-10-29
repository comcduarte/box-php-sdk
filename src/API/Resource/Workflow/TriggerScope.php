<?php

declare(strict_types=1);

namespace comcduarte\Box\API\Resource\Workflow;

use comcduarte\Box\API\Resource\BaseResource;

class TriggerScope extends BaseResource
{
    public string $type = 'trigger_scope';
    public mixed $object;
    public string $ref;
}