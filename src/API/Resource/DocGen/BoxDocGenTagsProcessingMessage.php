<?php
declare(strict_types = 1);
namespace comcduarte\Box\API\Resource\DocGen;

use comcduarte\Box\API\Resource\HydrationTrait;

class BoxDocGenTagsProcessingMessage
{
    use HydrationTrait;

    public string $message;
}