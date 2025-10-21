<?php

declare(strict_types=1);

namespace comcduarte\Box\API\Resource\Workflow;

use comcduarte\Box\API\Resource\FullResource;


class Flow extends FullResource
{
    public string $type = 'flow';
    public array $outcomes;
    public Trigger $trigger;
}