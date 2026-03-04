<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource;

use comcduarte\Box\API\Enum\ResourceType;
use JsonSerializable;

class BaseResource implements JsonSerializable
{
    use HydrationTrait;

    public string $id = '';

    public ResourceType $type;

    public function getId()
    {
        return $this->id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setType($type)
    {
        $this->type = ResourceType::from($type);
        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->getId(),
            'type' => $this->getType()->value,
        ];
    }
}