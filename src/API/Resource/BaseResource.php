<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ResourceType;

class BaseResource
{

    use HydrationTrait;

    public string $id;

    public ResourceType $type;
}